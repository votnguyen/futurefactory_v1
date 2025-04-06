@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold mb-6">Mijn Voertuigen</h1>

    @if($vehicles->isEmpty())
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <p class="text-gray-600">U heeft nog geen voertuigen in productie.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($vehicles as $vehicle)
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 
                @if($vehicle->status == 'In productie') border-blue-500
                @elseif($vehicle->status == 'Gereed voor levering') border-green-500
                @elseif($vehicle->status == 'Geleverd') border-gray-500
                @else border-yellow-500 @endif">
                
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <h2 class="text-xl font-semibold">{{ $vehicle->name }}</h2>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($vehicle->status == 'In productie') bg-blue-100 text-blue-800
                            @elseif($vehicle->status == 'Gereed voor levering') bg-green-100 text-green-800
                            @elseif($vehicle->status == 'Geleverd') bg-gray-100 text-gray-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $vehicle->status }}
                        </span>
                    </div>

                    <div class="mt-4">
                        <h3 class="font-medium text-gray-700">Specificaties:</h3>
                        <ul class="mt-2 space-y-1">
                            <li><span class="font-medium">Totaalprijs:</span> €{{ number_format($vehicle->total_cost, 2) }}</li>
                            <li><span class="font-medium">Productietijd:</span> {{ $vehicle->total_assembly_time }} minuten</li>
                            <li><span class="font-medium">Aangemaakt:</span> {{ $vehicle->created_at->format('d-m-Y') }}</li>
                        </ul>
                    </div>

                    <div class="mt-4">
                        <h3 class="font-medium text-gray-700">Voortgang:</h3>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" 
                                style="width: @if($vehicle->status == 'Geleverd') 100%
                                        @elseif($vehicle->status == 'Gereed voor levering') 75%
                                        @elseif($vehicle->status == 'In productie') 50%
                                        @else 25% @endif">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3">
                    <h3 class="font-medium text-gray-700">Modules:</h3>
                    <ul class="mt-1 text-sm">
                        @foreach($vehicle->modules as $module)
                        <li class="flex items-center py-1">
                            <span class="w-2 h-2 mr-2 rounded-full 
                                @if($module->type == 'chassis') bg-red-500
                                @elseif($module->type == 'aandrijving') bg-blue-500
                                @elseif($module->type == 'wielen') bg-yellow-500
                                @elseif($module->type == 'stuur') bg-green-500
                                @else bg-purple-500 @endif">
                            </span>
                            {{ $module->name }} ({{ $module->type }})
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