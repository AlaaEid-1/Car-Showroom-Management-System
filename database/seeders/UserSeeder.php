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
    User::create([
        'name' => 'Alaa Eid',
        'email' => 'AlaaEid@gmail.com',
        'username' => 'alaaeid',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'status' => 'active',
    ]);

    // Dealer 1
    User::create([
        'name' => 'Menna Eid',
        'email' => 'MennaEid@gmail.com',
        'username' => 'mennaeid',
        'password' => Hash::make('password'),
        'role' => 'dealer',
        'status' => 'active',
    ]);

    // Dealer 2
    User::create([
        'name' => 'Sami Eid',
        'email' => 'SamiEid@gmail.com',
        'username' => 'samieid',
        'password' => Hash::make('password'),
        'role' => 'dealer',
        'status' => 'active',
    ]);

    // Customers
    User::create([
        'name' => 'Manal Eid',
        'email' => 'ManalEid@gmail.com',
        'username' => 'manaleid',
        'password' => Hash::make('password'),
        'role' => 'customer',
        'status' => 'active',
    ]);

    User::create([
        'name' => 'Batool Eid',
        'email' => 'BatoolEid@gmail.com',
        'username' => 'batooleid',
        'password' => Hash::make('password'),
        'role' => 'customer',
        'status' => 'active',
    ]);
    User::create([
        'name' => 'Israa Eid',
        'email' => 'IsraaEid@gmail.com',
        'username' => 'israaeid',
        'password' => Hash::make('password'),
        'role' => 'customer',
        'status' => 'active',
    ]);
    User::create([
        'name' => 'Afnan Eid',
        'email' => 'AfnanEid@gmail.com',
        'username' => 'afnaneid',
        'password' => Hash::make('password'),
        'role' => 'customer',
        'status' => 'active',
    ]);
    }
}
