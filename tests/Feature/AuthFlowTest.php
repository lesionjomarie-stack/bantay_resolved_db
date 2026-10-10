<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_access_dashboard(): void
    {
        User::factory()->create([
            'name' => 'BantayResolve Admin',
            'email' => 'admin@bantayresolve.com',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@bantayresolve.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs(User::where('email', 'admin@bantayresolve.com')->first());
    }

    public function test_citizen_can_login_and_access_dashboard(): void
    {
        User::factory()->create([
            'name' => 'Sample Citizen',
            'email' => 'citizen@bantayresolve.com',
            'role' => 'citizen',
            'password' => bcrypt('citizen123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'citizen@bantayresolve.com',
            'password' => 'citizen123',
        ]);

        $response->assertRedirect('/citizen/dashboard');
        $this->assertAuthenticatedAs(User::where('email', 'citizen@bantayresolve.com')->first());
    }

    public function test_admin_dashboard_is_blocked_for_citizen_users(): void
    {
        $citizen = User::factory()->create([
            'name' => 'Citizen User',
            'email' => 'citizen.user@example.com',
            'role' => 'citizen',
            'password' => bcrypt('citizen123'),
        ]);

        $this->actingAs($citizen)->get('/admin/dashboard')
            ->assertRedirect('/citizen/dashboard');
    }

    public function test_citizen_dashboard_is_blocked_for_admin_users(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Only',
            'email' => 'admin.user@example.com',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        $this->actingAs($admin)->get('/citizen/dashboard')
            ->assertRedirect('/admin/dashboard');
    }

    public function test_admin_can_publish_announcement_and_it_is_visible_on_admin_page(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin.announcement@example.com',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        $this->actingAs($admin)->post('/admin/announcements', [
            'title' => 'Road Repair Notice',
            'priority' => 'High Priority',
            'content' => 'The road will be closed tomorrow from 8am to 12pm.',
        ])->assertRedirect();

        $this->actingAs($admin)->get('/admin/announcements')
            ->assertSee('Road Repair Notice')
            ->assertSee('High Priority');
    }

    public function test_admin_can_delete_activity_log(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Log Deleter',
            'email' => 'admin.log@example.com',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        $log = ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'Published Announcement',
            'description' => 'Test log entry',
            'ip_address' => '127.0.0.1',
        ]);

        $this->actingAs($admin)->delete('/admin/logs/'.$log->id)
            ->assertRedirect();

        $this->assertDatabaseMissing('activity_logs', ['id' => $log->id]);
    }
}
