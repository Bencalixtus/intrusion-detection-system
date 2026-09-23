<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Security Events
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-md">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="mb-6">

                        <h3 class="text-lg font-semibold text-gray-800">
                            Network Security Events
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Monitor and manage detected network security events.
                        </p>

                    </div>

                    <div class="mb-6">

                        <p class="text-sm text-gray-600">
                            Total Events:
                            <span class="font-semibold text-gray-800">
                                {{ $events->total() }}
                            </span>
                        </p>

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
                                        Destination IP
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Protocol
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

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Action
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">

                                @forelse ($events as $event)

                                    <tr class="hover:bg-gray-50">

                                        {{-- ID --}}
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $event->id }}
                                        </td>

                                        {{-- Source IP --}}
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $event->source_ip }}
                                        </td>

                                        {{-- Destination IP --}}
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $event->destination_ip ?? 'N/A' }}
                                        </td>

                                        {{-- Protocol --}}
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $event->protocol ?? 'N/A' }}
                                        </td>

                                        {{-- Event Type --}}
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $event->event_type }}
                                        </td>

                                        {{-- Severity --}}
                                        <td class="px-4 py-3">

                                            @if ($event->severity === 'high')

                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    High
                                                </span>

                                            @elseif ($event->severity === 'medium')

                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                                    Medium
                                                </span>

                                            @elseif ($event->severity === 'low')

                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Low
                                                </span>

                                            @else

                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ ucfirst($event->severity) }}
                                                </span>

                                            @endif

                                        </td>

                                        {{-- Status --}}
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

                                        {{-- Detected At --}}
                                        <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                            {{ $event->detected_at?->format('d M Y, H:i:s') ?? 'N/A' }}
                                        </td>

                                        {{-- Action --}}
<td class="px-4 py-3">

    <a
        href="/security-events/{{ $event->id }}"
        style="display: inline-block; padding: 8px 14px; background: #4f46e5; color: white; text-decoration: none; border-radius: 6px; margin-bottom: 10px;"
    >
        VIEW EVENT
    </a>

    <form
        action="/security-events/{{ $event->id }}"
        method="POST"
    >

        @csrf
        @method('PATCH')

        <div class="flex items-center gap-2">

            <select
                name="status"
                class="border-gray-300 rounded-md text-sm"
            >
                <option value="unresolved" {{ $event->status === 'unresolved' ? 'selected' : '' }}>
                    Unresolved
                </option>

                <option value="investigating" {{ $event->status === 'investigating' ? 'selected' : '' }}>
                    Investigating
                </option>

                <option value="resolved" {{ $event->status === 'resolved' ? 'selected' : '' }}>
                    Resolved
                </option>
            </select>

            <button
                type="submit"
                class="px-3 py-2 bg-gray-800 text-white text-xs font-semibold rounded-md"
            >
                Update
            </button>

        </div>

    </form>

</td>
                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="9"
                                            class="px-4 py-8 text-center text-sm text-gray-500"
                                        >
                                            No security events have been detected yet.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    {{-- Pagination --}}
                    @if ($events->hasPages())

                        <div class="mt-6">
                            {{ $events->links() }}
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>