<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Security Event Details
            </h2>

            <a
                href="{{ route('security-events.index') }}"
                class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700"
            >
                Back to Events
            </a>
        </div>
    </x-slot>

    <div class="py-12">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6">

                    {{-- Page heading --}}
                    <div class="mb-6">

                        <h3 class="text-lg font-semibold text-gray-800">
                            Event #{{ $networkEvent->id }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Detailed information about the detected network security event.
                        </p>

                    </div>


                    {{-- Event information --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Source IP --}}
                        <div class="border rounded-lg p-4">
                            <p class="text-sm text-gray-500">
                                Source IP Address
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $networkEvent->source_ip }}
                            </p>
                        </div>


                        {{-- Destination IP --}}
                        <div class="border rounded-lg p-4">
                            <p class="text-sm text-gray-500">
                                Destination IP Address
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $networkEvent->destination_ip ?? 'N/A' }}
                            </p>
                        </div>


                        {{-- Source Port --}}
                        <div class="border rounded-lg p-4">
                            <p class="text-sm text-gray-500">
                                Source Port
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $networkEvent->source_port ?? 'N/A' }}
                            </p>
                        </div>


                        {{-- Destination Port --}}
                        <div class="border rounded-lg p-4">
                            <p class="text-sm text-gray-500">
                                Destination Port
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $networkEvent->destination_port ?? 'N/A' }}
                            </p>
                        </div>


                        {{-- Protocol --}}
                        <div class="border rounded-lg p-4">
                            <p class="text-sm text-gray-500">
                                Protocol
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $networkEvent->protocol ?? 'N/A' }}
                            </p>
                        </div>


                        {{-- Event Type --}}
                        <div class="border rounded-lg p-4">
                            <p class="text-sm text-gray-500">
                                Event Type
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $networkEvent->event_type }}
                            </p>
                        </div>


                        {{-- Severity --}}
                        <div class="border rounded-lg p-4">
                            <p class="text-sm text-gray-500">
                                Severity
                            </p>

                            <div class="mt-2">

                                @if ($networkEvent->severity === 'high')

                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        High
                                    </span>

                                @elseif ($networkEvent->severity === 'medium')

                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                        Medium
                                    </span>

                                @elseif ($networkEvent->severity === 'low')

                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Low
                                    </span>

                                @else

                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($networkEvent->severity) }}
                                    </span>

                                @endif

                            </div>
                        </div>


                        {{-- Status --}}
                        <div class="border rounded-lg p-4">
                            <p class="text-sm text-gray-500">
                                Status
                            </p>

                            <div class="mt-2">

                                @if ($networkEvent->status === 'resolved')

                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Resolved
                                    </span>

                                @elseif ($networkEvent->status === 'investigating')

                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Investigating
                                    </span>

                                @else

                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Unresolved
                                    </span>

                                @endif

                            </div>
                        </div>

                    </div>


                    {{-- Description --}}
                    <div class="mt-6 border rounded-lg p-4">

                        <p class="text-sm text-gray-500">
                            Description
                        </p>

                        <p class="mt-2 text-gray-800">
                            {{ $networkEvent->description ?? 'No description available.' }}
                        </p>

                    </div>


                    {{-- Detection time --}}
                    <div class="mt-6 border rounded-lg p-4">

                        <p class="text-sm text-gray-500">
                            Detected At
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $networkEvent->detected_at?->format('d M Y, H:i:s') ?? 'N/A' }}
                        </p>

                    </div>


                    {{-- Update status --}}
                    <div class="mt-6 border rounded-lg p-4">

                        <h4 class="font-semibold text-gray-800">
                            Update Event Status
                        </h4>

                        <form
                            action="{{ route('security-events.update', $networkEvent) }}"
                            method="POST"
                            class="mt-4 flex flex-col sm:flex-row gap-3"
                        >

                            @csrf

                            @method('PATCH')

                            <select
                                name="status"
                                class="border-gray-300 rounded-md text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                                <option
                                    value="unresolved"
                                    {{ $networkEvent->status === 'unresolved' ? 'selected' : '' }}
                                >
                                    Unresolved
                                </option>

                                <option
                                    value="investigating"
                                    {{ $networkEvent->status === 'investigating' ? 'selected' : '' }}
                                >
                                    Investigating
                                </option>

                                <option
                                    value="resolved"
                                    {{ $networkEvent->status === 'resolved' ? 'selected' : '' }}
                                >
                                    Resolved
                                </option>

                            </select>

                            <button
                                type="submit"
                                class="px-4 py-2 bg-gray-800 text-white text-sm font-semibold rounded-md hover:bg-gray-700"
                            >
                                Update Status
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>