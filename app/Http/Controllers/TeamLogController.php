<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamLogRequest;
use App\Http\Requests\UpdateTeamLogRequest;
use App\Models\TeamLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-bitacora')->only('index');
        $this->middleware('permission:crear-bitacora')->only(['store', 'edit', 'update']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userAreaIds = $user->areas->pluck('id');

        // Eager load media relationships para evitar N+1 queries
        $query = TeamLog::with(['user', 'area', 'media'])->whereIn('area_id', $userAreaIds);

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

        // Crear la entrada de bitácora
        $teamLog = auth()->user()->teamLogs()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'area_id' => $validated['area_id'],
            'type' => $validated['type'],
        ]);

        // Procesar archivos adjuntos (si existen)
        if ($request->hasFile('attachments')) {
            \Log::info('Procesando archivos adjuntos:', ['count' => count($request->file('attachments'))]);

            foreach ($request->file('attachments') as $index => $file) {
                try {
                    \Log::info("Archivo {$index}:", [
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);

                    $teamLog->addMedia($file)
                           ->toMediaCollection('attachments');
                    // Las conversiones (webp, avif) se procesarán automáticamente en cola
                } catch (\Exception $e) {
                    \Log::error('Error al procesar archivo adjunto: ' . $e->getMessage(), [
                        'file' => $file->getClientOriginalName(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }
        } else {
            \Log::warning('No se recibieron archivos adjuntos en la solicitud');
        }

        // Procesar enlaces (si existen) - NO descargar, solo guardar referencia
        if ($request->has('links') && is_array($request->input('links'))) {
            foreach ($request->input('links') as $link) {
                // Crear registro de media sin archivo físico, solo metadata
                $media = $teamLog->media()->create([
                    'collection_name' => 'links',
                    'name' => $link['url'],
                    'file_name' => basename(parse_url($link['url'], PHP_URL_PATH)) ?: 'link',
                    'mime_type' => 'text/uri-list',
                    'disk' => config('filesystems.default'),
                    'size' => 0,
                    'manipulations' => [],
                    'custom_properties' => [
                        'url' => $link['url'],
                        'link_type' => $link['type'],
                        'is_link' => true,
                    ],
                    'generated_conversions' => [],
                    'responsive_images' => [],
                    'order_column' => $teamLog->media()->where('collection_name', 'links')->count() + 1,
                ]);
            }
        }

        return redirect()->route('team-logs.index')
                       ->with('success', 'Entrada de bitácora creada con éxito. Los adjuntos se están procesando en segundo plano.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeamLog $teamLog)
    {
        // Only the author can edit their own team log entries
        if ($teamLog->user_id !== auth()->id()) {
            return redirect()->route('team-logs.index')->with('error', 'No tienes permiso para editar esta entrada.');
        }

        // Load area and media relationships for display
        $teamLog->load(['area', 'media']);

        return view('team-logs.edit', compact('teamLog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamLogRequest $request, TeamLog $teamLog)
    {
        $validated = $request->validated();

        $teamLog->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
        ]);

        // Process new attachments (if any)
        if ($request->hasFile('attachments')) {
            \Log::info('Procesando nuevos archivos adjuntos en actualización:', ['count' => count($request->file('attachments'))]);

            foreach ($request->file('attachments') as $index => $file) {
                try {
                    \Log::info("Archivo {$index}:", [
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);

                    $teamLog->addMedia($file)
                           ->toMediaCollection('attachments');
                } catch (\Exception $e) {
                    \Log::error('Error al procesar archivo adjunto: ' . $e->getMessage(), [
                        'file' => $file->getClientOriginalName(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }
        }

        // Process new links (if any)
        if ($request->has('links') && is_array($request->input('links'))) {
            foreach ($request->input('links') as $link) {
                $media = $teamLog->media()->create([
                    'collection_name' => 'links',
                    'name' => $link['url'],
                    'file_name' => basename(parse_url($link['url'], PHP_URL_PATH)) ?: 'link',
                    'mime_type' => 'text/uri-list',
                    'disk' => config('filesystems.default'),
                    'size' => 0,
                    'manipulations' => [],
                    'custom_properties' => [
                        'url' => $link['url'],
                        'link_type' => $link['type'],
                        'is_link' => true,
                    ],
                    'generated_conversions' => [],
                    'responsive_images' => [],
                    'order_column' => $teamLog->media()->where('collection_name', 'links')->count() + 1,
                ]);
            }
        }

        return redirect()->route('team-logs.index')
                       ->with('success', 'Entrada de bitácora actualizada con éxito.');
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
