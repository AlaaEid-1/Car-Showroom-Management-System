<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Showroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Safely fetch showrooms belonging specifically to our predefined Dealers
        $showroom1 = Showroom::whereHas('user', function ($query) {
            $query->where('email', 'MennaEid@gmail.com');
        })->first();

        $showroom2 = Showroom::whereHas('user', function ($query) {
            $query->where('email', 'mohammed@gmail.com');
        })->first();

        if (!$showroom1 || !$showroom2) {
            return;
        }

        Car::firstOrCreate(
            ['title' => 'Toyota Corolla', 'showroom_id' => $showroom1->id],
            [
                'user_id' => $showroom1->user_id,
                'brand' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2022,
                'price' => 18000,
                'description' => 'Reliable and fuel efficient',
                'status' => 'published',
            ]
        );

        Car::firstOrCreate(
            ['title' => 'Honda Civic', 'showroom_id' => $showroom1->id],
            [
                'user_id' => $showroom1->user_id,
                'brand' => 'Honda',
                'model' => 'Civic',
                'year' => 2023,
                'price' => 22000,
                'description' => 'Sporty and modern design',
                'status' => 'published',
            ]
        );

        Car::firstOrCreate(
            ['title' => 'BMW M3', 'showroom_id' => $showroom2->id],
            [
                'user_id' => $showroom2->user_id,
                'brand' => 'BMW',
                'model' => 'M3',
                'year' => 2021,
                'price' => 55000,
                'description' => 'High performance sports car',
                'status' => 'published',
            ]
        );
    }
}
