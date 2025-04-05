@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold mb-6">Voertuigassemblage</h1>
    
    <div class="mb-4">
        <a href="{{ route('monteur.assembly.create') }}" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
            Nieuw Voertuig Samenstellen
        </a>
    </div>

    <div class="bg-black shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-4 text-left">Naam</th>
                    <th class="py-3 px-4 text-left">Klant</th>
                    <th class="py-3 px-4 text-left">Modules</th>
                    <th class="py-3 px-4 text-left">Totale Kosten</th>
                    <th class="py-3 px-4 text-left">Acties</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicles as $vehicle)
                <tr class="border-t">
                    <td class="py-3 px-4">{{ $vehicle->name }}</td>
                    <td class="py-3 px-4">{{ $vehicle->customer->name }}</td>
                    <td class="py-3 px-4">{{ $vehicle->modules->count() }}</td>
                    <td class="py-3 px-4">€{{ number_format($vehicle->total_cost, 2) }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('monteur.assembly.show', $vehicle) }}" class="text-blue-500 hover:text-blue-700">Bekijken</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection