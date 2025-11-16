<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Availability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamAvailabilityController extends Controller
{
    /**
     * Display team availability for a specific area.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Get areas the user belongs to
        $userAreas = $user->areas()->orderBy('name')->get();

        // Get selected area (default to first area or null)
        $selectedAreaId = $request->get('area_id');

        if ($selectedAreaId) {
            $selectedArea = $userAreas->firstWhere('id', $selectedAreaId);
        } else {
            $selectedArea = $userAreas->first();
        }

        // Calculate current week (Monday to Sunday)
        $weekStart = Carbon::today()->startOfWeek(Carbon::MONDAY);

        // Get team members and their availability for the week
        $weekData = [];

        if ($selectedArea) {
            // Get all users in the selected area
            $teamMembers = $selectedArea->users()->orderBy('name')->get();

            // Build week data for each day (Monday to Sunday)
            $dayNames = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

            for ($i = 0; $i < 7; $i++) {
                $currentDate = $weekStart->copy()->addDays($i);
                $dateKey = $currentDate->format('Y-m-d');

                $weekData[$i] = [
                    'dayName' => $dayNames[$i],
                    'availability' => [],
                ];

                // Get availability for each team member on this day
                foreach ($teamMembers as $member) {
                    $memberAvailability = Availability::where('user_id', $member->id)
                        ->where('date', $dateKey)
                        ->where('status', 'available')
                        ->orderBy('start_time')
                        ->get();

                    if ($memberAvailability->isNotEmpty()) {
                        foreach ($memberAvailability as $slot) {
                            $weekData[$i]['availability'][] = [
                                'user' => $member,
                                'start_time' => Carbon::parse($slot->start_time)->format('H:i'),
                                'end_time' => Carbon::parse($slot->end_time)->format('H:i'),
                                'start_time_formatted' => Carbon::parse($slot->start_time)->format('g:i A'),
                                'end_time_formatted' => Carbon::parse($slot->end_time)->format('g:i A'),
                                'notes' => $slot->notes,
                            ];
                        }
                    }
                }

                // Sort availability by start time, then by user name
                usort($weekData[$i]['availability'], function ($a, $b) {
                    $timeCompare = strcmp($a['start_time'], $b['start_time']);
                    if ($timeCompare === 0) {
                        return strcmp($a['user']->name, $b['user']->name);
                    }
                    return $timeCompare;
                });
            }
        }

        return view('team-availability.index', [
            'userAreas' => $userAreas,
            'selectedArea' => $selectedArea,
            'weekData' => $weekData,
        ]);
    }

    /**
     * Get availability data for a specific day (AJAX endpoint).
     */
    public function dayData(Request $request)
    {
        $user = auth()->user();
        $areaId = $request->get('area_id');
        $date = $request->get('date');

        if (!$areaId || !$date) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        // Verify user has access to this area
        if (!$user->areas()->where('areas.id', $areaId)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $area = Area::with('users')->findOrFail($areaId);
        $dateObj = Carbon::parse($date);

        $availability = [];

        foreach ($area->users as $member) {
            $memberSlots = Availability::where('user_id', $member->id)
                ->where('date', $date)
                ->where('status', 'available')
                ->orderBy('start_time')
                ->get();

            foreach ($memberSlots as $slot) {
                $availability[] = [
                    'user_id' => $member->id,
                    'user_name' => $member->name,
                    'user_email' => $member->email,
                    'user_avatar' => $member->profile_photo_url ?? null,
                    'start_time' => Carbon::parse($slot->start_time)->format('g:i A'),
                    'end_time' => Carbon::parse($slot->end_time)->format('g:i A'),
                    'notes' => $slot->notes,
                ];
            }
        }

        // Sort by start time
        usort($availability, function ($a, $b) {
            return strcmp($a['start_time'], $b['start_time']);
        });

        return response()->json([
            'date' => $dateObj->format('Y-m-d'),
            'date_formatted' => $dateObj->translatedFormat('l, d F Y'),
            'availability' => $availability,
        ]);
    }
}
