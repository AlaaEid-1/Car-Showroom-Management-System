<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        $adminEmail = trim(env('ADMIN_EMAIL', 'admin@example.com'));
        
        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => env('ADMIN_NAME', 'Admin'),
                'username' => explode('@', $adminEmail)[0],
                'password' => env('ADMIN_PASSWORD', 'change_me'),
                'status' => 'active',
                'role' => 'admin',
            ]
        );

        // Dealer 1
        User::firstOrCreate(
            ['email' => 'MennaEid@gmail.com'],
            [
                'name' => 'Menna Eid',
                'username' => 'mennaeid',
                'password' => 'password',
                'role' => 'dealer',
                'status' => 'active',
            ]
        );

        // Dealer 2
        User::firstOrCreate(
            ['email' => 'mohammed@gmail.com'],
            [
                'name' => 'Mohammed',
                'username' => 'mohammed',
                'password' => 'password',
                'role' => 'dealer',
                'status' => 'active',
            ]
        );

        // Customer 1
        User::firstOrCreate(
            ['email' => 'ahmed@gmail.com'],
            [
                'name' => 'Ahmed',
                'username' => 'ahmed',
                'password' => 'password',
                'role' => 'customer',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'BatoolEid@gmail.com'],
            [
                'name' => 'Batool Eid',
                'username' => 'batooleid',
                'password' => 'password',
                'role' => 'customer',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'IsraaEid@gmail.com'],
            [
                'name' => 'Israa Eid',
                'username' => 'israaeid',
                'password' => 'password',
                'role' => 'customer',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'AfnanEid@gmail.com'],
            [
                'name' => 'Afnan Eid',
                'username' => 'afnaneid',
                'password' => 'password',
                'role' => 'customer',
                'status' => 'active',
            ]
        );
    }
}
