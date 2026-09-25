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

        <div class="text-end">

            <small class="text-muted d-block">
                Dashboard status
            </small>

            <span class="badge bg-success">
                <i class="fas fa-circle me-1"></i>
                Monitoring
            </span>

            <small class="text-muted d-block mt-1">
                Last updated:
                <span id="last-updated">
                    Loading...
                </span>
            </small>

        </div>

    </div>

@endsection


@section('content')

    <!-- LIVE SECURITY ALERT -->
<div id="security-alert"
     class="alert alert-danger shadow-sm d-none"
     role="alert">

    <div class="d-flex align-items-start">

        <div class="me-3 fs-4">
            <i class="fas fa-shield-alt"></i>
        </div>

        <div class="flex-grow-1">

            <h5 class="alert-heading mb-1">
                Security Alert
            </h5>

            <div id="security-alert-message">
                High-severity security event detected.
            </div>

            <small class="d-block mt-2">
                <strong>Source:</strong>
                <span id="alert-source-ip"></span>

                &nbsp; | &nbsp;

                <strong>Event:</strong>
                <span id="alert-event-type"></span>
            </small>

        </div>

        <div class="ms-3">
            <a href="{{ route('security-events.index') }}"
               class="btn btn-danger btn-sm">
                <i class="fas fa-eye me-1"></i>
                View Events
            </a>
        </div>

    </div>

</div>

    <!-- ========================================================= -->
    <!-- EVENT STATUS -->
    <!-- ========================================================= -->

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

                            <h2 class="mb-0" id="total-events">
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

                            <h2 class="mb-0" id="unresolved-events">
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

                            <h2 class="mb-0" id="investigating-events">
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

                            <h2 class="mb-0" id="resolved-events">
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


    <!-- ========================================================= -->
    <!-- SEVERITY STATISTICS -->
    <!-- ========================================================= -->

    <div class="row g-4 mb-4">

        <!-- High -->

        <div class="col-lg-4">

            <div class="card shadow-sm border-start border-danger border-4">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        High Severity
                    </p>

                    <h2 class="mb-0" id="high-severity">
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

                    <h2 class="mb-0" id="medium-severity">
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

                    <h2 class="mb-0" id="low-severity">
                        {{ $lowSeverity }}
                    </h2>

                </div>

            </div>

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- SEVERITY DISTRIBUTION CHART -->
    <!-- ========================================================= -->

    <div class="row g-4 mb-4">

        <div class="col-12">

            <div class="card shadow-sm">

                <div class="card-header">

                    <h3 class="card-title mb-0">

                        <i class="fas fa-chart-bar me-2"></i>

                        Security Events by Severity

                    </h3>

                </div>

                <div class="card-body">

                    <div style="height: 300px;">

                        <canvas id="severity-chart"></canvas>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- RECENT SECURITY EVENTS -->
    <!-- ========================================================= -->

    <div class="card shadow-sm">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h3 class="card-title mb-0">
                    Recent Security Events
                </h3>

                <a href="{{ route('security-events.index') }}"
                   class="btn btn-primary btn-sm">

                    <i class="fas fa-list me-1"></i>

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


                    <tbody id="recent-events-table">

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


