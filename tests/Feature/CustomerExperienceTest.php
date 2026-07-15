<?php

use App\Models\Car;
use App\Models\Showroom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('customer can browse published cars but not draft cars', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    $publishedCar = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Published Tesla Model S',
        'brand' => 'Tesla',
        'model' => 'Model S',
        'year' => 2023,
        'price' => 89000,
        'status' => 'published',
    ]);

    $draftCar = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Draft Porsche Taycan',
        'brand' => 'Porsche',
        'model' => 'Taycan',
        'year' => 2023,
        'price' => 120000,
        'status' => 'draft',
    ]);

    $response = $this->get('/cars/search');
    $response->assertStatus(200);
    $response->assertSee('Published Tesla Model S');
    $response->assertDontSee('Draft Porsche Taycan');
});

test('viewing car details restricts draft cars to owner only', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    $publishedCar = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Published Audi R8',
        'brand' => 'Audi',
        'model' => 'R8',
        'year' => 2022,
        'price' => 160000,
        'status' => 'published',
    ]);

    $draftCar = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Draft Audi e-tron',
        'brand' => 'Audi',
        'model' => 'e-tron',
        'year' => 2022,
        'price' => 90000,
        'status' => 'draft',
    ]);

    // Public visitor can view published car
    $this->get("/cars/{$publishedCar->id}")->assertStatus(200);

    // Public visitor cannot view draft car
    $this->get("/cars/{$draftCar->id}")->assertStatus(404);

    // Other user cannot view draft car
    $otherUser = User::factory()->create(['role' => 'customer']);
    $this->actingAs($otherUser)->get("/cars/{$draftCar->id}")->assertStatus(404);

    // Owner (dealer) CAN view draft car
    $this->actingAs($dealer)->get("/cars/{$draftCar->id}")->assertStatus(200);
});

test('customer can view dashboard and edit their profile', function () {
    $customer = User::factory()->create([
        'name' => 'John Doe',
        'username' => 'johndoe',
        'email' => 'john@example.com',
        'role' => 'customer',
    ]);

    $response = $this->actingAs($customer)->get('/inquiries');
    $response->assertStatus(200);
    $response->assertSee('Profile Settings');

    $responseUpdate = $this->actingAs($customer)->patch('/profile', [
        'name' => 'John Updated',
        'username' => 'johnup',
        'email' => 'john.up@example.com',
        'timezone' => 'Asia/Gaza',
        'country_code' => 'PS',
    ]);

    $responseUpdate->assertSessionHasNoErrors();
    $responseUpdate->assertRedirect();
    
    $customer->refresh();
    $this->assertEquals('John Updated', $customer->name);
    $this->assertEquals('johnup', $customer->username);
    $this->assertEquals('john.up@example.com', $customer->email);
    $this->assertEquals('Asia/Gaza', $customer->timezone);
    $this->assertEquals('PS', $customer->country_code);
});
