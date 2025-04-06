@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-7xl px-6 py-8">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-10">🔧 Monteur Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        {{-- 🔄 Snelle Acties --}}
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-black p-8 rounded-2xl shadow-xl">
            <h2 class="text-2xl font-semibold mb-6">🚀 Snelle Acties</h2>
            
            <div class="space-y-4">
                <a href="{{ route('monteur.assembly.create') }}"
                   class="flex items-center justify-between bg-blue-600 hover:bg-blue-700 transition-all duration-200 text-black font-semibold py-4 px-6 rounded-xl shadow-md">
                    <span>Nieuw Voertuig Samenstellen</span>
                    <span>➕</span>
                </a>

                <a href="{{ route('monteur.assembly.index') }}"
                   class="flex items-center justify-between bg-green-600 hover:bg-green-700 transition-all duration-200 text-black font-semibold py-4 px-6 rounded-xl shadow-md">
                    <span>Bekijk Concept Voertuigen</span>
                    <span>📄</span>
                </a>
            </div>
        </div>

       {{-- ✅ Recent Samengestelde Voertuigen --}}
<div class="bg-white p-12 rounded-2xl shadow-xl border border-gray-200 mt-[100px]">
    <h2 class="text-3xl font-semibold text-gray-800 mb-8">🧩 Laatste Samengestelde Voertuigen</h2>

    @if($recentVehicles->count())
        <ul class="divide-y divide-gray-100 space-y-4">
            @foreach($recentVehicles as $vehicle)
                <li class="pt-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <a href="{{ route('monteur.assembly.show', $vehicle) }}"
                               class="text-lg font-medium text-blue-600 hover:text-blue-800">
                                {{ $vehicle->name }} 
                                <span class="text-gray-500 text-sm">({{ $vehicle->customer->name }})</span>
                            </a>
                            <div class="text-sm text-gray-500 mt-2">
                                💶 €{{ number_format($vehicle->total_cost, 2) }} &nbsp; • &nbsp; 🕒 {{ $vehicle->total_assembly_time }} uur
                            </div>
                        </div>
                        <a href="{{ route('monteur.assembly.show', $vehicle) }}"
                           class="text-sm text-blue-500 hover:underline">
                            Details →
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="flex flex-col items-center justify-center text-center text-gray-400 mt-10">
            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M9 17v-6h6v6m2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h3l2-2h2l2 2h3a2 2 0 012 2v12a2 2 0 01-2 2z" />
            </svg>
            <p class="text-lg font-medium">Nog geen voertuigen samengesteld</p>
            <p class="text-sm">Begin met een nieuw voertuig aanmaken via de snelle acties.</p>
        </div>
    @endif
</div>

@endsection
