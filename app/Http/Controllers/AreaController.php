<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Http\Requests\StoreAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AreaController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Area::class, 'area');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Area::query();

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $areas = $query->latest()->paginate(10)->withQueryString();

        return view('areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('areas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAreaRequest $request)
    {
        $validated = $request->validated();

        Area::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('areas.index')->with('success', 'Área creada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAreaRequest $request, Area $area)
    {
        $validated = $request->validated();

        $area->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('areas.index')->with('success', 'Área actualizada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        // Verificar si el área es del sistema
        if ($area->is_system) {
            return redirect()->route('areas.index')->with('error', 'No se puede desactivar un área del sistema.');
        }

        // Verificar dependencias activas
        $hasActiveUsers = $area->users()->wherePivot('deleted_at', null)->exists();
        $hasActiveTasks = $area->tasks()->whereIn('status', ['pending', 'in_progress'])->exists();
        $hasActiveBudgets = $area->budgets()->where('status', 'active')->exists();

        if ($hasActiveUsers) {
            return redirect()->route('areas.index')->with('error', 'No se puede desactivar el área porque tiene usuarios asignados. Primero reasigna o elimina los usuarios.');
        }

        if ($hasActiveTasks) {
            return redirect()->route('areas.index')->with('error', 'No se puede desactivar el área porque tiene tareas activas. Primero completa o reasigna las tareas.');
        }

        if ($hasActiveBudgets) {
            return redirect()->route('areas.index')->with('error', 'No se puede desactivar el área porque tiene presupuestos activos.');
        }

        // Si pasa todas las validaciones, desactivar el área
        $area->update(['is_active' => false]);

        return redirect()->route('areas.index')->with('success', 'Área desactivada con éxito.');
    }
}
