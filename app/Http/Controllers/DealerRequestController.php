<?php

namespace App\Http\Controllers;

use App\Enums\DealerRequestStatus;
use App\Notifications\NewDealerRequestNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DealerRequestController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        // Prevent duplicate pending requests
        $existingRequest = $user->dealerRequests()
            ->where('status', DealerRequestStatus::PENDING)
            ->first();

        if ($existingRequest) {
            return back()->withErrors(['dealer_request' => 'You already have a pending dealer request.']);
        }

        if ($user->role === 'dealer') {
            return back()->withErrors(['dealer_request' => 'You are already a dealer.']);
        }

        $dealerRequest = $user->dealerRequests()->create([
            'status' => DealerRequestStatus::PENDING,
        ]);

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewDealerRequestNotification($dealerRequest));
        }

        return back()->with('success', 'Your request to become a dealer has been submitted successfully and is pending admin approval.');
    }
}
