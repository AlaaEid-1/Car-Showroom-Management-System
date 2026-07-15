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
        $dealer1 = User::where('email', 'AlaaEid@gmail.com')->first();
        $dealer2 = User::where('email', 'MennaEid@gmail.com')->first();

        Showroom::create([
            'user_id' => $dealer1->id,
            'name' => 'Dealer One Showroom',
            'description' => 'Luxury cars showroom',
            'location' => 'Gaza',
            'phone' => '0591111111',
            'is_active' => true,
        ]);

        Showroom::create([
            'user_id' => $dealer2->id,
            'name' => 'Dealer Two Showroom',
            'description' => 'Sport cars showroom',
            'location' => 'Ramallah',
            'phone' => '0592222222',
            'is_active' => true,
        ]);
    }
}
