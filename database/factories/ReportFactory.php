<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category' => $this->faker->randomElement(['Infrastructure', 'Public Safety', 'Health', 'Utilities', 'Self Help']),
            'location' => $this->faker->address(),
            'contact_number' => $this->faker->phoneNumber(),
            'facebook_link' => null,
            'description' => $this->faker->paragraph(),
            'photo' => null,
            'status' => $this->faker->randomElement(['Pending', 'In Progress', 'Resolved']),
            'admin_remarks' => null,
            'backed_count' => 0,
        ];
    }
}
