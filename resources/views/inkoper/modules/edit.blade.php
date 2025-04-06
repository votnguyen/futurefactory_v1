@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Module Bewerken</h1>

    <form method="POST" action="{{ route('inkoper.modules.update', $module) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Algemene informatie -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Algemene Informatie</h2>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 mb-2">Naam:</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $module->name) }}" required
                           class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-gray-700 mb-2">Type:</label>
                    <select id="type" name="type" required
                            class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                        @foreach($moduleTypes as $key => $label)
                            <option value="{{ $key }}" @if($module->type === $key) selected @endif>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="cost" class="block text-gray-700 mb-2">Kosten (€):</label>
                    <input type="number" step="0.01" id="cost" name="cost" value="{{ old('cost', $module->cost) }}" required
                           class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="assembly_time" class="block text-gray-700 mb-2">Montagetijd (blokken):</label>
                    <input type="number" id="assembly_time" name="assembly_time" min="1" value="{{ old('assembly_time', $module->assembly_time) }}" required
                           class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-gray-700 mb-2">Afbeelding:</label>
                    <input type="file" id="image" name="image"
                           class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">

                    @if($module->image_path)
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">Huidige afbeelding:</p>
                            <img src="{{ asset('storage/' . $module->image_path) }}" alt="{{ $module->name }}" class="h-32 w-32 rounded border border-gray-300 object-cover mt-2">
                        </div>
                    @endif
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
                               value="{{ old('specifications.wheel_count', $module->specifications['wheel_count'] ?? '') }}"
                               class="w-full p-2 border rounded">
                    </div>

                    <div class="mb-4">
                        <label for="vehicle_type" class="block text-gray-700 mb-2">Voertuig type:</label>
                        <input type="text" id="vehicle_type" name="specifications[vehicle_type]"
                               value="{{ old('specifications.vehicle_type', $module->specifications['vehicle_type'] ?? '') }}"
                               class="w-full p-2 border rounded">
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label for="length" class="block text-gray-700 mb-2">Lengte (cm):</label>
                            <input type="number" id="length" name="specifications[length]"
                                   value="{{ old('specifications.length', $module->specifications['length'] ?? '') }}"
                                   class="w-full p-2 border rounded">
                        </div>
                        <div>
                            <label for="width" class="block text-gray-700 mb-2">Breedte (cm):</label>
                            <input type="number" id="width" name="specifications[width]"
                                   value="{{ old('specifications.width', $module->specifications['width'] ?? '') }}"
                                   class="w-full p-2 border rounded">
                        </div>
                        <div>
                            <label for="height" class="block text-gray-700 mb-2">Hoogte (cm):</label>
                            <input type="number" id="height" name="specifications[height]"
                                   value="{{ old('specifications.height', $module->specifications['height'] ?? '') }}"
                                   class="w-full p-2 border rounded">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="compatible_wheels" class="block text-gray-700 mb-2">Compatibele wielen:</label>
                        <input type="text" id="compatible_wheels" name="specifications[compatible_wheels]"
                               value="{{ old('specifications.compatible_wheels', $module->specifications['compatible_wheels'] ?? '') }}"
                               class="w-full p-2 border rounded">
                    </div>
                </div>

                <!-- Andere type-specifieke blokken kunnen hier komen -->
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Wijzigingen Opslaan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Toon/verberg type-specifieke velden
    document.getElementById('type').addEventListener('change', function() {
        const type = this.value;
        document.querySelectorAll('.module-specs').forEach(el => el.style.display = 'none');
        const selectedSpecs = document.getElementById(`${type}-specs`);
        if (selectedSpecs) selectedSpecs.style.display = 'block';
    });

    // Initialiseer bij laden
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('type').dispatchEvent(new Event('change'));
    });
</script>
@endpush
@endsection
