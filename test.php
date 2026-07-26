<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = App\Models\User::where('email', 'admin@example.com')->first();
echo "Old Hash: " . $u->password . "\n";
App\Models\User::updateOrCreate(
    ['email' => 'admin@example.com'],
    ['password' => Illuminate\Support\Facades\Hash::make('new_password_123')]
);
$u->refresh();
echo "New Hash: " . $u->password . "\n";
echo "Verify new_password_123: " . (password_verify('new_password_123', $u->password) ? 'Yes' : 'No') . "\n";
