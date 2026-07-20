<?php

use App\Models\Car;
use App\Models\User;
use App\Models\Showroom;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


test('customer can add a car to favorites', function () {

    // Create customer
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    // Create dealer
    $dealer = User::factory()->create([
        'role' => 'dealer',
    ]);

    // Create showroom
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Luxury Motors',
        'is_active' => true,
    ]);

    // Create car
    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'BMW M4',
        'brand' => 'BMW',
        'model' => 'M4',
        'year' => 2024,
        'price' => 90000,
        'status' => 'published',
    ]);


    // Customer adds car to favorites
    $response = $this->actingAs($customer)
        ->post(route('cars.favorite.store', $car->id));


    // Check redirect
    $response->assertRedirect();


    // Verify favorite exists
    $this->assertDatabaseHas('favorites', [
        'user_id' => $customer->id,
        'car_id' => $car->id,
    ]);

});



test('guest cannot add car to favorites', function () {

    // Create dealer
    $dealer = User::factory()->create([
        'role' => 'dealer',
    ]);


    // Create showroom
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Luxury Motors',
        'is_active' => true,
    ]);


    // Create car
    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Mercedes C200',
        'brand' => 'Mercedes',
        'model' => 'C200',
        'year' => 2023,
        'price' => 60000,
        'status' => 'published',
    ]);


    // Guest tries to add favorite
    $response = $this->post(
        route('cars.favorite.store', $car->id)
    );


    // Should redirect to login
    $response->assertRedirect('/login');

});
