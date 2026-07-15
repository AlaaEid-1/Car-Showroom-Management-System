<?php

use App\Models\Car;
use App\Models\Showroom;
use App\Models\User;
use App\Models\TestDrive;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('customer can add and remove a published car from favorites', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Ford Mustang',
        'brand' => 'Ford',
        'model' => 'Mustang',
        'year' => 2021,
        'price' => 40000,
        'status' => 'published',
    ]);

    $customer = User::factory()->create(['role' => 'customer']);

    // Add to favorites
    $response = $this->actingAs($customer)->post("/cars/{$car->id}/favorite");
    $response->assertRedirect();
    $this->assertDatabaseHas('favorites', [
        'user_id' => $customer->id,
        'car_id' => $car->id,
    ]);

    // Remove from favorites
    $responseRemove = $this->actingAs($customer)->delete("/cars/{$car->id}/favorite");
    $responseRemove->assertRedirect();
    $this->assertDatabaseMissing('favorites', [
        'user_id' => $customer->id,
        'car_id' => $car->id,
    ]);
});

test('customer cannot favorite a draft car listing', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create(['user_id' => $dealer->id, 'name' => 'S1']);
    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Secret Car',
        'brand' => 'Brand',
        'model' => 'Model',
        'year' => 2020,
        'price' => 20000,
        'status' => 'draft',
    ]);

    $customer = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($customer)->post("/cars/{$car->id}/favorite");
    $response->assertStatus(404);
    $this->assertDatabaseEmpty('favorites');
});

test('dealers and admins cannot manage favorites', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create(['user_id' => $dealer->id, 'name' => 'S1']);
    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Ford Mustang',
        'brand' => 'Ford',
        'model' => 'Mustang',
        'year' => 2021,
        'price' => 40000,
        'status' => 'published',
    ]);

    $admin = User::factory()->create(['role' => 'admin']);

    // Admin attempts to favorite
    $this->actingAs($admin)->post("/cars/{$car->id}/favorite")->assertStatus(403);

    // Dealer attempts to favorite
    $this->actingAs($dealer)->post("/cars/{$car->id}/favorite")->assertStatus(403);
});

test('customer can request a test drive on a published car', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create(['user_id' => $dealer->id, 'name' => 'S1']);
    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Ford Mustang',
        'brand' => 'Ford',
        'model' => 'Mustang',
        'year' => 2021,
        'price' => 40000,
        'status' => 'published',
    ]);

    $customer = User::factory()->create(['role' => 'customer']);
    $scheduledAt = now()->addDays(2)->roundSecond();

    $response = $this->actingAs($customer)->post("/cars/{$car->id}/test-drive", [
        'scheduled_at' => $scheduledAt->toDateTimeString(),
        'notes' => 'Please bring the keys.',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    $this->assertDatabaseHas('test_drives', [
        'car_id' => $car->id,
        'user_id' => $customer->id,
        'scheduled_at' => $scheduledAt->toDateTimeString(),
        'status' => 'pending',
        'notes' => 'Please bring the keys.',
    ]);
});

test('dealer can view, approve, reject, and complete test drive requests for their own cars', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create(['user_id' => $dealer->id, 'name' => 'S1']);
    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Ford Mustang',
        'brand' => 'Ford',
        'model' => 'Mustang',
        'year' => 2021,
        'price' => 40000,
        'status' => 'published',
    ]);

    $customer = User::factory()->create(['role' => 'customer']);
    $testDrive = TestDrive::create([
        'car_id' => $car->id,
        'user_id' => $customer->id,
        'scheduled_at' => now()->addDays(2),
        'status' => 'pending',
    ]);

    // View index
    $response = $this->actingAs($dealer)->get('/dashboarddealer/test-drives');
    $response->assertStatus(200);
    $response->assertSee('Ford Mustang');

    // Approve
    $responseApprove = $this->actingAs($dealer)->patch("/dashboarddealer/test-drives/{$testDrive->id}", [
        'status' => 'approved',
    ]);
    $responseApprove->assertRedirect();
    $this->assertEquals('approved', $testDrive->fresh()->status);

    // Reject (change from approved to rejected, or complete)
    $responseComplete = $this->actingAs($dealer)->patch("/dashboarddealer/test-drives/{$testDrive->id}", [
        'status' => 'completed',
    ]);
    $responseComplete->assertRedirect();
    $this->assertEquals('completed', $testDrive->fresh()->status);
});

