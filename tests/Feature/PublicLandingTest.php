<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicLandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_displays_live_report_totals(): void
    {
        User::factory()->count(2)->create();
        Report::factory()->count(3)->create([
            'status' => 'Pending',
        ]);
        Report::factory()->create([
            'status' => 'Resolved',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('4 Total Reports');
        $response->assertSee('1 Issues Resolved');
    }
}
