<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bantayresolve.com'],
            [
                'name' => 'BantayResolve Admin',
                'email' => 'admin@bantayresolve.com',
                'role' => 'admin',
                'password' => bcrypt('admin123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@bantayresolve.com'],
            [
                'name' => 'BantayResolve Staff',
                'email' => 'staff@bantayresolve.com',
                'role' => 'staff',
                'password' => bcrypt('staff123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'citizen@bantayresolve.com'],
            [
                'name' => 'Sample Citizen',
                'email' => 'citizen@bantayresolve.com',
                'role' => 'citizen',
                'password' => bcrypt('citizen123'),
            ]
        );
    }
}
