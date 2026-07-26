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
        $dealer1 = User::firstOrCreate(
            ['email' => 'MennaEid@gmail.com'],
            [
                'name' => 'Menna Eid',
                'username' => 'mennaeid',
                'password' => 'password',
                'role' => 'dealer',
                'status' => 'active',
            ]
        );

        $dealer2 = User::firstOrCreate(
            ['email' => 'mohammed@gmail.com'],
            [
                'name' => 'Mohammed',
                'username' => 'mohammed',
                'password' => 'password',
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