@section('js')

    <!-- Chart.js -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>

        /*
        |--------------------------------------------------------------------------
        | Severity Chart
        |--------------------------------------------------------------------------
        */

        let severityChart = null;


        function updateSeverityChart(data) {

            const canvas = document.getElementById('severity-chart');

            if (!canvas) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Destroy the previous chart
            |--------------------------------------------------------------------------
            */

            if (severityChart) {
                severityChart.destroy();
            }


            /*
            |--------------------------------------------------------------------------
            | Create the chart
            |--------------------------------------------------------------------------
            */

            severityChart = new Chart(canvas, {

                type: 'bar',

                data: {

                    labels: [
                        'High',
                        'Medium',
                        'Low'
                    ],

                    datasets: [{

                        label: 'Security Events',

                        data: [
                            data.highSeverity,
                            data.mediumSeverity,
                            data.lowSeverity
                        ],

                        backgroundColor: [
                            '#dc3545',
                            '#ffc107',
                            '#198754'
                        ],

                        borderWidth: 1

                    }]

                },


                options: {

                    responsive: true,

                    maintainAspectRatio: false,


                    scales: {

                        y: {

                            beginAtZero: true,

                            ticks: {

                                precision: 0

                            }

                        }

                    },


                    plugins: {

                        legend: {

                            display: false

                        }

                    }

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        function updateDashboardStats() {

            fetch('{{ route('dashboard.stats') }}', {

                headers: {
                    'Accept': 'application/json'
                }

            })

            .then(response => {

                if (!response.ok) {

                    throw new Error(
                        'Unable to retrieve dashboard statistics.'
                    );

                }

                return response.json();

            })


            .then(data => {

                /*
                |--------------------------------------------------------------------------
                | Update Event Status Statistics
                |--------------------------------------------------------------------------
                */

                document.getElementById('total-events').textContent =
                    data.totalEvents;


                document.getElementById('unresolved-events').textContent =
                    data.unresolvedEvents;


                document.getElementById('investigating-events').textContent =
                    data.investigatingEvents;


                document.getElementById('resolved-events').textContent =
                    data.resolvedEvents;


                /*
                |--------------------------------------------------------------------------
                | Update Severity Statistics
                |--------------------------------------------------------------------------
                */

                document.getElementById('high-severity').textContent =
                    data.highSeverity;


                document.getElementById('medium-severity').textContent =
                    data.mediumSeverity;


                document.getElementById('low-severity').textContent =
                    data.lowSeverity;


                /*
                |--------------------------------------------------------------------------
                | Update Severity Chart
                |--------------------------------------------------------------------------
                */

                updateSeverityChart(data);

            })


            .catch(error => {

                console.error(
                    'Dashboard statistics update failed:',
                    error
                );

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Recent Security Events
        |--------------------------------------------------------------------------
        */

        function updateRecentEvents() {

            fetch('{{ route('dashboard.recent-events') }}', {

                headers: {
                    'Accept': 'application/json'
                }

            })

            .then(response => {

                if (!response.ok) {

                    throw new Error(
                        'Unable to retrieve recent security events.'
                    );

                }

                return response.json();

            })


            .then(data => {

                const tableBody =
                    document.getElementById('recent-events-table');


                if (!data.events || data.events.length === 0) {

                    tableBody.innerHTML = `

                        <tr>

                            <td colspan="6"
                                class="text-center py-4">

                                No security events recorded yet.

                            </td>

                        </tr>

                    `;

                    return;

                }


                let rows = '';


                data.events.forEach(event => {


                    /*
                    |--------------------------------------------------------------------------
                    | Severity Badge
                    |--------------------------------------------------------------------------
                    */

                    let severityBadge = '';


                    if (event.severity === 'high') {

                        severityBadge = `

                            <span class="badge bg-danger">
                                High
                            </span>

                        `;

                    }

                    else if (event.severity === 'medium') {

                        severityBadge = `

                            <span class="badge bg-warning text-dark">
                                Medium
                            </span>

                        `;

                    }

                    else {

                        severityBadge = `

                            <span class="badge bg-success">
                                Low
                            </span>

                        `;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Status Badge
                    |--------------------------------------------------------------------------
                    */

                    let statusBadge = '';


                    if (event.status === 'resolved') {

                        statusBadge = `

                            <span class="badge bg-success">
                                Resolved
                            </span>

                        `;

                    }

                    else if (event.status === 'investigating') {

                        statusBadge = `

                            <span class="badge bg-warning text-dark">
                                Investigating
                            </span>

                        `;

                    }

                    else {

                        statusBadge = `

                            <span class="badge bg-danger">
                                Unresolved
                            </span>

                        `;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Date Formatting
                    |--------------------------------------------------------------------------
                    */

                    const detectedAt =
                        new Date(event.detected_at);


                    const formattedDate =
                        detectedAt.toLocaleString(
                            'en-GB',
                            {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            }
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Build Table Row
                    |--------------------------------------------------------------------------
                    */

                    rows += `

                        <tr>

                            <td>
                                #${event.id}
                            </td>

                            <td>
                                ${event.source_ip}
                            </td>

                            <td>
                                ${event.event_type}
                            </td>

                            <td>
                                ${severityBadge}
                            </td>

                            <td>
                                ${statusBadge}
                            </td>

                            <td>
                                ${formattedDate}
                            </td>

                        </tr>

                    `;

                });


                tableBody.innerHTML = rows;

            })


            .catch(error => {

                console.error(
                    'Recent events update failed:',
                    error
                );

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Update Last Updated Time
        |--------------------------------------------------------------------------
        */

        function updateLastUpdatedTime() {

            const now = new Date();


            document.getElementById('last-updated').textContent =
                now.toLocaleTimeString();

        }


        /*
|--------------------------------------------------------------------------
| Live Security Alert
|--------------------------------------------------------------------------
*/

let latestAlertId = null;

function updateSecurityAlert() {

    fetch('{{ route('dashboard.latest-alert') }}', {

        headers: {
            'Accept': 'application/json'
        }

    })

    .then(response => {

        if (!response.ok) {

            throw new Error(
                'Unable to retrieve latest security alert.'
            );

        }

        return response.json();

    })

    .then(data => {

        const alertBox =
            document.getElementById('security-alert');

        const alertMessage =
            document.getElementById('security-alert-message');

        const sourceIp =
            document.getElementById('alert-source-ip');

        const eventType =
            document.getElementById('alert-event-type');


        if (!data.event) {

            alertBox.classList.add('d-none');

            latestAlertId = null;

            return;

        }


        const event = data.event;


        sourceIp.textContent =
            event.source_ip;

        eventType.textContent =
            event.event_type;

        alertMessage.textContent =
            event.description ||
            'A high-severity security event has been detected.';


        /*
        |--------------------------------------------------------------------------
        | Show alert
        |--------------------------------------------------------------------------
        */

        alertBox.classList.remove('d-none');


        /*
        |--------------------------------------------------------------------------
        | Detect newly discovered alert
        |--------------------------------------------------------------------------
        */

        if (
            latestAlertId !== null &&
            latestAlertId !== event.id
        ) {

            console.log(
                'New security alert detected:',
                event.id
            );

        }

        latestAlertId = event.id;

    })

    .catch(error => {

        console.error(
            'Security alert update failed:',
            error
        );

    });

}

        /*
        |--------------------------------------------------------------------------
        | Update Dashboard
        |--------------------------------------------------------------------------
        */

        function updateDashboard() {

             updateDashboardStats();

             updateRecentEvents();

             updateSecurityAlert();

            updateLastUpdatedTime();

}

        /*
        |--------------------------------------------------------------------------
        | Initial Dashboard Update
        |--------------------------------------------------------------------------
        */

        updateDashboard();


        /*
        |--------------------------------------------------------------------------
        | Automatic Refresh
        |--------------------------------------------------------------------------
        |
        | Refresh the dashboard every 10 seconds.
        |
        */

        setInterval(
            updateDashboard,
            10000
        );

    </script>

@stop