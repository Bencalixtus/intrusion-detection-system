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
    public function index(): View
    {
        $events = NetworkEvent::orderByDesc('detected_at')
            ->orderByDesc('id')
            ->paginate(15);

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
    public function update(Request $request, NetworkEvent $networkEvent): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:unresolved,investigating,resolved'],
        ]);

        $networkEvent->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('security-events.index')
            ->with('success', 'Security event status updated successfully.');
    }
}