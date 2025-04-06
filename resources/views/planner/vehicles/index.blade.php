@foreach($vehicles as $vehicle)
    <div class="p-4 border rounded-lg mb-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="font-medium">{{ $vehicle->name }}</h3>
                <p class="text-sm text-gray-600">Klant: {{ $vehicle->customer->name }}</p>
            </div>
            <form action="{{ route('vehicles.status.update', $vehicle) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()" 
                    class="text-sm rounded border border-gray-300 focus:border-blue-500">
                    @foreach(App\Models\Vehicle::$statuses as $key => $label)
                        <option value="{{ $key }}" {{ $vehicle->status == $label ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
@endforeach