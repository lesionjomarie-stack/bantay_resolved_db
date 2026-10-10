<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Report;
use App\Models\ReportStatusHistory;
use App\Models\User;
use App\Notifications\NewAnnouncementNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class StaffDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_sign_in_and_reaches_staff_dashboard(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $response = $this
            ->withSession([])
            ->post('/login', [
                'email' => $staff->email,
                'password' => 'password',
                'dashboard_type' => 'staff',
            ]);

        $response->assertRedirectToRoute('staff.dashboard');
        $this->assertAuthenticatedAs($staff);
    }

    public function test_staff_can_sign_in_without_selecting_an_access_area(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $response = $this
            ->withSession([])
            ->post('/login', [
                'email' => $staff->email,
                'password' => 'password',
            ]);

        $response->assertRedirectToRoute('staff.dashboard');
        $this->assertAuthenticatedAs($staff);
    }

    public function test_admin_and_citizen_can_sign_in_without_selecting_an_access_area(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $citizen = User::factory()->create(['role' => 'citizen']);

        $adminResponse = $this
            ->withSession([])
            ->post('/login', [
                'email' => $admin->email,
                'password' => 'password',
            ]);

        $adminResponse->assertRedirectToRoute('admin.dashboard');
        $this->assertAuthenticatedAs($admin);

        $this->app['auth']->logout();

        $citizenResponse = $this
            ->withSession([])
            ->post('/login', [
                'email' => $citizen->email,
                'password' => 'password',
            ]);

        $citizenResponse->assertRedirectToRoute('citizen.dashboard');
        $this->assertAuthenticatedAs($citizen);
    }

    public function test_citizen_cannot_access_staff_dashboard(): void
    {
        $citizen = User::factory()->create(['role' => 'citizen']);

        $response = $this->actingAs($citizen)->get('/staff/dashboard');

        $response->assertRedirectToRoute('citizen.dashboard');
    }

    public function test_staff_can_update_report_status_and_remarks(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $report = Report::factory()->create([
            'status' => 'Pending',
            'admin_remarks' => 'Original note',
        ]);

        $response = $this
            ->actingAs($staff)
            ->put('/staff/reports/'.$report->id, [
                'status' => 'In Progress',
                'admin_remarks' => 'Investigating the reported issue.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'In Progress',
            'admin_remarks' => 'Investigating the reported issue.',
        ]);
    }

    public function test_staff_can_update_status_and_responder_assignment(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $report = Report::factory()->create(['status' => 'Pending']);

        $response = $this
            ->actingAs($staff)
            ->put('/staff/reports/'.$report->id, [
                'status' => 'Rejected',
                'responder_name' => 'Barangay Safety Team',
                'admin_remarks' => 'Case closed after verification.',
            ]);

        $response->assertRedirectToRoute('staff.reports');
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'Rejected',
            'responder_name' => 'Barangay Safety Team',
            'admin_remarks' => 'Case closed after verification.',
        ]);
    }

    public function test_staff_report_status_change_is_audited(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $report = Report::factory()->create([
            'status' => 'Pending',
            'admin_remarks' => 'Original note',
        ]);

        $response = $this
            ->actingAs($staff)
            ->put('/staff/reports/'.$report->id, [
                'status' => 'Resolved',
                'admin_remarks' => 'Issue resolved and verified.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('report_status_histories', [
            'report_id' => $report->id,
            'user_id' => $staff->id,
            'from_status' => 'Pending',
            'to_status' => 'Resolved',
        ]);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $staff->id,
            'action' => 'Updated report status',
        ]);
    }

    public function test_staff_cannot_update_report_with_invalid_status(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $report = Report::factory()->create(['status' => 'Pending']);

        $response = $this
            ->actingAs($staff)
            ->put('/staff/reports/'.$report->id, [
                'status' => 'Cancelled',
                'admin_remarks' => 'Invalid status',
            ]);

        $response->assertSessionHasErrors('status');
        $this->assertDatabaseCount('report_status_histories', 0);
        $this->assertDatabaseHas('reports', ['id' => $report->id, 'status' => 'Pending']);
    }

    public function test_staff_can_publish_an_announcement_to_citizens(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $citizen = User::factory()->create(['role' => 'citizen']);
        Notification::fake();

        $response = $this
            ->actingAs($staff)
            ->post('/staff/announcements', [
                'title' => 'Water outage notice',
                'priority' => 'High Priority',
                'content' => 'The water supply will be unavailable until 6 PM.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('announcements', [
            'user_id' => $staff->id,
            'title' => 'Water outage notice',
            'priority' => 'High Priority',
        ]);
        Notification::assertSentTo($citizen, NewAnnouncementNotification::class);
    }

    public function test_staff_dashboard_renders_report_summary(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        Report::factory()->count(3)->create();

        $response = $this->actingAs($staff)->get('/staff/dashboard');

        $response->assertOk()
            ->assertSee('Staff Dashboard')
            ->assertSee('Community Reports');
    }

    public function test_staff_dashboard_metrics_count_each_status(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        Report::factory()->create(['status' => 'Pending']);
        Report::factory()->create(['status' => 'In Progress']);
        Report::factory()->create(['status' => 'Resolved']);
        Report::factory()->create(['status' => 'Rejected']);

        $response = $this->actingAs($staff)->get('/staff/dashboard');
        $html = $response->getContent();

        $response->assertOk();
        $this->assertMatchesRegularExpression('/data-metric="total"[^>]*>\s*4\s*<\/h2>/', $html);
        $this->assertMatchesRegularExpression('/data-metric="pending"[^>]*>\s*1\s*<\/h2>/', $html);
        $this->assertMatchesRegularExpression('/data-metric="in-progress"[^>]*>\s*1\s*<\/h2>/', $html);
        $this->assertMatchesRegularExpression('/data-metric="resolved"[^>]*>\s*1\s*<\/h2>/', $html);
        $this->assertMatchesRegularExpression('/data-metric="rejected"[^>]*>\s*1\s*<\/h2>/', $html);
    }

    public function test_staff_can_view_community_reports_with_photos_and_status_history(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $report = Report::factory()->create([
            'category' => 'Infrastructure',
            'location' => 'Main Road',
            'description' => 'The road is damaged.',
            'status' => 'Pending',
            'photo' => json_encode(['reports/first.jpg']),
        ]);
        ReportStatusHistory::create([
            'report_id' => $report->id,
            'user_id' => $staff->id,
            'from_status' => 'Pending',
            'to_status' => 'In Progress',
            'remarks' => 'Investigation started.',
        ]);

        $response = $this
            ->actingAs($staff)
            ->get(route('staff.reports'));

        $response->assertOk();
        $response->assertSee('Community Reports');
        $response->assertSee('The road is damaged.');
        $response->assertSee('Status history');
        $response->assertSee('Investigation started.');
    }

    public function test_staff_can_filter_reports_by_search_category_status_and_date(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $matchingReport = Report::factory()->create([
            'category' => 'Infrastructure',
            'location' => 'Main Road',
            'description' => 'Broken streetlight near the school',
            'status' => 'In Progress',
            'created_at' => now()->subDays(2),
        ]);
        Report::factory()->create([
            'category' => 'Sanitation',
            'location' => 'Market Road',
            'description' => 'Overflowing bins',
            'status' => 'Pending',
            'created_at' => now()->subDays(2),
        ]);

        $response = $this
            ->actingAs($staff)
            ->get(route('staff.reports', [
                'search' => 'school',
                'category' => 'Infrastructure',
                'status' => 'In Progress',
                'date' => now()->subDays(2)->toDateString(),
            ]));

        $response->assertOk()
            ->assertSee($matchingReport->id)
            ->assertDontSee('Overflowing bins');
    }

    public function test_staff_cannot_publish_an_invalid_announcement(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $response = $this
            ->actingAs($staff)
            ->post('/staff/announcements', [
                'title' => '',
                'priority' => 'Unknown',
                'content' => '',
            ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('announcements', 0);
    }

    public function test_staff_dashboard_does_not_allow_admin_only_user_management(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($staff)->get('/admin/users');

        $response->assertRedirectToRoute('staff.dashboard');
    }

    public function test_registration_shows_success_message_and_redirects_to_login(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Citizen',
            'email' => 'new-citizen@example.com',
            'role' => 'citizen',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirectToRoute('login')
            ->assertSessionHas('success', 'Registration successful. Welcome, New Citizen! Please sign in.');
        $this->assertDatabaseHas('users', [
            'email' => 'new-citizen@example.com',
            'role' => 'citizen',
        ]);
        $this->assertGuest();
    }

    public function test_staff_can_register_as_staff(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Staff',
            'email' => 'new-staff@example.com',
            'role' => 'staff',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirectToRoute('login');
        $this->assertDatabaseHas('users', [
            'email' => 'new-staff@example.com',
            'role' => 'staff',
        ]);
    }

    public function test_staff_can_view_only_their_own_dashboard(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $announcement = Announcement::factory()->create(['user_id' => $staff->id]);

        $response = $this->actingAs($staff)->get('/staff/announcements');

        $response->assertOk()
            ->assertSee($announcement->title);
    }
}
