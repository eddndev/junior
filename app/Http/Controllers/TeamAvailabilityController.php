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

        // Transform data for heatmap view
        $heatmapData = [];
        $dayNames = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        // Generate slots for each hour (8:00 to 20:00)
        for ($hour = 8; $hour <= 20; $hour++) {
            $timeFormatted = sprintf('%d:00 %s', $hour > 12 ? $hour - 12 : $hour, $hour >= 12 ? 'PM' : 'AM');

            for ($dayIndex = 0; $dayIndex < 7; $dayIndex++) {
                $key = $hour . '-' . $dayIndex;
                $usersInSlot = [];

                if (isset($weekData[$dayIndex])) {
                    foreach ($weekData[$dayIndex]['availability'] as $avail) {
                        $startHour = (int) substr($avail['start_time'], 0, 2);
                        $endHour = (int) substr($avail['end_time'], 0, 2);
                        $endMin = (int) substr($avail['end_time'], 3, 2);

                        // Adjust end hour if minutes > 0 (e.g., 17:30 means available until 17:30, not 18:00)
                        $effectiveEndHour = $endMin > 0 ? $endHour : $endHour - 1;

                        // User is available if hour falls within their range
                        if ($hour >= $startHour && $hour <= $effectiveEndHour) {
                            $usersInSlot[] = [
                                'id' => $avail['user']->id,
                                'name' => $avail['user']->name,
                                'email' => $avail['user']->email,
                                'initials' => strtoupper(substr($avail['user']->name, 0, 1)),
                                'start_time' => $avail['start_time_formatted'],
                                'end_time' => $avail['end_time_formatted'],
                                'notes' => $avail['notes'],
                            ];
                        }
                    }
                }

                $heatmapData[$key] = [
                    'hour' => $hour,
                    'dayIndex' => $dayIndex,
                    'dayName' => $dayNames[$dayIndex],
                    'timeFormatted' => $timeFormatted,
                    'count' => count($usersInSlot),
                    'users' => $usersInSlot,
                ];
            }
        }

        return view('team-availability.index', [
            'userAreas' => $userAreas,
            'selectedArea' => $selectedArea,
            'weekData' => $weekData,
            'heatmapData' => $heatmapData,
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
