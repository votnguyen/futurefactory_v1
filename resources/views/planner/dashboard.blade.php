@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Planner Dashboard</h1>

    <p class="mb-6">Welkom, {{ Auth::user()->name }}! Jij kunt productieplannen maken en de voortgang van voertuigen volgen.</p>

    <!-- Overzicht van voertuigen in concept -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Voertuigen in concept:</h2>
        @if($vehicles->isEmpty())
            <p class="text-gray-600">Er zijn geen voertuigen in concept.</p>
        @else
            <ul class="list-disc pl-5">
                @foreach ($vehicles as $vehicle)
                    <li class="mb-2">
                        <strong>{{ $vehicle->name }}</strong> - Status: <span class="text-gray-500">{{ $vehicle->status }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Actieknoppen -->
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('planner.index') }}" class="inline-block bg-blue-500 text-black px-6 py-2 rounded-md shadow-md hover:bg-blue-600">
            Bekijk voertuigen en plan productie
        </a>
        <a href="{{ route('planner.completed') }}" class="inline-block bg-green-500 text-black px-6 py-2 rounded-md shadow-md hover:bg-green-600">
            Overzicht voltooide voertuigen
        </a>
    </div>
</div>
@endsection
