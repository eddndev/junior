<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateAvailabilityRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    /**
     * Display the user's availability form.
     */
    public function show(Request $request): View
    {
        return view('profile.availability', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's availability information.
     */
    public function update(UpdateAvailabilityRequest $request): RedirectResponse
    {
        Log::info('Raw availability data received:', $request->all());
        $validated = $request->validated();

        $user = $request->user();
        $availabilityByDay = $validated['availability'] ?? [];

        // Get all days of the current week to ensure we clear old data correctly
        $startOfWeek = now()->startOfWeek();
        $weekDates = [];
        for ($i = 0; $i < 7; $i++) {
            $weekDates[] = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
        }

        DB::transaction(function () use ($user, $availabilityByDay, $weekDates) {
            // Clear all existing availability for the displayed week
            $user->availability()->whereIn('date', $weekDates)->delete();

            // Process the submitted slots
            foreach ($availabilityByDay as $date => $slots) {
                if (empty($slots)) {
                    continue;
                }

                // Sort slots to ensure they are in chronological order
                sort($slots);

                $startTime = null;
                $previousSlot = null;

                foreach ($slots as $index => $slot) {
                    $currentSlotTime = Carbon::parse($slot);

                    if ($startTime === null) {
                        $startTime = $currentSlotTime;
                    }

                    if ($previousSlot !== null) {
                        // Check if the current slot is not contiguous with the previous one
                        if ($previousSlot->diffInMinutes($currentSlotTime) > 30) {
                            // End of a block, save it
                            $user->availability()->create([
                                'date' => $date,
                                'start_time' => $startTime->format('H:i'),
                                'end_time' => $previousSlot->copy()->addMinutes(30)->format('H:i'),
                                'status' => 'available',
                            ]);
                            // Start a new block
                            $startTime = $currentSlotTime;
                        }
                    }

                    $previousSlot = $currentSlotTime;

                    // If it's the last slot, close the final block
                    if ($index === count($slots) - 1) {
                        $user->availability()->create([
                            'date' => $date,
                            'start_time' => $startTime->format('H:i'),
                            'end_time' => $currentSlotTime->copy()->addMinutes(30)->format('H:i'),
                            'status' => 'available',
                        ]);
                    }
                }
            }
        });

        return Redirect::route('profile.availability.show')->with('status', 'availability-updated');
    }
}
