<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;

class CarApiController extends Controller
{
    public function index()
    {
        $cars = Car::with('images')->get();

        return response()->json([
            'success' => true,
            'cars' => $cars
        ]);
    }
}
