<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view users directory list', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    $otherUser = User::factory()->create([
        'username' => 'other_user',
        'role' => 'customer',
    ]);

    $response = $this->actingAs($admin)->get('/admin/users');
    $response->assertStatus(200);
    $response->assertSee($otherUser->name);
});

test('admin can update user status', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    $user = User::factory()->create([
        'username' => 'customer_user',
        'status' => 'active',
    ]);

    $response = $this->actingAs($admin)->patch("/admin/users/{$user->id}/status", [
        'status' => 'suspended',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertEquals('suspended', $user->fresh()->status);
});

test('admin can delete a user', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    $user = User::factory()->create([
        'username' => 'customer_user',
    ]);

    $response = $this->actingAs($admin)->delete("/admin/users/{$user->id}");

    $response->assertSessionHasNoErrors();
    $this->assertNull(User::find($user->id));
});

test('admin cannot delete themselves', function () {
    $admin = User::factory()->create([
        'username' => 'admin_user',
        'role' => 'admin',
    ]);

    $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");

    $response->assertSessionHasErrors();
    $this->assertNotNull(User::find($admin->id));
});

test('non admin users cannot delete users', function () {
    $customer = User::factory()->create([
        'username' => 'customer_user',
        'role' => 'customer',
    ]);

    $user = User::factory()->create([
        'username' => 'other_user',
    ]);

    $response = $this->actingAs($customer)->delete("/admin/users/{$user->id}");
    $response->assertStatus(403);
    $this->assertNotNull(User::find($user->id));
});
