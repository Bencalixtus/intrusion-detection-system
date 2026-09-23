<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NetworkEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NetworkEventApiController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'source_ip' => ['required', 'ip'],
            'destination_ip' => ['required', 'ip'],
            'source_port' => ['nullable', 'integer', 'between:1,65535'],
            'destination_port' => ['nullable', 'integer', 'between:1,65535'],
            'protocol' => ['required', 'string', 'max:20'],
            'event_type' => ['required', 'string', 'max:100'],
            'severity' => ['required', 'in:low,medium,high'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:unresolved,investigating,resolved'],
            'detected_at' => ['nullable', 'date'],
        ]);

        $event = NetworkEvent::create([
            'source_ip' => $validated['source_ip'],
            'destination_ip' => $validated['destination_ip'],
            'source_port' => $validated['source_port'] ?? null,
            'destination_port' => $validated['destination_port'] ?? null,
            'protocol' => $validated['protocol'],
            'event_type' => $validated['event_type'],
            'severity' => $validated['severity'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'unresolved',
            'detected_at' => $validated['detected_at'] ?? now(),
        ]);

        return response()->json([
            'message' => 'Security event recorded successfully.',
            'event' => $event,
        ], 201);
    }
}