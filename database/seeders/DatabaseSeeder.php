<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!User::where('email', 'admin@gerritsen.nl')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@gerritsen.nl',
                'password' => Hash::make('NieuwSterkWachtwoord!'),
            ]);
        }
    }
}
