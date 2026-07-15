<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Inquiry;

class InquiryPolicy
{
    public function view(User $user, Inquiry $inquiry): bool
    {
        return $inquiry->buyer_id === $user->id || $inquiry->dealer_id === $user->id;
    }

    public function reply(User $user, Inquiry $inquiry): bool
    {
        return $inquiry->status !== 'closed' && ($inquiry->buyer_id === $user->id || $inquiry->dealer_id === $user->id);
    }

    public function close(User $user, Inquiry $inquiry): bool
    {
        return $inquiry->buyer_id === $user->id || $inquiry->dealer_id === $user->id;
    }
}
