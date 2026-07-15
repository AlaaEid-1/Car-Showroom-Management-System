<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Car;

class CarPolicy
{
    public function view(?User $user, Car $car): bool
    {
        if ($car->status === 'published') {
            return true;
        }

        return $user && ($user->role === 'admin' || $car->user_id === $user->id);
    }

    public function manage(User $user, Car $car): bool
    {
        return $user->role === 'dealer' && $car->user_id === $user->id;
    }
}
