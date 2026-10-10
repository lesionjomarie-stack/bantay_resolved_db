<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRateLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_attempts_are_limited_per_email_and_ip(): void
    {
        $response = $this->post('/login', [
            'email' => 'unknown@example.com',
            'password' => 'wrong-password',
        ]);

        for ($attempt = 1; $attempt < 5; $attempt++) {
            $response = $this->post('/login', [
                'email' => 'unknown@example.com',
                'password' => 'wrong-password',
            ]);

            $response->assertStatus(302);
        }

        $response = $this->post('/login', [
            'email' => 'unknown@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429);
        $response->assertSee('Too Many Requests');
    }
}
