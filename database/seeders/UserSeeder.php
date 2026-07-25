<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => env('ADMIN_NAME', 'Admin'),
                'username' => explode('@', env('ADMIN_EMAIL', 'admin@example.com'))[0],
                'password' => Hash::make(env('ADMIN_PASSWORD', 'change_me')),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Dealer 1
        User::firstOrCreate(
            ['email' => 'MennaEid@gmail.com'],
            [
                'name' => 'Menna Eid',
                'username' => 'mennaeid',
                'password' => Hash::make('password'),
                'role' => 'dealer',
                'status' => 'active',
            ]
        );

        // Dealer 2
        User::firstOrCreate(
            ['email' => 'SamiEid@gmail.com'],
            [
                'name' => 'Sami Eid',
                'username' => 'samieid',
                'password' => Hash::make('password'),
                'role' => 'dealer',
                'status' => 'active',
            ]
        );

        // Customers
        User::firstOrCreate(
            ['email' => 'ManalEid@gmail.com'],
            [
                'name' => 'Manal Eid',
                'username' => 'manaleid',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'BatoolEid@gmail.com'],
            [
                'name' => 'Batool Eid',
                'username' => 'batooleid',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'IsraaEid@gmail.com'],
            [
                'name' => 'Israa Eid',
                'username' => 'israaeid',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'AfnanEid@gmail.com'],
            [
                'name' => 'Afnan Eid',
                'username' => 'afnaneid',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'active',
            ]
        );
    }
}
