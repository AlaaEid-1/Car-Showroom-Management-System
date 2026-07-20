<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Add a car listing to the customer's favorites.
     */
    public function store(Car $car)
    { 
        $user = Auth::user();

        abort_if($user->role !== 'customer', 403, 'Only customers can manage favorites.');
        abort_if($car->status !== 'published', 404, 'Only published vehicles can be favorited.');

        // Prevent duplicate favorites using syncWithoutDetaching
        $user->favoriteCars()->syncWithoutDetaching([$car->id]);

        return back()->with('success', 'Vehicle added to your favorites.');
    }

    /**
     * Remove a car listing from the customer's favorites.
     */
    public function destroy(Car $car)
    {
        $user = Auth::user();

        abort_if($user->role !== 'customer', 403, 'Only customers can manage favorites.');

        $user->favoriteCars()->detach($car->id);

        return back()->with('success', 'Vehicle removed from your favorites.');
    }
}
