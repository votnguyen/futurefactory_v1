@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold mb-6">Mijn Voertuigen</h1>

    @php
        $vehicles = auth()->user()->vehicles()->with('modules')->get();
    @endphp

    @if($vehicles->isEmpty())
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <p class="text-gray-600">U heeft nog geen voertuigen in productie.</p>
            <a href="{{ route('contact') }}" class="text-blue-500 hover:text-blue-700 mt-2 inline-block">
                Neem contact op voor een voertuigaanvraag
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($vehicles as $vehicle)
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 
                @switch($vehicle->status)
                    @case('in_productie') border-blue-500 @break
                    @case('gereed_voor_levering') border-green-500 @break
                    @case('geleverd') border-gray-500 @break
                    @default border-yellow-500
                @endswitch">
                
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <h2 class="text-xl font-semibold">{{ $vehicle->name }}</h2>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @switch($vehicle->status)
                                @case('in_productie') bg-blue-100 text-blue-800 @break
                                @case('gereed_voor_levering') bg-green-100 text-green-800 @break
                                @case('geleverd') bg-gray-100 text-gray-800 @break
                                @default bg-yellow-100 text-yellow-800
                            @endswitch">
                            {{ ucfirst(str_replace('_', ' ', $vehicle->status)) }}
                        </span>
                    </div>

                    <div class="mt-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Totaalprijs:</span>
                            <span>€{{ number_format($vehicle->total_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Productietijd:</span>
                            <span>{{ $vehicle->total_assembly_time }} minuten</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Aangemaakt:</span>
                            <span>{{ $vehicle->created_at->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h3 class="font-medium text-gray-700 mb-2">Voortgang:</h3>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" 
                                style="width: 
                                @switch($vehicle->status)
                                    @case('geleverd') 100% @break
                                    @case('gereed_voor_levering') 75% @break
                                    @case('in_productie') 50% @break
                                    @default 25%
                                @endswitch">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                    <h3 class="font-medium text-gray-700 mb-2">Modules:</h3>
                    <ul class="space-y-2">
                        @foreach($vehicle->modules->sortBy('pivot.assembly_order') as $module)
                        <li class="flex items-start">
                            <span class="inline-block w-2 h-2 mt-2 mr-2 rounded-full 
                                @switch($module->type)
                                    @case('chassis') bg-red-500 @break
                                    @case('aandrijving') bg-blue-500 @break
                                    @case('wielen') bg-yellow-500 @break
                                    @case('stuur') bg-green-500 @break
                                    @default bg-purple-500
                                @endswitch">
                            </span>
                            <div>
                                <p class="font-medium">{{ $module->name }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($module->type) }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection