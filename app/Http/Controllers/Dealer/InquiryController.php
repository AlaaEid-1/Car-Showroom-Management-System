<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\Inquiry;
use App\Models\TestDrive;
use App\Services\CarService;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateInquiryRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InquiryController extends Controller
{
    public function store(UpdateInquiryRequest $request, $carId)
{
    $validated = $request->validated();
    
    $car = Car::findOrFail($carId);

    // منع إرسال لنفسه
    if ($car->user_id === Auth::id()) {
        return back()->with('error', 'You cannot send inquiry to your own car.');
    }

    $inquiry = Inquiry::create([
        'car_id' => $car->id,
        'buyer_id' => Auth::id(),
        'dealer_id' => $car->user_id,
        'subject' => 'Car Inquiry',
        'status' => 'open',
        'last_message_at' => now(),
    ]);

    $inquiry->messages()->create([
        'sender_id' => Auth::id(),
        'message' => $request->message,
    ]);

    $dealer = $inquiry->dealer;
    if ($dealer) {
        $dealer->notify(new \App\Notifications\NewInquiry($inquiry));
    }

    return back()->with('success', 'Inquiry sent successfully.');
}

public function index()
{
    $user = Auth::user();

    if ($user->role === 'dealer') {
        $inquiries = Inquiry::with(['car.images', 'buyer'])
            ->where('dealer_id', $user->id)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('dashboarddealer.inquiry', compact('inquiries'));
    }

    $inquiries = Inquiry::with(['car.images', 'dealer'])
        ->where('buyer_id', $user->id)
        ->latest()
        ->paginate(10)
        ->withQueryString();

    $favorites = $user->favoriteCars()->with('images')->latest()->get();
    $testDrives = TestDrive::with(['car.images', 'car.user'])
        ->where('user_id', $user->id)
        ->latest()
        ->get();

    return view('dashboardcustomer.inquiry', compact('inquiries', 'favorites', 'testDrives'));
}

public function show($id)
{
    $inquiry = Inquiry::with([
        'car',
        'buyer',
        'dealer',
        'messages.sender'
    ])->findOrFail($id);

    \Illuminate\Support\Facades\Gate::authorize('view', $inquiry);

    return view('inquiries.show', compact('inquiry'));
}

public function sendMessage(UpdateInquiryRequest $request, $id)
{
    $validated = $request->validated();

    $inquiry = Inquiry::findOrFail($id);

    \Illuminate\Support\Facades\Gate::authorize('view', $inquiry);
    abort_if($inquiry->status === 'closed', 400, 'This inquiry is closed.');

    $messageRecord = $inquiry->messages()->create([
        'sender_id' => Auth::id(),
        'message' => $request->message,
    ]);

    $messageRecord->load('sender');

    $status = Auth::id() === $inquiry->buyer_id ? 'pending' : 'answered';

    $inquiry->update([
        'last_message_at' => now(),
        'status' => $status
    ]);

    $recipient = Auth::id() === $inquiry->buyer_id ? $inquiry->dealer : $inquiry->buyer;
    if ($recipient) {
        $recipient->notify(new \App\Notifications\NewInquiryReply($inquiry, $messageRecord));
    }

    return back();
}

public function close($id)
{
    $inquiry = Inquiry::findOrFail($id);

    \Illuminate\Support\Facades\Gate::authorize('close', $inquiry);

    $inquiry->update([
        'status' => 'closed'
    ]);

    \Illuminate\Support\Facades\Log::info('Inquiry closed', [
        'user_id' => Auth::id(),
        'inquiry_id' => $inquiry->id,
    ]);

    return back()->with('success', 'Inquiry has been closed.');
}
}
