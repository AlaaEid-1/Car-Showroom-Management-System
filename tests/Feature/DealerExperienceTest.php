<?php

use App\Models\Car;
use App\Models\Showroom;
use App\Models\User;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('dealer can view dashboard with correct dealer-specific statistics', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    // Create 2 cars, 1 published, 1 draft
    $car1 = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Car 1',
        'brand' => 'Tesla',
        'model' => 'Model 3',
        'year' => 2022,
        'price' => 45000,
        'status' => 'published',
    ]);

    $car2 = Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Car 2',
        'brand' => 'Tesla',
        'model' => 'Model Y',
        'year' => 2022,
        'price' => 55000,
        'status' => 'draft',
    ]);

    // Create an inquiry
    $buyer = User::factory()->create(['role' => 'customer']);
    Inquiry::create([
        'car_id' => $car1->id,
        'buyer_id' => $buyer->id,
        'dealer_id' => $dealer->id,
        'subject' => 'Inquiry 1',
        'status' => 'open',
    ]);

    $response = $this->actingAs($dealer)->get('/dashboarddealer');
    $response->assertStatus(200);
    $response->assertSee('My Cars Fleet');
    $response->assertSee('Dealer Showroom');
});

test('dealer can create and update their showroom', function () {
    Storage::fake('public');
    $dealer = User::factory()->create(['role' => 'dealer']);

    // Get create showroom page
    $response = $this->actingAs($dealer)->get('/dashboarddealer/showroom');
    $response->assertStatus(200);
    $response->assertSee('Initialize Showroom');

    // Create showroom
    $logo = UploadedFile::fake()->create('logo.jpg', 100);
    $responseCreate = $this->actingAs($dealer)->post('/dashboarddealer/showroom', [
        'name' => 'My New Showroom',
        'description' => 'A luxury dealership',
        'location' => 'Ramallah',
        'phone' => '123456',
        'logo' => $logo,
    ]);

    $responseCreate->assertRedirect();
    $this->assertDatabaseHas('showrooms', [
        'user_id' => $dealer->id,
        'name' => 'My New Showroom',
        'location' => 'Ramallah',
    ]);

    $showroom = $dealer->showrooms()->first();
    $this->assertNotNull($showroom->logo);

    // Update showroom
    $responseUpdate = $this->actingAs($dealer)->patch('/dashboarddealer/showroom', [
        'name' => 'Updated Showroom Name',
        'description' => 'Updated desc',
        'location' => 'Gaza',
        'phone' => '654321',
    ]);

    $responseUpdate->assertRedirect();
    $this->assertDatabaseHas('showrooms', [
        'user_id' => $dealer->id,
        'name' => 'Updated Showroom Name',
        'location' => 'Gaza',
    ]);
});

test('dealer can complete car CRUD', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    // Create car
    $response = $this->actingAs($dealer)->post('/dashboarddealer/cars', [
        'title' => 'Porsche 911 GT3',
        'brand' => 'Porsche',
        'model' => '911 GT3',
        'year' => 2023,
        'price' => 180000,
        'description' => 'Mint condition',
        'status' => 'published',
    ]);

    $response->assertRedirect(route('dashboarddealer.cars.index'));
    $this->assertDatabaseHas('cars', [
        'user_id' => $dealer->id,
        'title' => 'Porsche 911 GT3',
    ]);

    $car = Car::where('title', 'Porsche 911 GT3')->first();

    // Edit car page
    $this->actingAs($dealer)->get("/dashboarddealer/cars/{$car->id}/edit")->assertStatus(200);

    // Update car
    $responseUpdate = $this->actingAs($dealer)->put("/dashboarddealer/cars/{$car->id}", [
        'title' => 'Porsche 911 GT3 RS',
        'brand' => 'Porsche',
        'model' => '911 GT3',
        'year' => 2023,
        'price' => 220000,
        'description' => 'Brand new track weapon',
        'status' => 'published',
    ]);

    $responseUpdate->assertRedirect(route('dashboarddealer.cars.index'));
    $this->assertEquals('Porsche 911 GT3 RS', $car->fresh()->title);

    // Delete car
    $responseDelete = $this->actingAs($dealer)->delete("/dashboarddealer/cars/{$car->id}");
    $this->assertSoftDeleted('cars', ['id' => $car->id]);

    // Restore car
    $responseRestore = $this->actingAs($dealer)->patch("/dashboarddealer/cars/{$car->id}/restore");
    $responseRestore->assertRedirect(route('dashboarddealer.cars.index'));
    $this->assertNull($car->fresh()->deleted_at);
});

