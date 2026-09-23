<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    IDS Dashboard
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Network Intrusion Detection and Monitoring System
                </p>
            </div>

        </div>

    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Dashboard Introduction --}}
            <div class="mb-8">

                <h3 class="text-2xl font-bold text-gray-800">
                    Security Overview
                </h3>

                <p class="text-gray-500 mt-1">
                    Monitor detected network security events and their current status.
                </p>

            </div>


            {{-- Main Statistics --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                {{-- Total Events --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <p class="text-sm font-medium text-gray-500">
                            Total Events
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-800">
                            {{ $totalEvents }}
                        </p>

                        <p class="mt-2 text-sm text-gray-500">
                            Detected security events
                        </p>

                    </div>

                </div>


                {{-- Unresolved --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <p class="text-sm font-medium text-gray-500">
                            Unresolved
                        </p>

                        <p class="mt-2 text-3xl font-bold text-red-600">
                            {{ $unresolvedEvents }}
                        </p>

                        <p class="mt-2 text-sm text-gray-500">
                            Events requiring attention
                        </p>

                    </div>

                </div>


                {{-- Investigating --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <p class="text-sm font-medium text-gray-500">
                            Investigating
                        </p>

                        <p class="mt-2 text-3xl font-bold text-blue-600">
                            {{ $investigatingEvents }}
                        </p>

                        <p class="mt-2 text-sm text-gray-500">
                            Events under investigation
                        </p>

                    </div>

                </div>


                {{-- Resolved --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <p class="text-sm font-medium text-gray-500">
                            Resolved
                        </p>

                        <p class="mt-2 text-3xl font-bold text-green-600">
                            {{ $resolvedEvents }}
                        </p>

                        <p class="mt-2 text-sm text-gray-500">
                            Events successfully handled
                        </p>

                    </div>

                </div>

            </div>


            {{-- Severity Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                {{-- High Severity --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    High Severity
                                </p>

                                <p class="mt-2 text-3xl font-bold text-red-600">
                                    {{ $highSeverity }}
                                </p>
                            </div>

                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                HIGH
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Medium Severity --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Medium Severity
                                </p>

                                <p class="mt-2 text-3xl font-bold text-orange-600">
                                    {{ $mediumSeverity }}
                                </p>
                            </div>

                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                MEDIUM
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Low Severity --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Low Severity
                                </p>

                                <p class="mt-2 text-3xl font-bold text-green-600">
                                    {{ $lowSeverity }}
                                </p>
                            </div>

                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                LOW
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Recent Events --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="flex items-center justify-between mb-6">

                        <div>

                            <h3 class="text-lg font-semibold text-gray-800">
                                Recent Security Events
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Latest detected network security events.
                            </p>

                        </div>

                        <a
                            href="{{ route('security-events.index') }}"
                            class="px-4 py-2 bg-gray-800 text-white text-sm font-semibold rounded-md hover:bg-gray-700"
                        >
                            View All Events
                        </a>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        ID
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Source IP
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Event Type
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Severity
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Status
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Detected At
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="bg-white divide-y divide-gray-200">

                                @forelse ($recentEvents as $event)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $event->id }}
                                        </td>

                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $event->source_ip }}
                                        </td>

                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $event->event_type }}
                                        </td>

                                        <td class="px-4 py-3">

                                            @if ($event->severity === 'high')

                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    High
                                                </span>

                                            @elseif ($event->severity === 'medium')

                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                                    Medium
                                                </span>

                                            @else

                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Low
                                                </span>

                                            @endif

                                        </td>

                                        <td class="px-4 py-3">

                                            @if ($event->status === 'resolved')

                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Resolved
                                                </span>

                                            @elseif ($event->status === 'investigating')

                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Investigating
                                                </span>

                                            @else

                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    Unresolved
                                                </span>

                                            @endif

                                        </td>

                                        <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                            {{ $event->detected_at?->format('d M Y, H:i:s') ?? 'N/A' }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="6"
                                            class="px-4 py-8 text-center text-sm text-gray-500"
                                        >
                                            No security events have been detected yet.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>