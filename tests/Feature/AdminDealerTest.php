<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view dealers list directory', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    $dealer = User::factory()->create([
        'username' => 'dealer_user',
        'role' => 'dealer',
        'status' => 'active',
    ]);

    $response = $this->actingAs($admin)->get('/admin/dealers');
    $response->assertStatus(200);
    $response->assertSee($dealer->name);
});

test('admin can view pending dealer requests list', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    $pendingDealer = User::factory()->create([
        'username' => 'pending_dealer',
        'role' => 'dealer',
        'status' => 'inactive',
    ]);

    $response = $this->actingAs($admin)->get('/admin/dealers/requests');
    $response->assertStatus(200);
    $response->assertSee($pendingDealer->name);
});

test('admin can approve pending dealer request', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    $pendingDealer = User::factory()->create([
        'username' => 'pending_dealer',
        'role' => 'dealer',
        'status' => 'inactive',
    ]);

    $response = $this->actingAs($admin)->patch("/admin/dealers/{$pendingDealer->id}/approve");

    $response->assertRedirect(route('admin.dealers.requests'));
    $this->assertEquals('active', $pendingDealer->fresh()->status);
    $this->assertEquals('dealer', $pendingDealer->fresh()->role);
});

test('admin can reject pending dealer request', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    $pendingDealer = User::factory()->create([
        'username' => 'pending_dealer',
        'role' => 'dealer',
        'status' => 'inactive',
    ]);

    $response = $this->actingAs($admin)->patch("/admin/dealers/{$pendingDealer->id}/reject");

    $response->assertRedirect(route('admin.dealers.requests'));
    $this->assertEquals('active', $pendingDealer->fresh()->status);
    $this->assertEquals('customer', $pendingDealer->fresh()->role);
});

test('admin can suspend active dealer status', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    $dealer = User::factory()->create([
        'username' => 'dealer_user',
        'role' => 'dealer',
        'status' => 'active',
    ]);

    $response = $this->actingAs($admin)->patch("/admin/dealers/{$dealer->id}/status", [
        'status' => 'suspended',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertEquals('suspended', $dealer->fresh()->status);
});

test('non admin users cannot approve dealer requests', function () {
    $customer = User::factory()->create([
        'username' => 'customer_user',
        'role' => 'customer',
    ]);

    $pendingDealer = User::factory()->create([
        'username' => 'pending_dealer',
        'role' => 'dealer',
        'status' => 'inactive',
    ]);

    $response = $this->actingAs($customer)->patch("/admin/dealers/{$pendingDealer->id}/approve");
    $response->assertStatus(403);
    $this->assertEquals('inactive', $pendingDealer->fresh()->status);
});