test('dealer cannot manage or access other dealers showroom or cars', function () {
    $dealer1 = User::factory()->create(['role' => 'dealer']);
    $showroom1 = Showroom::create([
        'user_id' => $dealer1->id,
        'name' => 'Dealer 1 Showroom',
        'is_active' => true,
    ]);
    $car1 = Car::create([
        'showroom_id' => $showroom1->id,
        'user_id' => $dealer1->id,
        'title' => 'Dealer 1 Car',
        'brand' => 'Tesla',
        'model' => 'Model 3',
        'year' => 2022,
        'price' => 45000,
        'status' => 'published',
    ]);

    $dealer2 = User::factory()->create(['role' => 'dealer']);

    // Dealer 2 cannot edit Dealer 1 car
    $this->actingAs($dealer2)->get("/dashboarddealer/cars/{$car1->id}/edit")->assertStatus(403);
    $this->actingAs($dealer2)->delete("/dashboarddealer/cars/{$car1->id}")->assertStatus(403);
});

test('dealer can update personal profile information but not role or status', function () {
    $dealer = User::factory()->create([
        'name' => 'Dealer Name',
        'username' => 'dealername',
        'email' => 'dealer@example.com',
        'role' => 'dealer',
        'status' => 'active',
    ]);

    $response = $this->actingAs($dealer)->patch('/profile', [
        'name' => 'Updated Dealer Name',
        'username' => 'dealernameup',
        'email' => 'dealer.up@example.com',
        'timezone' => 'Asia/Gaza',
        'role' => 'admin', // Attempts to upgrade to admin
        'status' => 'suspended', // Attempts to change status
    ]);

    $response->assertSessionHasNoErrors();
    $dealer->refresh();

    // Verify allowed fields were updated
    $this->assertEquals('Updated Dealer Name', $dealer->name);
    $this->assertEquals('dealernameup', $dealer->username);
    $this->assertEquals('dealer.up@example.com', $dealer->email);

    // Verify role and status remained untouched
    $this->assertEquals('dealer', $dealer->role);
    $this->assertEquals('active', $dealer->status);
});

test('dealer can upload multiple images and delete/set main image', function () {
    Storage::fake('public');
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    $image1 = UploadedFile::fake()->create('image1.jpg', 100);
    $image2 = UploadedFile::fake()->create('image2.jpg', 100);

    $this->actingAs($dealer)->post('/dashboarddealer/cars', [
        'title' => 'Porsche 911',
        'brand' => 'Porsche',
        'model' => '911',
        'year' => 2023,
        'price' => 150000,
        'description' => 'A classic performance car.',
        'status' => 'published',
        'images' => [$image1, $image2],
    ]);

    $car = Car::where('title', 'Porsche 911')->first();
    $this->assertCount(2, $car->images);

    $img1 = $car->images[0];
    $img2 = $car->images[1];

    $this->assertTrue((bool)$img1->is_main);
    $this->assertFalse((bool)$img2->is_main);

    $this->actingAs($dealer)->post("/dashboarddealer/cars/{$car->id}/images/{$img2->id}/main");
    $this->assertFalse((bool)$img1->fresh()->is_main);
    $this->assertTrue((bool)$img2->fresh()->is_main);

    $this->actingAs($dealer)->delete("/dashboarddealer/cars/{$car->id}/images/{$img1->id}");
    $this->assertDatabaseMissing('car_images', ['id' => $img1->id]);
});

test('dealer can search and filter their fleet listings', function () {
    $dealer = User::factory()->create(['role' => 'dealer']);
    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Dealer Showroom',
        'is_active' => true,
    ]);

    Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Mercedes C200',
        'brand' => 'Mercedes',
        'model' => 'C200',
        'year' => 2021,
        'price' => 45000,
        'status' => 'published',
    ]);

    Car::create([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'BMW 320i',
        'brand' => 'BMW',
        'model' => '320i',
        'year' => 2022,
        'price' => 50000,
        'status' => 'draft',
    ]);

    $responseBrand = $this->actingAs($dealer)->get('/dashboarddealer/cars?search=Mercedes&status=published');
    $responseBrand->assertStatus(200);
    $responseBrand->assertSee('Mercedes C200');
    $responseBrand->assertDontSee('BMW 320i');

    $responseDraft = $this->actingAs($dealer)->get('/dashboarddealer/cars?status=draft');
    $responseDraft->assertStatus(200);
    $responseDraft->assertSee('BMW 320i');
    $responseDraft->assertDontSee('Mercedes C200');
});
