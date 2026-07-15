<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TestDrive;

class TestDrivePolicy
{
    public function view(User $user, TestDrive $testDrive): bool
    {
        return $testDrive->user_id === $user->id || $testDrive->car->user_id === $user->id;
    }

    public function manage(User $user, TestDrive $testDrive): bool
    {
        return $user->role === 'dealer' && $testDrive->car->user_id === $user->id;
    }
}
