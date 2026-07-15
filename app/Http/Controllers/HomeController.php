<?php
namespace App\Http\Controllers;

use App\Models\Car;

class HomeController extends Controller
{
    public function index()
    {
        $years = Car::select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $models = Car::select('model')
            ->distinct()
            ->orderBy('model')
            ->pluck('model');

        $prices = Car::select('price')
            ->distinct()
            ->pluck('price');

        return view('welcome', compact('years', 'models', 'prices'));
    }
}
