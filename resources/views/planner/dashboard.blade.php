@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Planner Dashboard</h1>
<<<<<<< HEAD

    <p class="mb-6">Welkom, {{ Auth::user()->name }}! Jij kunt productieplannen maken en de voortgang van voertuigen volgen.</p>

    <!-- Overzicht van voertuigen -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Voertuigen in concept:</h2>
        <ul class="list-disc pl-5">
            @foreach ($vehicles as $vehicle)
                <li class="mb-2">
                    <strong>{{ $vehicle->name }}</strong> - Status: <span class="text-gray-500">{{ $vehicle->status }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Button voor het plannen van productie -->
    <div>
        <a href="{{ route('planner.index') }}" class="inline-block bg-blue-500 text-white px-6 py-2 rounded-md shadow-md hover:bg-blue-600">
            Bekijk voertuigen en plan productie
        </a>
    </div>
=======
    <p>Welkom, {{ Auth::user()->name }}! Jij kunt productieplannen maken.</p>
>>>>>>> parent of 9126aec (Planner controller etc.)
</div>
@endsection