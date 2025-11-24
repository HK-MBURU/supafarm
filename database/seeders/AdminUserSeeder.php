<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make a specific user admin (replace email with your user's email)
        User::where('email', 'your-admin@email.com')->update([
            'is_admin' => true
        ]);

        // Or create a new admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@supafarmsupplies.com',
            'password' => bcrypt('Supafarm@123'),
            'is_admin' => true,
        ]);
    }
}
