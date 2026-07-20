<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Inquiry;
use App\Models\Showroom;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the Admin Statistics Overview Dashboard
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalDealers = User::where('role', 'dealer')->count();
        $totalCars = Car::count();
        $totalShowrooms = Showroom::count();
        $totalInquiries = Inquiry::count();

        $recentCars = Car::with(['showroom', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $recentInquiries = Inquiry::with(['car', 'buyer', 'dealer'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalDealers',
            'totalCars',
            'totalShowrooms',
            'totalInquiries',
            'recentCars',
            'recentInquiries'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Exclude current admin to prevent accidental self lockout/deletion
        $users = $query->where('id', '!=', auth()->id())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $user->update(['status' => $validated['status']]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors('You cannot delete your own admin account.');
        }

        $user->delete();

        return back()->with('success', 'User account permanently deleted.');
    }

    public function dealers(Request $request)
    {
        $query = User::where('role', 'dealer');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $dealers = $query->withCount('cars')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.dealers.index', compact('dealers'));
    }

    public function dealerRequests()
    {
        $requests = \App\Models\DealerRequest::with('user')
            ->where('status', \App\Enums\DealerRequestStatus::PENDING)
            ->latest()
            ->get();

        return view('admin.dealers.requests', compact('requests'));
    }

    public function approveDealer(\App\Models\DealerRequest $dealerRequest)
    {
        if ($dealerRequest->status !== \App\Enums\DealerRequestStatus::PENDING) {
            return back()->withErrors('This request has already been processed.');
        }

        $dealerRequest->update([
            'status' => \App\Enums\DealerRequestStatus::APPROVED,
        ]);

        $dealerRequest->user->update([
            'role' => 'dealer',
            'status' => 'active',
        ]);

        $dealerRequest->user->notify(new \App\Notifications\DealerRequestApprovedNotification());

        \Illuminate\Support\Facades\Log::info('Admin approved dealer', [
            'admin_id' => auth()->id(),
            'dealer_id' => $dealerRequest->user_id,
        ]);

        return redirect()->route('admin.dealers.requests')->with('success', "Dealer {$dealerRequest->user->name} has been approved and activated.");
    }

    public function rejectDealer(\App\Models\DealerRequest $dealerRequest)
    {
        if ($dealerRequest->status !== \App\Enums\DealerRequestStatus::PENDING) {
            return back()->withErrors('This request has already been processed.');
        }

        $dealerRequest->update([
            'status' => \App\Enums\DealerRequestStatus::REJECTED,
        ]);

        $dealerRequest->user->notify(new \App\Notifications\DealerRequestRejectedNotification());

        \Illuminate\Support\Facades\Log::info('Admin rejected dealer application', [
            'admin_id' => auth()->id(),
            'dealer_id' => $dealerRequest->user_id,
        ]);

        return redirect()->route('admin.dealers.requests')->with('success', "Dealer application for {$dealerRequest->user->name} was rejected.");
    }

    public function updateDealerStatus(Request $request, User $user)
    {
        if ($user->role !== 'dealer') {
            return back()->withErrors('This account is not a dealer.');
        }

        if ($user->id === auth()->id()) {
            return back()->withErrors('Cannot modify your own status.');
        }

        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $user->update(['status' => $validated['status']]);

        return back()->with('success', "Dealer status updated to {$validated['status']}.");
    }

    public function cars(Request $request)
    {
        $query = Car::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $cars = $query->with(['showroom', 'user', 'images'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.cars.index', compact('cars'));
    }

    public function deleteCar(Car $car)
    {
        $car->delete();

        return back()->with('success', 'Car listing has been removed from the portal.');
    }

    public function showrooms(Request $request)
    {
        $query = Showroom::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $showrooms = $query->with(['user'])
            ->withCount('cars')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.showrooms.index', compact('showrooms'));
    }

    public function updateShowroomStatus(Request $request, Showroom $showroom)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $showroom->update(['is_active' => $validated['is_active']]);

        $statusText = $showroom->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Showroom '{$showroom->name}' status updated to {$statusText}.");
    }

    public function inquiries()
    {
        return 'Admin Inquiries Management';
    }
}
