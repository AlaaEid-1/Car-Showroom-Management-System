<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\TestDrive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestDriveController extends Controller
{
    /**
     * Display a listing of test drive requests received by the dealer.
     */
    public function index()
    {
        $user = Auth::user();

        // Get test drives for the dealer's cars
        $testDrives = TestDrive::with(['car.images', 'user'])
            ->whereHas('car', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('dashboarddealer.testdrives.index', compact('testDrives'));
    }

    /**
     * Update the status of a test drive request.
     */
    public function update(Request $request, TestDrive $testDrive)
    {
        \Illuminate\Support\Facades\Gate::authorize('manage', $testDrive);

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected,completed'],
        ]);

        $testDrive->update([
            'status' => $validated['status'],
        ]);

        \Illuminate\Support\Facades\Log::info('Dealer updated test drive request status', [
            'dealer_id' => Auth::id(),
            'test_drive_id' => $testDrive->id,
            'status' => $testDrive->status,
        ]);

        $customer = $testDrive->user;
        if ($customer) {
            $customer->notify(new \App\Notifications\TestDriveStatusUpdated($testDrive));
        }

        return back()->with('success', 'Test drive request status updated successfully.');
    }
}
