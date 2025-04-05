@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold mb-6">Nieuw Voertuig Samenstellen</h1>

    <form method="POST" action="{{ route('monteur.assembly.store') }}" class="bg-white p-6 rounded-lg shadow-md">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Voertuignaam:</label>
            <input type="text" id="name" name="name" required class="w-full px-3 py-2 border rounded-lg">
        </div>

        <div class="mb-4">
            <label for="customer_id" class="block text-gray-700 font-bold mb-2">Klant:</label>
            <select id="customer_id" name="customer_id" required class="w-full px-3 py-2 border rounded-lg">
                @foreach(App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'klant'))->get() as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <h2 class="text-xl font-semibold mb-4">Selecteer Modules</h2>

        @foreach($modules as $type => $typeModules)
        <div class="mb-6 p-4 border rounded-lg">
            <h3 class="text-lg font-medium mb-3 capitalize">{{ $type }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($typeModules as $module)
                <div class="border p-3 rounded-lg hover:bg-gray-50">
                    <label class="flex items-start">
                        <input type="checkbox" name="modules[]" value="{{ $module->id }}" 
                               class="mt-1 mr-2">
                        <div>
                            <h4 class="font-medium">{{ $module->name }}</h4>
                            <p class="text-sm text-gray-600">€{{ number_format($module->cost, 2) }}</p>
                            <p class="text-sm text-gray-600">{{ $module->assembly_time }} min</p>
                            @if($module->image_path)
                            <img src="{{ asset('storage/' . $module->image_path) }}" alt="{{ $module->name }}" class="mt-2 h-20">
                            @endif
                        </div>
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <button type="submit" class="bg-green-500 hover:bg-green-700 text-black font-bold py-2 px-4 rounded">
            Voertuig Opslaan
        </button>
    </form>
</div>
@endsection