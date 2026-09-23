@extends('layouts.adminlte')

@section('header')

    <div class="d-flex justify-content-between align-items-center">

        <div>
            <h1 class="h3 mb-1">
                IDS Dashboard
            </h1>

            <p class="text-muted mb-0">
                Network Intrusion Detection and Monitoring System
            </p>
        </div>

    </div>

@endsection


@section('content')

    <!-- Event Status -->
    <div class="row g-4 mb-4">

        <!-- Total Events -->
        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <p class="text-muted mb-1">
                                Total Events
                            </p>

                            <h2 class="mb-0">
                                {{ $totalEvents }}
                            </h2>
                        </div>

                        <div class="text-primary fs-2">
                            <i class="fas fa-list"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Unresolved -->
        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <p class="text-muted mb-1">
                                Unresolved
                            </p>

                            <h2 class="mb-0">
                                {{ $unresolvedEvents }}
                            </h2>
                        </div>

                        <div class="text-danger fs-2">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Investigating -->
        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <p class="text-muted mb-1">
                                Investigating
                            </p>

                            <h2 class="mb-0">
                                {{ $investigatingEvents }}
                            </h2>
                        </div>

                        <div class="text-warning fs-2">
                            <i class="fas fa-search"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Resolved -->
        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <p class="text-muted mb-1">
                                Resolved
                            </p>

                            <h2 class="mb-0">
                                {{ $resolvedEvents }}
                            </h2>
                        </div>

                        <div class="text-success fs-2">
                            <i class="fas fa-check-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Severity Statistics -->
    <div class="row g-4 mb-4">

        <!-- High -->
        <div class="col-lg-4">

            <div class="card shadow-sm border-start border-danger border-4">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        High Severity
                    </p>

                    <h2 class="mb-0">
                        {{ $highSeverity }}
                    </h2>

                </div>

            </div>

        </div>


        <!-- Medium -->
        <div class="col-lg-4">

            <div class="card shadow-sm border-start border-warning border-4">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Medium Severity
                    </p>

                    <h2 class="mb-0">
                        {{ $mediumSeverity }}
                    </h2>

                </div>

            </div>

        </div>


        <!-- Low -->
        <div class="col-lg-4">

            <div class="card shadow-sm border-start border-success border-4">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Low Severity
                    </p>

                    <h2 class="mb-0">
                        {{ $lowSeverity }}
                    </h2>

                </div>

            </div>

        </div>

    </div>


    <!-- Recent Events -->
    <div class="card shadow-sm">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h3 class="card-title mb-0">
                    Recent Security Events
                </h3>

                <a href="{{ route('security-events.index') }}"
                   class="btn btn-primary btn-sm">

                    View All Events

                </a>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Source IP</th>

                            <th>Event Type</th>

                            <th>Severity</th>

                            <th>Status</th>

                            <th>Detected At</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($recentEvents as $event)

                            <tr>

                                <td>
                                    #{{ $event->id }}
                                </td>

                                <td>
                                    {{ $event->source_ip }}
                                </td>

                                <td>
                                    {{ $event->event_type }}
                                </td>

                                <td>

                                    @if($event->severity === 'high')

                                        <span class="badge bg-danger">
                                            High
                                        </span>

                                    @elseif($event->severity === 'medium')

                                        <span class="badge bg-warning text-dark">
                                            Medium
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            Low
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($event->status === 'resolved')

                                        <span class="badge bg-success">
                                            Resolved
                                        </span>

                                    @elseif($event->status === 'investigating')

                                        <span class="badge bg-warning text-dark">
                                            Investigating
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Unresolved
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ $event->detected_at->format('d M Y, H:i') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center py-4">

                                    No security events recorded yet.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection