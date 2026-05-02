<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@corporatekitchen.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '01712345678',
            'address' => 'Dhaka, Bangladesh',
        ]);

        // Create Regular User
        User::create([
            'name' => 'Test User',
            'email' => 'user@corporatekitchen.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '01912345678',
            'address' => 'Gulshan, Dhaka',
        ]);

        // Create another test user
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '01512345678',
            'address' => 'Banani, Dhaka',
        ]);

        // Call Menu Seeder
        $this->call(MenuSeeder::class);
    }
}
