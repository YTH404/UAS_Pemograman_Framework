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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Fachrizal',
            'username' => 'admin',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Alviano',
            'username' => 'teacher',
            'password' => bcrypt('12345678'),
            'role' => 'teacher',
        ]);

        User::factory()->create([
            'name' => 'Yesaya',
            'username' => 'student',
            'password' => bcrypt('12345678'),
            'role' => 'student',
        ]);
    }
}
