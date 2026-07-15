<?php

namespace App\Http\Controllers\Dealer\AI;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarAIRequest;
use App\Models\Car;
use App\Services\AI\CarListingAssistantService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CarAssistantController extends Controller
{
    /**
     * Generate car listing description and suggestions from prompt details.
     */
    public function generateDescription(CarAIRequest $request, CarListingAssistantService $service)
    {
        $data = $service->generate($request->validated());

        Log::info('Dealer generated car listing description using AI assistant', [
            'dealer_id' => Auth::id(),
            'params' => $request->validated(),
        ]);

        return response()->json($data);
    }

    /**
     * Improve an existing car listing using database and form details.
     */
    public function improveListing(CarAIRequest $request, Car $car, CarListingAssistantService $service)
    {
        Gate::authorize('manage', $car);

        // Merge existing car details with validated inputs (removing null values to allow fallback)
        $params = array_merge([
            'title' => $car->title,
            'brand' => $car->brand,
            'model' => $car->model,
            'year' => $car->year,
            'price' => $car->price,
            'description' => $car->description,
        ], array_filter($request->validated()));

        $data = $service->generate($params);

        Log::info('Dealer improved car listing using AI assistant', [
            'dealer_id' => Auth::id(),
            'car_id' => $car->id,
            'params' => $request->validated(),
        ]);

        return response()->json($data);
    }
}
