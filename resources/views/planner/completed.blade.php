@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Overzicht Voertuigen</h1>

    @php
        $statusColors = [
            'concept' => 'bg-gray-100 text-gray-800',
            'in_productie' => 'bg-yellow-100 text-yellow-800',
            'voltooid' => 'bg-green-100 text-green-800',
        ];
    @endphp

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Voertuig</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Klant</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Modules</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Voortgang</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Verwachte oplevering</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($vehicles as $vehicle)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $vehicle->name }}</div>
                        <div class="text-xs text-gray-500">Type: {{ $vehicle->type }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $vehicle->customer->name ?? 'Onbekend' }}</div>
                        <div class="text-xs text-gray-500">{{ $vehicle->customer->email ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs font-semibold rounded-full 
                            {{ $vehicle->modules->count() > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $vehicle->modules->count() }} modules
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs font-semibold rounded-full 
                            {{ $statusColors[$vehicle->completion_status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($vehicle->completion_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2.5 mr-2">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $vehicle->completion_percentage }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600">{{ $vehicle->completion_percentage }}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        @if($vehicle->expected_delivery)
                            {{ $vehicle->expected_delivery->format('d-m-Y H:i') }}
                            <div class="text-xs text-gray-400">
                                ({{ $vehicle->expected_delivery->diffForHumans() }})
                            </div>
                        @else
                            <span class="text-gray-400">Nog niet ingepland</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Geen voertuigen gevonden.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
