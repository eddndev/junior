<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs with filtering.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with(['user', 'auditable'])
            ->latest('created_at');

        // Filter by user who performed the action
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action type
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by auditable type (model)
        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in old_values or new_values (JSON search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw("JSON_SEARCH(old_values, 'one', ?) IS NOT NULL", ["%{$search}%"])
                  ->orWhereRaw("JSON_SEARCH(new_values, 'one', ?) IS NOT NULL", ["%{$search}%"]);
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        // Get unique users for filter dropdown
        $users = User::active()
            ->orderBy('name')
            ->get(['id', 'name']);

        // Get unique actions for filter dropdown
        $actions = AuditLog::distinct()
            ->pluck('action')
            ->sort()
            ->values();

        // Get unique model types for filter dropdown
        $auditableTypes = AuditLog::distinct()
            ->pluck('auditable_type')
            ->map(function ($type) {
                // Convert full class name to readable format
                return [
                    'value' => $type,
                    'label' => class_basename($type),
                ];
            })
            ->sortBy('label')
            ->values();

        return view('audit-logs.index', compact('logs', 'users', 'actions', 'auditableTypes'));
    }
}
