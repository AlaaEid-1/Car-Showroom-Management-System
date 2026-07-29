<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Models\CarImage;
use App\Services\CarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'published');
        $user = Auth::user();
        $query = $user->cars()->with('images');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($status === 'trash') {
            $query->onlyTrashed();
        } else {
            $query->where('status', $status);
        }

        $cars = $query->latest()->paginate(10)->withQueryString();
        $totalCars = $user->cars()->count();

        $activeListings = $user->cars()
            ->where('status', 'published')
            ->count();

        $draftListings = $user->cars()
            ->where('status', 'draft')
            ->count();

        $soldListings = $user->cars()
            ->where('status', 'sold')
            ->count();

        $trashCount = $user->cars()
            ->onlyTrashed()
            ->count();

        $status_options = [
            [
                'name' => 'Published',
                'count' => $activeListings,
            ],
            [
                'name' => 'Draft',
                'count' => $draftListings,
            ],
            [
                'name' => 'Sold',
                'count' => $soldListings,
            ],
            [
                'name' => 'Trash',
                'count' => $trashCount,
            ],
        ];

        $newMessages = \App\Models\Inquiry::where('dealer_id', $user->id)->count();
        $testDriveRequests = \App\Models\TestDrive::whereHas('car', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        return view('dashboarddealer.cars.index', compact(
            'cars',
            'status',
            'status_options',
            'totalCars',
            'activeListings',
            'newMessages',
            'testDriveRequests'
        ));
    }
    public function show($id)
    {
        $car = Car::with(['images', 'showroom'])->findOrFail($id);

        if (!\Illuminate\Support\Facades\Gate::allows('view', $car)) {
            abort(404);
        }

        return view('dashboarddealer.cars.show', compact('car'));
    }
    public function create()
    {
        if (!Auth::user()->showrooms()->exists()) {
            return redirect()->route('dashboarddealer.showroom.edit')->with('error', 'You must create a showroom before listing vehicles.');
        }

        return view('dashboarddealer.cars.create', [
            'car' => new Car()
        ]);
    }

    public function store(CreateCarRequest $request, CarService $service)
    {
        $service->create($request);

        return redirect()
            ->route('dashboarddealer.cars.index')
            ->with('success', 'Car created successfully');
    }

    public function edit(Car $car)
    {
        $this->authorizeCar($car);

        $car->load('images');

        return view('dashboarddealer.cars.edit', compact('car'));
    }

    public function update(UpdateCarRequest $request, Car $car, CarService $service)
    {
        $this->authorizeCar($car);

        $service->update($car, $request);

        return redirect()
            ->route('dashboarddealer.cars.index')
            ->with('success', 'Car updated successfully');
    }

    public function destroy(Car $car)
    {
        $this->authorizeCar($car);

        $car->delete();

        return back()->with('success', 'Car deleted');
    }

    public function restore(string $id)
    {
        $car = Car::onlyTrashed()->findOrFail($id);

        $this->authorizeCar($car);

        $car->restore();

        return redirect()
            ->route('dashboarddealer.cars.index')
            ->with('success', 'Car restored!');
    }

    public function forceDelete(string $id)
    {
        $car = Car::onlyTrashed()->findOrFail($id);

        $this->authorizeCar($car);

        $car->load('images'); // مهم جداً

        foreach ($car->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $car->images()->delete();
        $car->forceDelete();

        return redirect()
            ->route('dashboarddealer.cars.index')
            ->with('success', 'Car permanently deleted!');
    }

    private function authorizeCar(Car $car): void
    {
        \Illuminate\Support\Facades\Gate::authorize('manage', $car);
    }

    public function deleteImage(Car $car, CarImage $image)
    {
        $this->authorizeCar($car);

        if ($image->car_id !== $car->id) {
            abort(403);
        }

        Storage::disk('public')->delete($image->path);
        $image->delete();

        if ($image->is_main && $car->images()->exists()) {
            $car->images()->first()->update(['is_main' => true]);
        }

        return back()->with('success', 'Image deleted successfully.');
    }

    public function setMainImage(Car $car, CarImage $image)
    {
        $this->authorizeCar($car);

        if ($image->car_id !== $car->id) {
            abort(403);
        }

        $car->images()->update(['is_main' => false]);
        $image->update(['is_main' => true]);

        return back()->with('success', 'Main image updated successfully.');
    }

    public function search(Request $request)
    {
        $cars = Car::where('status', 'published');

        if ($request->filled('year')) {
            $cars->where('year', $request->year);
        }

        if ($request->filled('model')) {
            $cars->where('model', $request->model);
        }

        if ($request->filled('price_range')) {

            switch ($request->price_range) {

                case '50-100':
                    $cars->whereBetween('price', [50000, 100000]);
                    break;

                case '100-250':
                    $cars->whereBetween('price', [100000, 250000]);
                    break;

                case '250-500':
                    $cars->whereBetween('price', [250000, 500000]);
                    break;

                case '500+':
                    $cars->where('price', '>=', 500000);
                    break;
            }
        }

        return view('resultscars', [
            'cars' => $cars->with('images')->latest()->paginate(6)->withQueryString()
        ]);
    }
}
