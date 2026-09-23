<?php

namespace App\Http\Controllers;

use App\Models\NetworkEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NetworkEventController extends Controller
{
    /**
     * Display a listing of network security events.
     */
    public function index(Request $request): View
    {
        $query = NetworkEvent::query();

        // Search by IP address or event type
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('source_ip', 'like', "%{$search}%")
                    ->orWhere('destination_ip', 'like', "%{$search}%")
                    ->orWhere('event_type', 'like', "%{$search}%");
            });
        }

        // Filter by severity
        if ($request->filled('severity')) {
            $query->where('severity', $request->input('severity'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by protocol
        if ($request->filled('protocol')) {
            $query->where('protocol', $request->input('protocol'));
        }

        $events = $query
            ->orderByDesc('detected_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('network-events.index', compact('events'));
    }

    /**
     * Display a specific network security event.
     */
    public function show(NetworkEvent $networkEvent): View
    {
        return view('network-events.show', compact('networkEvent'));
    }

    /**
     * Update the status of a network security event.
     */
    public function update(
        Request $request,
        NetworkEvent $networkEvent
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:unresolved,investigating,resolved',
            ],
        ]);

        $networkEvent->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('security-events.index')
            ->with(
                'success',
                'Security event status updated successfully.'
            );
    }
}