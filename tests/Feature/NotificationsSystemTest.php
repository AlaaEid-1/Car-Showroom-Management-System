<?php

use App\Models\Car;
use App\Models\Showroom;
use App\Models\User;
use App\Models\Inquiry;
use App\Models\TestDrive;
use App\Notifications\NewInquiry;
use App\Notifications\NewInquiryReply;
use App\Notifications\NewTestDriveRequest;
use App\Notifications\TestDriveStatusUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('dealer receives inquiry notification when customer submits an inquiry', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'BMW M3',
        'brand' => 'BMW',
        'model' => 'M3',
        'year' => 2021,
        'price' => 70000,
        'status' => 'published',
    ]);

    $customer = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($customer)->post("/cars/{$car->id}/inquiry", [
        'message' => 'Interested in this BMW',
    ]);

    $response->assertRedirect();
    
    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $dealer->id,
        'notifiable_type' => User::class,
        'type' => NewInquiry::class,
    ]);
});

test('customer receives reply notification when dealer responds', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'BMW M3',
        'brand' => 'BMW',
        'model' => 'M3',
        'year' => 2021,
        'price' => 70000,
        'status' => 'published',
    ]);

    $customer = User::factory()->create(['role' => 'customer']);

    $inquiry = Inquiry::create([
        'car_id' => $car->id,
        'buyer_id' => $customer->id,
        'dealer_id' => $dealer->id,
        'subject' => 'Car Inquiry',
        'status' => 'open',
    ]);

    // Dealer replies
    $response = $this->actingAs($dealer)->post("/inquiries/{$inquiry->id}/message", [
        'message' => 'Yes, it is in stock.',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $customer->id,
        'notifiable_type' => User::class,
        'type' => NewInquiryReply::class,
    ]);
});

test('dealer receives test drive request notification when customer schedules a drive', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'BMW M3',
        'brand' => 'BMW',
        'model' => 'M3',
        'year' => 2021,
        'price' => 70000,
        'status' => 'published',
    ]);

    $customer = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($customer)->post("/cars/{$car->id}/test-drive", [
        'scheduled_at' => now()->addDays(2)->toDateTimeString(),
        'notes' => 'Looking forward to it.',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $dealer->id,
        'notifiable_type' => User::class,
        'type' => NewTestDriveRequest::class,
    ]);
});

test('customer receives test drive status notification when dealer updates booking status', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'BMW M3',
        'brand' => 'BMW',
        'model' => 'M3',
        'year' => 2021,
        'price' => 70000,
        'status' => 'published',
    ]);

    $customer = User::factory()->create(['role' => 'customer']);
    $testDrive = TestDrive::create([
        'car_id' => $car->id,
        'user_id' => $customer->id,
        'scheduled_at' => now()->addDays(2),
        'status' => 'pending',
    ]);

    // Dealer approves
    $response = $this->actingAs($dealer)->patch("/dashboarddealer/test-drives/{$testDrive->id}", [
        'status' => 'approved',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $customer->id,
        'notifiable_type' => User::class,
        'type' => TestDriveStatusUpdated::class,
    ]);
});

test('user can mark notification as read and mark all as read', function () {
    $user = User::factory()->create(['role' => 'customer']);

    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'BMW M3',
        'brand' => 'BMW',
        'model' => 'M3',
        'year' => 2021,
        'price' => 70000,
        'status' => 'published',
    ]);

    $inquiry = Inquiry::create([
        'car_id' => $car->id,
        'buyer_id' => $user->id,
        'dealer_id' => $dealer->id,
        'subject' => 'Car Inquiry',
        'status' => 'open',
    ]);

    $user->notify(new NewInquiry($inquiry));

    $this->assertEquals(1, \DB::table('notifications')->whereNull('read_at')->count());

    $notiId = $user->unreadNotifications->first()->id;

    // Read specific
    $response = $this->actingAs($user)->patch("/notifications/{$notiId}/read");
    $response->assertRedirect();
    $this->assertEquals(0, \DB::table('notifications')->whereNull('read_at')->count());

    // Generate another one and mark all as read
    $user->notify(new NewInquiry($inquiry));
    $this->assertEquals(1, \DB::table('notifications')->whereNull('read_at')->count());

    $responseAll = $this->actingAs($user)->post("/notifications/read-all");
    $responseAll->assertRedirect();
    $this->assertEquals(0, \DB::table('notifications')->whereNull('read_at')->count());
});

test('user cannot mark another users notification as read', function () {
    $userA = User::factory()->create(['role' => 'customer']);
    $userB = User::factory()->create(['role' => 'customer']);

    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'BMW M3',
        'brand' => 'BMW',
        'model' => 'M3',
        'year' => 2021,
        'price' => 70000,
        'status' => 'published',
    ]);

    $inquiry = Inquiry::create([
        'car_id' => $car->id,
        'buyer_id' => $userA->id,
        'dealer_id' => $dealer->id,
        'subject' => 'Car Inquiry',
        'status' => 'open',
    ]);

    $userB->notify(new NewInquiry($inquiry));
    $notiId = $userB->unreadNotifications->first()->id;

    // User A tries to read User B's notification
    $response = $this->actingAs($userA)->patch("/notifications/{$notiId}/read");
    $response->assertStatus(404);

    $this->assertEquals(1, \DB::table('notifications')->whereNull('read_at')->count());
});
