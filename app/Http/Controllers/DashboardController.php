<?php

namespace App\Http\Controllers;

use App\Models\NetworkEvent;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the IDS dashboard.
     */
    public function index(): View
    {
        $totalEvents = NetworkEvent::count();

        $unresolvedEvents = NetworkEvent::where('status', 'unresolved')->count();

        $investigatingEvents = NetworkEvent::where('status', 'investigating')->count();

        $resolvedEvents = NetworkEvent::where('status', 'resolved')->count();

        $highSeverity = NetworkEvent::where('severity', 'high')->count();

        $mediumSeverity = NetworkEvent::where('severity', 'medium')->count();

        $lowSeverity = NetworkEvent::where('severity', 'low')->count();

        $recentEvents = NetworkEvent::orderByDesc('detected_at')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalEvents',
            'unresolvedEvents',
            'investigatingEvents',
            'resolvedEvents',
            'highSeverity',
            'mediumSeverity',
            'lowSeverity',
            'recentEvents'
        ));
    }
}