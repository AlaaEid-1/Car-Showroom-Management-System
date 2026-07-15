<?php

namespace App\Services;

use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CarService
{
    public function __construct()
    {
        //
    }

    /**
     * CREATE CAR
     */
    public function create(CarRequest $request): Car
    {
        $data = $request->validated();

        $showroom = Auth::user()->showrooms()->first();

        if (!$showroom) {
            throw new \Exception('No showroom found for this user');
        }

        DB::beginTransaction();

        try {

            $car = Car::create(array_merge($data, [
                'user_id' => Auth::id(),
                'showroom_id' => $showroom->id,
            ]));

            $this->uploadImages($car, $request);

            DB::commit();

            \Illuminate\Support\Facades\Log::info('Dealer listed a new vehicle', [
                'dealer_id' => Auth::id(),
                'car_id' => $car->id,
                'status' => $car->status,
            ]);

            return $car;

        } catch (Throwable $e) {

            DB::rollBack();
            throw $e;
        }
    }

    /**
     * UPDATE CAR
     */
    public function update(Car $car, CarRequest $request): Car
    {
        $data = $request->validated();
        $oldStatus = $car->status;

        DB::beginTransaction();

        try {

            $car->update($data);

            $this->replaceImages($car, $request);

            DB::commit();

            if ($oldStatus !== $car->status) {
                \Illuminate\Support\Facades\Log::info('Dealer changed vehicle status', [
                    'dealer_id' => Auth::id(),
                    'car_id' => $car->id,
                    'old_status' => $oldStatus,
                    'new_status' => $car->status,
                ]);
            } else {
                \Illuminate\Support\Facades\Log::info('Dealer updated vehicle details', [
                    'dealer_id' => Auth::id(),
                    'car_id' => $car->id,
                ]);
            }

            return $car;

        } catch (Throwable $e) {

            DB::rollBack();
            throw $e;
        }
    }

    /**
     * UPLOAD IMAGES (CREATE)
     */
    private function uploadImages(Car $car, CarRequest $request): void
    {
        $files = $request->file('images');

        if (!$files) {
            return;
        }

        foreach ($files as $index => $file) {

            CarImage::create([
                'car_id' => $car->id,
                'path' => $file->store('cars', 'public'),
                'is_main' => $index === 0,
            ]);
        }
    }

    /**
     * REPLACE IMAGES (UPDATE)
     */
    private function replaceImages(Car $car, CarRequest $request): void
    {
        $files = $request->file('images');

        if (!$files) {
            return;
        }

        $hasMain = $car->images()->where('is_main', true)->exists();

        // upload new images
        foreach ($files as $index => $file) {

            CarImage::create([
                'car_id' => $car->id,
                'path' => $file->store('cars', 'public'),
                'is_main' => !$hasMain && ($index === 0),
            ]);
            $hasMain = true;
        }
    }
}
