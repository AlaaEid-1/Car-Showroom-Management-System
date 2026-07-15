<?php

use App\Models\Car;
use App\Models\Showroom;
use App\Models\User;
use App\Models\Inquiry;
use App\Models\TestDrive;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

uses(RefreshDatabase::class);

test('dealer cannot manage another dealers car listing', function () {
    $dealer1 = User::factory()->create(['role' => 'dealer']);
    $dealer2 = User::factory()->create(['role' => 'dealer']);

    $showroom1 = Showroom::create(['user_id' => $dealer1->id, 'name' => 'S1']);
    $car1 = Car::create([
        'showroom_id' => $showroom1->id, 'user_id' => $dealer1->id,
        'title' => 'Mustang', 'brand' => 'Ford', 'model' => 'Mustang', 'year' => 2021, 'price' => 40000, 'status' => 'published'
    ]);

    // Dealer 2 tries to edit Dealer 1's car
    $response = $this->actingAs($dealer2)->get("/dashboarddealer/cars/{$car1->id}/edit");
    $response->assertStatus(403);
});

test('customer cannot manage showroom profile', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create(['user_id' => $dealer->id, 'name' => 'S1']);

    $customer = User::factory()->create(['role' => 'customer']);

    // Customer tries to post to showroom update
    $response = $this->actingAs($customer)->patch("/dashboarddealer/showroom", [
        'name' => 'Hack Showroom',
    ]);

    $response->assertStatus(403);
});

test('customer cannot view another customers inquiry chat', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create(['user_id' => $dealer->id, 'name' => 'S1']);
    $car = Car::create([
        'showroom_id' => $showroom->id, 'user_id' => $dealer->id,
        'title' => 'BMW', 'brand' => 'BMW', 'model' => 'M3', 'year' => 2021, 'price' => 70000, 'status' => 'published'
    ]);

    $customerA = User::factory()->create(['role' => 'customer']);
    $customerB = User::factory()->create(['role' => 'customer']);

    $inquiry = Inquiry::create([
        'car_id' => $car->id, 'buyer_id' => $customerA->id, 'dealer_id' => $dealer->id, 'subject' => 'Inquiry', 'status' => 'open'
    ]);

    // Customer B tries to view Customer A's inquiry details
    $response = $this->actingAs($customerB)->get("/inquiries/{$inquiry->id}");
    $response->assertStatus(403);
});

test('dealer cannot update status of a test drive for a car they do not own', function () {
    $dealer1 = User::factory()->create(['role' => 'dealer']);
    $dealer2 = User::factory()->create(['role' => 'dealer']);

    $showroom1 = Showroom::create(['user_id' => $dealer1->id, 'name' => 'S1']);
    $car = Car::create([
        'showroom_id' => $showroom1->id, 'user_id' => $dealer1->id,
        'title' => 'BMW', 'brand' => 'BMW', 'model' => 'M3', 'year' => 2021, 'price' => 70000, 'status' => 'published'
    ]);

    $customer = User::factory()->create(['role' => 'customer']);
    $testDrive = TestDrive::create([
        'car_id' => $car->id, 'user_id' => $customer->id, 'scheduled_at' => now()->addDays(2), 'status' => 'pending'
    ]);

    // Dealer 2 tries to approve test drive on Dealer 1's car
    $response = $this->actingAs($dealer2)->patch("/dashboarddealer/test-drives/{$testDrive->id}", [
        'status' => 'approved',
    ]);

    $response->assertStatus(403);
});

test('validation fails for CreateCarRequest when title is too short', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create(['user_id' => $dealer->id, 'name' => 'S1', 'is_active' => true]);

    $response = $this->actingAs($dealer)->post("/dashboarddealer/cars", [
        'title' => 'ab', // Min is 3
        'brand' => 'Ford',
        'model' => 'Mustang',
        'year' => 2021,
        'price' => 40000,
        'status' => 'published',
    ]);

    $response->assertSessionHasErrors(['title']);
});

test('validation ignores original profile username when updating current user profile', function () {
    $user = User::factory()->create([
        'role' => 'customer',
        'username' => 'originaluser',
        'email' => 'original@test.com',
    ]);

    $response = $this->actingAs($user)->patch("/profile", [
        'name' => 'New Name',
        'username' => 'originaluser', // Same username should be accepted (ignored)
        'email' => 'new@test.com',
        'timezone' => 'UTC',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertEquals('New Name', $user->fresh()->name);
});

test('critical status transitions are logged', function () {
    Log::shouldReceive('info')->once()->with('Dealer listed a new vehicle', \Mockery::any());
    Log::shouldReceive('info')->once()->with('Dealer changed vehicle status', \Mockery::any());

    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create(['user_id' => $dealer->id, 'name' => 'S1', 'is_active' => true]);

    // 1. Create listing -> triggers 'Dealer listed a new vehicle' log
    $response = $this->actingAs($dealer)->post("/dashboarddealer/cars", [
        'title' => 'GT Mustang',
        'brand' => 'Ford',
        'model' => 'Mustang',
        'year' => 2021,
        'price' => 45000,
        'status' => 'draft',
    ]);
    $response->assertRedirect();
    $car = Car::first();

    // 2. Update status -> triggers 'Dealer changed vehicle status' log
    $responseUpdate = $this->actingAs($dealer)->patch("/dashboarddealer/cars/{$car->id}", [
        'title' => 'GT Mustang',
        'brand' => 'Ford',
        'model' => 'Mustang',
        'year' => 2021,
        'price' => 45000,
        'status' => 'published',
    ]);
    $responseUpdate->assertRedirect();
});
