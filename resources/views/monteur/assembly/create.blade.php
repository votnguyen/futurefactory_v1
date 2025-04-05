@extends('layouts.app')

@section('content')

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold mb-6">Nieuw Voertuig Samenstellen</h1>

    <form method="POST" action="{{ route('monteur.assembly.store') }}" class="bg-white p-6 rounded-lg shadow-md">
        @csrf

        <!-- Basis informatie -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2" for="name">Voertuignaam:</label>
                <input type="text" name="name" id="name" required 
                    class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2" for="customer_id">Klant:</label>
                <select name="customer_id" id="customer_id" required class="w-full p-2 border rounded">
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Module selectie -->
        <div class="space-y-6">
            @foreach(['chassis', 'aandrijving', 'wielen', 'stuur', 'stoelen'] as $type)
                <div class="border p-4 rounded-lg bg-gray-50">
                    <h2 class="text-xl font-semibold mb-4 capitalize">{{ $type }}</h2>

                    @if(!empty($modules[$type]) && count($modules[$type]))
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($modules[$type] as $module)
                                <label class="block border p-4 rounded-lg hover:bg-white transition-colors shadow-sm cursor-pointer">
                                    <div class="flex items-start gap-4">
                                        <input 
                                            type="radio" 
                                            name="{{ $type }}" 
                                            value="{{ $module->id }}"
                                            class="mt-1"
                                            @if($type !== 'stoelen') required @endif
                                            aria-label="{{ $module->name }}">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-lg">{{ $module->name }}</h3>
                                            
                                            <div class="text-sm text-gray-700 mt-1 space-y-1">
                                                <p>Kosten: €{{ number_format($module->cost, 2) }}</p>
                                                <p>Montagetijd: {{ $module->assembly_time }} uur</p>
                                            </div>

                                            @if($module->specifications)
                                                <div class="text-xs text-gray-600 mt-2 space-y-1">
                                                    @foreach($module->specifications as $key => $value)
                                                        <p>
                                                            <span class="font-medium">{{ ucfirst($key) }}:</span> 
                                                            {{ is_array($value) ? implode(', ', $value) : $value }}
                                                        </p>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($module->image_path)
                                            <img 
                                            src="{{ asset('storage/modules/' . $module->image_path) }}" 
                                              alt="{{ $module->name }}" 
                                             class="mt-3 h-48 w-48 object-contain rounded border transition-all duration-300 hover:scale-105">
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">Geen modules beschikbaar voor {{ $type }}.</p>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-black font-bold py-2 px-6 rounded transition duration-200">
                Voertuig Opslaan
            </button>
        </div>
    </form>
</div>

@endsection
