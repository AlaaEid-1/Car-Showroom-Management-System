<?php

use App\Models\Car;
use App\Models\Showroom;
use App\Models\User;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('customer can create an inquiry on a car listing', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Audi R8',
        'brand' => 'Audi',
        'model' => 'R8',
        'year' => 2022,
        'price' => 160000,
        'status' => 'published',
    ]);

    $customer = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($customer)->post("/cars/{$car->id}/inquiry", [
        'message' => 'Hello, is this car still available?',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    $this->assertDatabaseHas('inquiries', [
        'car_id' => $car->id,
        'buyer_id' => $customer->id,
        'dealer_id' => $dealer->id,
        'status' => 'open',
    ]);

    $inquiry = Inquiry::first();
    $this->assertDatabaseHas('inquiry_messages', [
        'inquiry_id' => $inquiry->id,
        'sender_id' => $customer->id,
        'message' => 'Hello, is this car still available?',
    ]);
});

test('dealer cannot create an inquiry on their own car', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    $car = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Audi R8',
        'brand' => 'Audi',
        'model' => 'R8',
        'year' => 2022,
        'price' => 160000,
        'status' => 'published',
    ]);

    $response = $this->actingAs($dealer)->post("/cars/{$car->id}/inquiry", [
        'message' => 'Hacked',
    ]);

    $response->assertSessionHas('error', 'You cannot send inquiry to your own car.');
    $this->assertDatabaseEmpty('inquiries');
});

test('dealer can view only inquiries related to his cars and reply', function () {
    $dealer1 = User::factory()->create(['role' => 'dealer']);
    $showroom1 = Showroom::create(['user_id' => $dealer1->id, 'name' => 'S1']);
    $car1 = Car::create([
        'showroom_id' => $showroom1->id, 'user_id' => $dealer1->id,
        'title' => 'Car 1', 'brand' => 'B1', 'model' => 'M1', 'year' => 2022, 'price' => 10000, 'status' => 'published'
    ]);

    $dealer2 = User::factory()->create(['role' => 'dealer']);
    $showroom2 = Showroom::create(['user_id' => $dealer2->id, 'name' => 'S2']);
    $car2 = Car::create([
        'showroom_id' => $showroom2->id, 'user_id' => $dealer2->id,
        'title' => 'Car 2', 'brand' => 'B2', 'model' => 'M2', 'year' => 2022, 'price' => 20000, 'status' => 'published'
    ]);

    $buyer = User::factory()->create(['role' => 'customer']);

    // Inquiry 1 for Dealer 1
    $inquiry1 = Inquiry::create([
        'car_id' => $car1->id, 'buyer_id' => $buyer->id, 'dealer_id' => $dealer1->id, 'subject' => 'Inquiry 1', 'status' => 'open'
    ]);

    // Inquiry 2 for Dealer 2
    $inquiry2 = Inquiry::create([
        'car_id' => $car2->id, 'buyer_id' => $buyer->id, 'dealer_id' => $dealer2->id, 'subject' => 'Inquiry 2', 'status' => 'open'
    ]);

    // Dealer 1 gets their list
    $response = $this->actingAs($dealer1)->get('/inquiries');
    $response->assertStatus(200);
    $response->assertSee('Inquiry 1');
    $response->assertDontSee('Inquiry 2');

    // Dealer 1 replies to Inquiry 1
    $responseReply = $this->actingAs($dealer1)->post("/inquiries/{$inquiry1->id}/message", [
        'message' => 'Yes, it is available.',
    ]);

    $responseReply->assertRedirect();
    $inquiry1->refresh();
    $this->assertEquals('answered', $inquiry1->status);
    $this->assertDatabaseHas('inquiry_messages', [
        'inquiry_id' => $inquiry1->id,
        'sender_id' => $dealer1->id,
        'message' => 'Yes, it is available.',
    ]);
});

test('customer can view sent inquiries and reply', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create(['user_id' => $dealer->id, 'name' => 'S1']);
    $car = Car::create([
        'showroom_id' => $showroom->id, 'user_id' => $dealer->id,
        'title' => 'Car 1', 'brand' => 'B1', 'model' => 'M1', 'year' => 2022, 'price' => 10000, 'status' => 'published'
    ]);

    $customer = User::factory()->create(['role' => 'customer']);
    $inquiry = Inquiry::create([
        'car_id' => $car->id, 'buyer_id' => $customer->id, 'dealer_id' => $dealer->id, 'subject' => 'My Inquiry', 'status' => 'open'
    ]);

    $response = $this->actingAs($customer)->get('/inquiries');
    $response->assertStatus(200);
    $response->assertSee('My Inquiry');

    $responseReply = $this->actingAs($customer)->post("/inquiries/{$inquiry->id}/message", [
        'message' => 'When can I see it?',
    ]);

    $responseReply->assertRedirect();
    $inquiry->refresh();
    $this->assertEquals('pending', $inquiry->status);
});

test('non-participant users cannot view or message a conversation', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create(['user_id' => $dealer->id, 'name' => 'S1']);
    $car = Car::create([
        'showroom_id' => $showroom->id, 'user_id' => $dealer->id,
        'title' => 'Car 1', 'brand' => 'B1', 'model' => 'M1', 'year' => 2022, 'price' => 10000, 'status' => 'published'
    ]);

    $buyer = User::factory()->create(['role' => 'customer']);
    $inquiry = Inquiry::create([
        'car_id' => $car->id, 'buyer_id' => $buyer->id, 'dealer_id' => $dealer->id, 'subject' => 'Chat', 'status' => 'open'
    ]);

    $stranger = User::factory()->create(['role' => 'customer']);

    // Stranger gets 403 on view
    $this->actingAs($stranger)->get("/inquiries/{$inquiry->id}")->assertStatus(403);

    // Stranger gets 403 on reply
    $this->actingAs($stranger)->post("/inquiries/{$inquiry->id}/message", ['message' => 'Haha'])->assertStatus(403);
});

test('participants can close inquiry and replies are disabled', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create(['user_id' => $dealer->id, 'name' => 'S1']);
    $car = Car::create([
        'showroom_id' => $showroom->id, 'user_id' => $dealer->id,
        'title' => 'Car 1', 'brand' => 'B1', 'model' => 'M1', 'year' => 2022, 'price' => 10000, 'status' => 'published'
    ]);

    $buyer = User::factory()->create(['role' => 'customer']);
    $inquiry = Inquiry::create([
        'car_id' => $car->id, 'buyer_id' => $buyer->id, 'dealer_id' => $dealer->id, 'subject' => 'Chat', 'status' => 'open'
    ]);

    // Close
    $response = $this->actingAs($buyer)->post("/inquiries/{$inquiry->id}/close");
    $response->assertRedirect();
    $inquiry->refresh();
    $this->assertEquals('closed', $inquiry->status);

    // Attempt reply should fail with 400
    $responseReply = $this->actingAs($buyer)->post("/inquiries/{$inquiry->id}/message", [
        'message' => 'Can I open it again?',
    ]);
    $responseReply->assertStatus(400);
});
