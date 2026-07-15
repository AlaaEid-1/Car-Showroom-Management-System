<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTestDriveRequest;
use App\Models\Car;
use App\Models\TestDrive;
use Illuminate\Support\Facades\Auth;

class TestDriveController extends Controller
{
    /**
     * Create a test drive booking request for a car listing.
     */
    public function store(StoreTestDriveRequest $request, Car $car)
    {
        abort_if($car->status !== 'published', 404, 'This vehicle is not available for test drives.');

        $exists = TestDrive::where('car_id', $car->id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'You already have an active test drive request for this vehicle.');
        }

        $testDrive = TestDrive::create([
            'car_id' => $car->id,
            'user_id' => Auth::id(),
            'scheduled_at' => $request->scheduled_at,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        $dealer = $car->user;
        if ($dealer) {
            $dealer->notify(new \App\Notifications\NewTestDriveRequest($testDrive));
        }

        return back()->with('success', 'Test drive request submitted successfully.');
    }
}
