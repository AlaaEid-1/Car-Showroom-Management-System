<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Showroom;

class ShowroomPolicy
{
    public function view(User $user, Showroom $showroom): bool
    {
        return $user->role === 'admin' || ($user->role === 'dealer' && $showroom->user_id === $user->id);
    }

    public function manage(User $user, Showroom $showroom): bool
    {
        return $user->role === 'dealer' && $showroom->user_id === $user->id;
    }
}
