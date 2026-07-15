<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dealer dashboard with statistics.
     */
    public function index()
    {
        $user = Auth::user();
        
        $totalCars = $user->cars()->count();
        $publishedCars = $user->cars()->where('status', 'published')->count();
        $inquiriesCount = Inquiry::where('dealer_id', $user->id)->count();
        $showroom = $user->showrooms()->first();

        return view('dashboarddealer.dashboard', compact(
            'totalCars',
            'publishedCars',
            'inquiriesCount',
            'showroom'
        ));
    }

    /**
     * Display the dealer profile settings form.
     */
    public function profile()
    {
        return view('dashboarddealer.profile');
    }
}
