<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\Showroom;
use App\Actions\FileUpload;
use App\Http\Requests\UpdateShowroomRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShowroomController extends Controller
{
    /**
     * Show the form for creating or editing the dealer's showroom.
     */
    public function edit()
    {
        $showroom = Auth::user()->showrooms()->first();

        return view('dashboarddealer.showroom', compact('showroom'));
    }

    /**
     * Store a newly created showroom in storage.
     */
    public function store(UpdateShowroomRequest $request, FileUpload $uploader)
    {
        $user = Auth::user();
        if ($user->showrooms()->exists()) {
            return redirect()->route('dashboarddealer.showroom.edit')->with('error', 'Showroom already exists.');
        }

        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            $validated['logo'] = $uploader->handle($request->file('logo'), 'showrooms');
        }

        $user->showrooms()->create($validated);

        return redirect()->route('dashboarddealer.showroom.edit')->with('success', 'Showroom created successfully.');
    }

    /**
     * Update the dealer's showroom in storage.
     */
    public function update(UpdateShowroomRequest $request, FileUpload $uploader)
    {
        $showroom = Auth::user()->showrooms()->first();

        if (!$showroom) {
            return redirect()->route('dashboarddealer.showroom.edit')->with('error', 'Showroom does not exist.');
        }

        \Illuminate\Support\Facades\Gate::authorize('manage', $showroom);

        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($showroom->logo) {
                Storage::disk('public')->delete($showroom->logo);
            }
            $validated['logo'] = $uploader->handle($request->file('logo'), 'showrooms');
        }

        $showroom->update($validated);

        return redirect()->route('dashboarddealer.showroom.edit')->with('success', 'Showroom updated successfully.');
    }
}