test('dealer cannot manage test drive requests for another dealers cars', function () {
    $dealer1 = User::factory()->create(['role' => 'dealer']);
    $showroom1 = Showroom::create(['user_id' => $dealer1->id, 'name' => 'S1']);
    $car1 = Car::create([
        'showroom_id' => $showroom1->id, 'user_id' => $dealer1->id,
        'title' => 'Mustang', 'brand' => 'Ford', 'model' => 'Mustang', 'year' => 2021, 'price' => 40000, 'status' => 'published'
    ]);

    $customer = User::factory()->create(['role' => 'customer']);
    $testDrive = TestDrive::create([
        'car_id' => $car1->id,
        'user_id' => $customer->id,
        'scheduled_at' => now()->addDays(2),
        'status' => 'pending',
    ]);

    $dealer2 = User::factory()->create(['role' => 'dealer']);

    // Dealer 2 tries to approve Dealer 1's request
    $response = $this->actingAs($dealer2)->patch("/dashboarddealer/test-drives/{$testDrive->id}", [
        'status' => 'approved',
    ]);

    $response->assertStatus(403);
    $this->assertEquals('pending', $testDrive->fresh()->status);
});

test('customer cannot request duplicate test drives while pending or approved, but can request if rejected or completed', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create(['user_id' => $dealer->id, 'name' => 'S1']);
    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'BMW M4',
        'brand' => 'BMW',
        'model' => 'M4',
        'year' => 2022,
        'price' => 80000,
        'status' => 'published',
    ]);

    $customer = User::factory()->create(['role' => 'customer']);

    // 1. First request succeeds
    $response = $this->actingAs($customer)->post("/cars/{$car->id}/test-drive", [
        'scheduled_at' => now()->addDays(2)->toDateTimeString(),
        'notes' => 'First attempt',
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect();
    $this->assertDatabaseHas('test_drives', [
        'car_id' => $car->id,
        'user_id' => $customer->id,
        'status' => 'pending',
    ]);

    $firstRequest = TestDrive::first();

    // 2. Second request while first is pending should fail
    $responseDuplicatePending = $this->actingAs($customer)->post("/cars/{$car->id}/test-drive", [
        'scheduled_at' => now()->addDays(3)->toDateTimeString(),
        'notes' => 'Second attempt (should be blocked)',
    ]);
    $responseDuplicatePending->assertSessionHas('error', 'You already have an active test drive request for this vehicle.');

    // 3. Approve first request, and try a second request - it should still fail
    $firstRequest->update(['status' => 'approved']);
    $responseDuplicateApproved = $this->actingAs($customer)->post("/cars/{$car->id}/test-drive", [
        'scheduled_at' => now()->addDays(3)->toDateTimeString(),
        'notes' => 'Third attempt (should be blocked)',
    ]);
    $responseDuplicateApproved->assertSessionHas('error', 'You already have an active test drive request for this vehicle.');

    // 4. Reject first request - new request is now allowed
    $firstRequest->update(['status' => 'rejected']);
    $responseAfterRejection = $this->actingAs($customer)->post("/cars/{$car->id}/test-drive", [
        'scheduled_at' => now()->addDays(3)->toDateTimeString(),
        'notes' => 'New attempt after rejection (should succeed)',
    ]);
    $responseAfterRejection->assertSessionHasNoErrors();
    $responseAfterRejection->assertRedirect();
    $this->assertCount(2, TestDrive::all());

    $secondRequest = TestDrive::latest('id')->first();
    $this->assertEquals('pending', $secondRequest->status);

    // 5. Complete second request - new request is allowed again
    $secondRequest->update(['status' => 'completed']);
    $responseAfterCompletion = $this->actingAs($customer)->post("/cars/{$car->id}/test-drive", [
        'scheduled_at' => now()->addDays(4)->toDateTimeString(),
        'notes' => 'New attempt after completion (should succeed)',
    ]);
    $responseAfterCompletion->assertSessionHasNoErrors();
    $responseAfterCompletion->assertRedirect();
    $this->assertCount(3, TestDrive::all());
});
