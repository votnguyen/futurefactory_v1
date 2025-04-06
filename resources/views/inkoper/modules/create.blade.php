// resources/views/inkoper/modules/create.blade.php
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Nieuwe Module</h1>

    <form method="POST" action="{{ route('inkoper.modules.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Algemene informatie -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Algemene Informatie</h2>
                
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 mb-2">Naam:</label>
                    <input type="text" id="name" name="name" required
                           class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-gray-700 mb-2">Type:</label>
                    <select id="type" name="type" required
                            class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                        @foreach($moduleTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="cost" class="block text-gray-700 mb-2">Kosten (€):</label>
                    <input type="number" step="0.01" id="cost" name="cost" required
                           class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="assembly_time" class="block text-gray-700 mb-2">Montagetijd (blokken):</label>
                    <input type="number" id="assembly_time" name="assembly_time" min="1" required
                           class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-gray-700 mb-2">Afbeelding:</label>
                    <input type="file" id="image" name="image"
                           class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Type-specifieke velden -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Specificaties</h2>
                
                <!-- Chassis velden -->
                <div id="chassis-specs" class="module-specs">
                    <div class="mb-4">
                        <label for="wheel_count" class="block text-gray-700 mb-2">Aantal wielen:</label>
                        <input type="number" id="wheel_count" name="specifications[wheel_count]" min="2" step="2"
                               class="w-full p-2 border rounded">
                    </div>

                    <div class="mb-4">
                        <label for="vehicle_type" class="block text-gray-700 mb-2">Voertuig type:</label>
                        <input type="text" id="vehicle_type" name="specifications[vehicle_type]"
                               class="w-full p-2 border rounded">
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label for="length" class="block text-gray-700 mb-2">Lengte (cm):</label>
                            <input type="number" id="length" name="specifications[length]"
                                   class="w-full p-2 border rounded">
                        </div>
                        <div>
                            <label for="width" class="block text-gray-700 mb-2">Breedte (cm):</label>
                            <input type="number" id="width" name="specifications[width]"
                                   class="w-full p-2 border rounded">
                        </div>
                        <div>
                            <label for="height" class="block text-gray-700 mb-2">Hoogte (cm):</label>
                            <input type="number" id="height" name="specifications[height]"
                                   class="w-full p-2 border rounded">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="compatible_wheels" class="block text-gray-700 mb-2">Compatibele wielen (komma gescheiden):</label>
                        <input type="text" id="compatible_wheels" name="specifications[compatible_wheels]"
                               class="w-full p-2 border rounded">
                    </div>
                </div>

                <!-- Andere type-specifieke velden hier... -->
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Module Opslaan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Toon/verberg type-specifieke velden
    document.getElementById('type').addEventListener('change', function() {
        const type = this.value;
        document.querySelectorAll('.module-specs').forEach(el => {
            el.style.display = 'none';
        });
        document.getElementById(`${type}-specs`).style.display = 'block';
    });

    // Initialiseer bij het laden
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('type').dispatchEvent(new Event('change'));
    });
</script>
@endpush
@endsection