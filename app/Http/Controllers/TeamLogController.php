<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamLogRequest;
use App\Models\TeamLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-bitacora')->only('index');
        $this->middleware('permission:crear-bitacora')->only('store');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userAreaIds = $user->areas->pluck('id');

        $query = TeamLog::with('user', 'area')->whereIn('area_id', $userAreaIds);

        // Filter by a specific area if provided and if the user belongs to it
        if ($request->filled('area_id') && $userAreaIds->contains($request->area_id)) {
            $query->where('area_id', $request->area_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search in title and content
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest()->paginate(15)->withQueryString();
        $userAreas = $user->areas;

        return view('team-logs.index', compact('logs', 'userAreas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeamLogRequest $request)
    {
        // Validation and authorization handled by StoreTeamLogRequest
        $validated = $request->validated();

        auth()->user()->teamLogs()->create($validated);

        return redirect()->route('team-logs.index')->with('success', 'Entrada de bitácora creada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeamLog $teamLog)
    {
        // Only the author can delete their own team log entries
        if ($teamLog->user_id !== auth()->id()) {
            return redirect()->route('team-logs.index')->with('error', 'No tienes permiso para eliminar esta entrada.');
        }

        // Check if user has permission to create team log entries (implies they can delete their own)
        if (!auth()->user()->hasPermission('crear-bitacora')) {
            return redirect()->route('team-logs.index')->with('error', 'No tienes permiso para eliminar entradas de bitácora.');
        }

        $teamLog->delete();

        return redirect()->route('team-logs.index')->with('success', 'Entrada de bitácora eliminada con éxito.');
    }
}
