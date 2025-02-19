<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,  // Pastikan ini ada
        ]);

        User::create([
            'name' => 'Ryan',
            'email' => 'valeryan@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Valey',
            'email' => 'ryanvaley@gmail.com',
            'password' => Hash::make('users'),
            'role' => 'users',
        ]);

        User::create([
            'name' => 'WahyuPentil',
            'email' => 'wahyupentil@gmail.com',
            'password' => Hash::make('users'),
            'role' => 'users',
        ]);

    }
}
