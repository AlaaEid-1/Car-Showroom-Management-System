<?php

namespace Database\Seeders;

use App\Models\Showroom;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShowroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        
        $dealer1 = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => env('ADMIN_NAME', 'Admin'),
                'username' => explode('@', $adminEmail)[0],
                'password' => \Illuminate\Support\Facades\Hash::make(env('ADMIN_PASSWORD', 'change_me')),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        $dealer2 = User::firstOrCreate(
            ['email' => 'MennaEid@gmail.com'],
            [
                'name' => 'Menna Eid',
                'username' => 'mennaeid',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'dealer',
                'status' => 'active',
            ]
        );

        Showroom::updateOrCreate(
            ['user_id' => $dealer1->id],
            [
                'name' => 'Dealer One Showroom',
                'description' => 'Luxury cars showroom',
                'location' => 'Gaza',
                'phone' => '0591111111',
                'is_active' => true,
            ]
        );

        Showroom::updateOrCreate(
            ['user_id' => $dealer2->id],
            [
                'name' => 'Dealer Two Showroom',
                'description' => 'Sport cars showroom',
                'location' => 'Ramallah',
                'phone' => '0592222222',
                'is_active' => true,
            ]
        );
    }
}
