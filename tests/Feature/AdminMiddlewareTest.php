<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the homepage loads successfully', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

test('guest users are redirected to login when accessing admin dashboard', function () {
    $response = $this->get('/admin/dashboard');
    $response->assertRedirect('/login');
});

test('non admin users receive 403 when accessing admin dashboard', function () {
    $user = User::factory()->create([
        'username' => 'testcustomer',
        'role' => 'customer',
    ]);

    $response = $this->actingAs($user)->get('/admin/dashboard');
    $response->assertStatus(403);
});

test('admin users can access admin dashboard successfully', function () {
    $admin = User::factory()->create([
        'username' => 'testadmin',
        'role' => 'admin',
    ]);

    $response = $this->actingAs($admin)->get('/admin/dashboard');
    $response->assertStatus(200);
});
