<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $cars = Car::all();

        CarImage::create([
            'car_id' => $cars[0]->id,
            'path' => 'cars/toyota.jpg',
            'is_main' => true,
        ]);

        CarImage::create([
            'car_id' => $cars[1]->id,
            'path' => 'cars/honda.jpg',
            'is_main' => true,
        ]);

        CarImage::create([
            'car_id' => $cars[2]->id,
            'path' => 'cars/bmw.jpg',
            'is_main' => true,
        ]);
    }
}
