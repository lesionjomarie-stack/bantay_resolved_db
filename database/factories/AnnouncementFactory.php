<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Announcement>
 */
class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(5),
            'priority' => $this->faker->randomElement(['General Notice', 'High Priority', 'Urgent Alert']),
            'content' => $this->faker->paragraph(),
        ];
    }
}
