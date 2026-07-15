<?php

use App\Models\Car;
use App\Models\Showroom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view showrooms list directory', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    $dealer = User::factory()->create([
        'username' => 'dealer_user',
        'role' => 'dealer',
    ]);

    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Lux Dealership Ramallah',
        'location' => 'Ramallah',
        'phone' => '123456789',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->get('/admin/showrooms');
    $response->assertStatus(200);
    $response->assertSee('Lux Dealership Ramallah');
});

test('admin can search and filter showrooms', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    $dealer1 = User::factory()->create([
        'username' => 'dealer_one',
        'role' => 'dealer',
    ]);

    $showroom1 = Showroom::create([
        'user_id' => $dealer1->id,
        'name' => 'Royal Motors',
        'location' => 'Gaza',
        'phone' => '987654321',
        'is_active' => true,
    ]);

    $dealer2 = User::factory()->create([
        'username' => 'dealer_two',
        'role' => 'dealer',
    ]);

    $showroom2 = Showroom::create([
        'user_id' => $dealer2->id,
        'name' => 'Prestige Cars',
        'location' => 'Ramallah',
        'phone' => '555555555',
        'is_active' => false,
    ]);

    // Search query matches name
    $response1 = $this->actingAs($admin)->get('/admin/showrooms?search=Royal');
    $response1->assertSee('Royal Motors');
    $response1->assertDontSee('Prestige Cars');

    // Filter by active status
    $response2 = $this->actingAs($admin)->get('/admin/showrooms?status=active');
    $response2->assertSee('Royal Motors');
    $response2->assertDontSee('Prestige Cars');

    // Filter by inactive status
    $response3 = $this->actingAs($admin)->get('/admin/showrooms?status=inactive');
    $response3->assertSee('Prestige Cars');
    $response3->assertDontSee('Royal Motors');
});

test('admin can toggle showroom active status', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    $dealer = User::factory()->create([
        'username' => 'dealer_user',
        'role' => 'dealer',
    ]);

    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Toggle Showroom',
        'location' => 'Ramallah',
        'phone' => '123456789',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->patch("/admin/showrooms/{$showroom->id}/status", [
        'is_active' => 0,
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertFalse((bool) $showroom->fresh()->is_active);

    $response2 = $this->actingAs($admin)->patch("/admin/showrooms/{$showroom->id}/status", [
        'is_active' => 1,
    ]);

    $response2->assertSessionHasNoErrors();
    $this->assertTrue((bool) $showroom->fresh()->is_active);
});

test('non-admin users cannot access showrooms list or change status', function () {
    $customer = User::factory()->create([
        'username' => 'customer_user',
        'role' => 'customer',
    ]);

    $dealer = User::factory()->create([
        'username' => 'dealer_user',
        'role' => 'dealer',
    ]);

    $showroom = Showroom::create([
        'user_id' => $dealer->id,
        'name' => 'Secure Showroom',
        'location' => 'Ramallah',
        'phone' => '123456789',
        'is_active' => true,
    ]);

    $response = $this->actingAs($customer)->get('/admin/showrooms');
    $response->assertStatus(403);

    $response2 = $this->actingAs($customer)->patch("/admin/showrooms/{$showroom->id}/status", [
        'is_active' => 0,
    ]);
    $response2->assertStatus(403);
});
