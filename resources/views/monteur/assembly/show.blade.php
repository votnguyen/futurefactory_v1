@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold mb-6">Voertuig Details: {{ $vehicle->name }}</h1>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <h3 class="font-bold">Klant:</h3>
                <p>{{ $vehicle->customer->name }}</p>
            </div>
            <div>
                <h3 class="font-bold">Totale Kosten:</h3>
                <p>€{{ number_format($vehicle->total_cost, 2) }}</p>
            </div>
            <div>
                <h3 class="font-bold">Totale Montagetijd:</h3>
                <p>{{ $vehicle->total_assembly_time }} minuten</p>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-4">Modules</h2>
        <div class="space-y-4">
            @foreach($vehicle->modules as $module)
            <div class="border p-4 rounded-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-medium">{{ $module->name }}</h3>
                        <p class="text-gray-600 capitalize">{{ $module->type }}</p>
                    </div>
                    <div class="text-right">
                        <p>€{{ number_format($module->cost, 2) }}</p>
                        <p>{{ $module->assembly_time }} min</p>
                    </div>
                </div>
                @if($module->image_path)
                <img src="{{ asset('storage/' . $module->image_path) }}" alt="{{ $module->name }}" class="mt-2 h-32">
                @endif
                <div class="mt-2">
                    <h4 class="font-medium">Specificaties:</h4>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($module->specifications as $key => $value)
                            @if(is_array($value))
                                <li>{{ $key }}: {{ json_encode($value) }}</li>
                            @else
                                <li>{{ $key }}: {{ $value }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <a href="{{ route('monteur.assembly.index') }}" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
        Terug naar overzicht
    </a>
</div>
@endsection