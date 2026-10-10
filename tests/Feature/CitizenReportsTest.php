<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CitizenReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_citizen_can_view_their_reports(): void
    {
        $citizen = User::factory()->create(['role' => 'citizen']);
        Report::factory()->create([
            'user_id' => $citizen->id,
            'category' => 'Infrastructure',
            'location' => 'Main Road',
            'description' => 'The road is damaged.',
            'status' => 'Pending',
            'photo' => null,
        ]);
        Report::factory()->create([
            'user_id' => $citizen->id,
            'category' => 'Utilities',
            'location' => 'Electric pole',
            'description' => 'Power line is down.',
            'status' => 'Resolved',
            'photo' => json_encode(['reports/first.jpg', 'reports/second.jpg']),
        ]);

        $response = $this
            ->actingAs($citizen)
            ->get(route('citizen.reports.index'));

        $response->assertOk();
        $response->assertSee('My Submitted Reports');
        $response->assertSee('The road is damaged.');
        $response->assertSee('Power line is down.');
        $response->assertSee('View All Photos');
    }

    public function test_citizen_can_filter_reports_by_status(): void
    {
        $citizen = User::factory()->create(['role' => 'citizen']);
        Report::factory()->create([
            'user_id' => $citizen->id,
            'status' => 'Pending',
        ]);
        Report::factory()->create([
            'user_id' => $citizen->id,
            'status' => 'Resolved',
        ]);

        $response = $this
            ->actingAs($citizen)
            ->get(route('citizen.reports.index', ['status' => 'Resolved']));

        $response->assertOk();
        $response->assertSee('Resolved');
        $response->assertDontSee('Pending');
    }
}
