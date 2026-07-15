<?php

use App\Models\Car;
use App\Models\Showroom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createDealerWithCar(string $username, array $carAttributes = [])
{
    $dealer = User::factory()->create([
        'username' => $username,
        'role' => 'dealer',
    ]);

    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => "{$username} Showroom",
        'location' => 'Ramallah',
        'phone' => '123456789',
        'is_active' => true,
    ]);

    $car = Car::create(array_merge([
        'showroom_id' => $showroom->id,
        'user_id' => $dealer->id,
        'title' => 'Test Car',
        'brand' => 'Test Brand',
        'model' => 'Test Model',
        'year' => 2022,
        'price' => 50000,
        'status' => 'published',
    ], $carAttributes));

    return [$dealer, $showroom, $car];
}

test('admin can view global cars list directory', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    list($dealer, $showroom, $car) = createDealerWithCar('dealer1', [
        'title' => 'Porsche 911 GT3',
        'brand' => 'Porsche',
    ]);

    $response = $this->actingAs($admin)->get('/admin/cars');
    $response->assertStatus(200);
    $response->assertSee('Porsche 911 GT3');
});

test('admin can search and filter global cars list', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    list($d1, $s1, $car1) = createDealerWithCar('dealer1', [
        'title' => 'Ferrari F8 Tributo',
        'brand' => 'Ferrari',
        'status' => 'published',
    ]);

    list($d2, $s2, $car2) = createDealerWithCar('dealer2', [
        'title' => 'Audi R8 Coupe',
        'brand' => 'Audi',
        'status' => 'draft',
    ]);

    $response = $this->actingAs($admin)->get('/admin/cars?search=Ferrari');
    $response->assertSee('Ferrari F8 Tributo');
    $response->assertDontSee('Audi R8 Coupe');

    $response2 = $this->actingAs($admin)->get('/admin/cars?status=draft');
    $response2->assertSee('Audi R8 Coupe');
    $response2->assertDontSee('Ferrari F8 Tributo');
});

test('admin can soft delete a car listing', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    list($dealer, $showroom, $car) = createDealerWithCar('dealer1', [
        'title' => 'Lamborghini Huracan',
    ]);

    $response = $this->actingAs($admin)->delete("/admin/cars/{$car->id}");

    $response->assertSessionHasNoErrors();
    $this->assertSoftDeleted('cars', ['id' => $car->id]);
});

test('non admin users cannot delete car listings', function () {
    $customer = User::factory()->create([
        'username' => 'customer_user',
        'role' => 'customer',
    ]);

    list($dealer, $showroom, $car) = createDealerWithCar('dealer1', [
        'title' => 'Lamborghini Huracan',
    ]);

    $response = $this->actingAs($customer)->delete("/admin/cars/{$car->id}");
    $response->assertStatus(403);
    $this->assertDatabaseHas('cars', ['id' => $car->id, 'deleted_at' => null]);
});
