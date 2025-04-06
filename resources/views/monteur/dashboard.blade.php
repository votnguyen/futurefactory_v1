@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Monteur Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-black p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Snelle Acties</h2>
            <a href="{{ route('monteur.assembly.create') }}" class="block bg-blue-500 hover:bg-blue-700 text-black font-bold py-3 px-4 rounded mb-3 text-center">
                Nieuw Voertuig Samenstellen
            </a>
            <a href="{{ route('monteur.assembly.index') }}" class="block bg-green-500 hover:bg-green-700 text-black font-bold py-3 px-4 rounded text-center">
                Bekijk Concept Voertuigen
            </a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Recent Samengestelde Voertuigen</h2>
            @if($recentVehicles->count() > 0)
                <ul class="space-y-3">
                    @foreach($recentVehicles as $vehicle)
                    <li class="border-b pb-2">
                        <a href="{{ route('monteur.assembly.show', $vehicle) }}" class="text-blue-500 hover:text-blue-700">
                            {{ $vehicle->name }} ({{ $vehicle->customer->name }})
                        </a>
                        <div class="text-sm text-gray-600">
                            €{{ number_format($vehicle->total_cost, 2) }} | {{ $vehicle->total_assembly_time }} uur
                        </div>
                    </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">Nog geen voertuigen samengesteld</p>
            @endif
        </div>
    </div>
</div>
@endsection