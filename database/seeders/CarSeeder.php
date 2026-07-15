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
        $showroom1 = Showroom::first();
        $showroom2 = Showroom::skip(1)->first();

        Car::create([
            'showroom_id' => $showroom1->id,
            'user_id' => $showroom1->user_id,
            'title' => 'Toyota Corolla',
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2022,
            'price' => 18000,
            'description' => 'Reliable and fuel efficient',
            'status' => 'published',
        ]);

        Car::create([
            'showroom_id' => $showroom1->id,
            'user_id' => $showroom1->user_id,
            'title' => 'Honda Civic',
            'brand' => 'Honda',
            'model' => 'Civic',
            'year' => 2023,
            'price' => 22000,
            'description' => 'Sporty and modern design',
            'status' => 'published',
        ]);

        Car::create([
            'showroom_id' => $showroom2->id,
            'user_id' => $showroom2->user_id,
            'title' => 'BMW M3',
            'brand' => 'BMW',
            'model' => 'M3',
            'year' => 2021,
            'price' => 55000,
            'description' => 'High performance sports car',
            'status' => 'published',
        ]);
    }
}
