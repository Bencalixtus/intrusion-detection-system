@extends('adminlte::page')

@section('title', 'Security Events')

@section('content_header')

    <div>
        <h1 class="mb-1">
            Network Security Events
        </h1>

        <p class="text-muted mb-0">
            Monitor and manage detected network security events.
        </p>
    </div>

@stop


@section('content')

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Events Summary --}}
    <div class="row mb-4">

        <div class="col-md-4">

            <div class="info-box shadow-sm">

                <span class="info-box-icon bg-primary">
                    <i class="fas fa-shield-alt"></i>
                </span>

                <div class="info-box-content">

                    <span class="info-box-text">
                        Total Events
                    </span>

                    <span class="info-box-number">
                        {{ $events->total() }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- Events Table --}}
    <div class="card shadow-sm">

        <div class="card-header">

            <h3 class="card-title">
                Detected Security Events
            </h3>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Source IP</th>

                            <th>Destination IP</th>

                            <th>Protocol</th>

                            <th>Event Type</th>

                            <th>Severity</th>

                            <th>Status</th>

                            <th>Detected At</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($events as $event)

                            <tr>

                                <td>
                                    {{ $event->id }}
                                </td>


                                <td>
                                    {{ $event->source_ip }}
                                </td>


                                <td>
                                    {{ $event->destination_ip ?? 'N/A' }}
                                </td>


                                <td>
                                    {{ $event->protocol ?? 'N/A' }}
                                </td>


                                <td>
                                    {{ $event->event_type }}
                                </td>


                                {{-- Severity --}}
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


                                {{-- Status --}}
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


                                {{-- Detected At --}}
                                <td>

                                    {{ $event->detected_at->format('d M Y, H:i:s') }}

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex flex-column gap-2">

                                        {{-- View --}}
                                        <a href="{{ route('security-events.show', ['networkEvent' => $event->id]) }}"
                                           class="btn btn-primary btn-sm">

                                            <i class="fas fa-eye me-1"></i>
                                            View Event

                                        </a>


                                        {{-- Update Status --}}
                                        <form
                                            action="{{ route('security-events.update', ['networkEvent' => $event->id]) }}"
                                            method="POST"
                                        >

                                            @csrf

                                            @method('PATCH')


                                            <div class="input-group input-group-sm">

                                                <select name="status"
                                                        class="form-select">

                                                    <option value="unresolved"
                                                        {{ $event->status === 'unresolved' ? 'selected' : '' }}>
                                                        Unresolved
                                                    </option>

                                                    <option value="investigating"
                                                        {{ $event->status === 'investigating' ? 'selected' : '' }}>
                                                        Investigating
                                                    </option>

                                                    <option value="resolved"
                                                        {{ $event->status === 'resolved' ? 'selected' : '' }}>
                                                        Resolved
                                                    </option>

                                                </select>


                                                <button type="submit"
                                                        class="btn btn-dark">

                                                    Update

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    <i class="fas fa-shield-alt fa-2x text-muted mb-3"></i>

                                    <p class="mb-0">
                                        No security events recorded yet.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($events->hasPages())

            <div class="card-footer">

                {{ $events->links() }}

            </div>

        @endif

    </div>

@stop