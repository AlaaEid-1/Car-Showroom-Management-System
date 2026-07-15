<?php

use App\Models\Car;
use App\Models\Showroom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

uses(RefreshDatabase::class);

test('authenticated dealer can access description generator endpoint and receive valid response structure', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    
    $response = $this->actingAs($dealer)->postJson(route('dashboarddealer.ai.generate'), [
        'brand' => 'Audi',
        'model' => 'R8',
        'year' => 2022,
        'price' => 180000,
        'mileage' => '5,000 miles',
        'color' => 'Nardo Grey',
    ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'title',
        'description',
        'highlights',
    ]);

    // Verify response is not saved directly to the database
    $this->assertDatabaseMissing('cars', [
        'brand' => 'Audi',
        'model' => 'R8',
    ]);
});

test('non-dealers are unauthorized to call AI generator', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($customer)->postJson(route('dashboarddealer.ai.generate'), [
        'brand' => 'Audi',
        'model' => 'R8',
    ]);

    $response->assertStatus(403);
});

test('dealer can improve their own car listing but cannot improve other dealers cars', function () {
    $dealer1 = User::factory()->create(['role' => 'dealer']);
    $showroom1 = Showroom::create(['user_id' => $dealer1->id, 'name' => 'S1']);
    $car1 = Car::create([
        'showroom_id' => $showroom1->id,
        'user_id' => $dealer1->id,
        'title' => 'BMW M3',
        'brand' => 'BMW',
        'model' => 'M3',
        'year' => 2021,
        'price' => 70000,
        'description' => 'Original description',
        'status' => 'published',
    ]);

    $dealer2 = User::factory()->create(['role' => 'dealer']);

    // 1. Dealer 1 improves their own car -> success
    $response1 = $this->actingAs($dealer1)->postJson(route('dashboarddealer.ai.improve', $car1->id), [
        'engine' => '3.0L twin-turbo I6',
    ]);
    $response1->assertOk();
    $response1->assertJsonStructure([
        'title',
        'description',
        'highlights',
    ]);

    // Verify database record has NOT been updated with AI suggestions yet (requires dealer form submit)
    $this->assertEquals('Original description', $car1->fresh()->description);

    // 2. Dealer 2 tries to improve Dealer 1's car -> 403 forbidden
    $response2 = $this->actingAs($dealer2)->postJson(route('dashboarddealer.ai.improve', $car1->id), [
        'engine' => '3.0L twin-turbo I6',
    ]);
    $response2->assertStatus(403);
});
