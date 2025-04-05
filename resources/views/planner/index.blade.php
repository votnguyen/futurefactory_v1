@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Productieplanning</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Kalender -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div id="calendar" class="w-full"></div>
        </div>

        <!-- Voertuig Selectie -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Plan nieuw voertuig</h2>
            
            <form id="planningForm">
                <div class="mb-4">
                    <label class="block font-medium mb-2">Selecteer voertuig:</label>
                    <select name="vehicle_id" class="w-full p-2 border rounded" required>
                        @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}">{{ $vehicle->name }} ({{ $vehicle->customer->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-2">Selecteer modules:</label>
                    <div class="space-y-2" id="moduleList">
                        @foreach($vehicles as $vehicle)
                            @foreach($vehicle->modules as $module)
                            <label class="flex items-center p-2 border rounded">
                                <input type="checkbox" 
                                    name="modules[]" 
                                    value="{{ $module->id }}"
                                    data-vehicle="{{ $vehicle->id }}"
                                    class="vehicle-module hidden">
                                <span class="ml-2">{{ $module->name }} ({{ $module->type }})</span>
                            </label>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <input type="hidden" name="start_time" id="selectedSlot">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Inplannen
                </button>
            </form>
        </div>
    </div>
</div>

<!-- FullCalendar CSS & JS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/nl.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'nl',
        initialView: 'timeGridWeek',
        slotDuration: '02:00:00',
        slotMinTime: '08:00:00',
        slotMaxTime: '18:00:00',
        events: '/api/schedules',
        selectable: true,
        select: function(info) {
            document.getElementById('selectedSlot').value = info.startStr;
            document.querySelectorAll('.fc-highlight').forEach(el => el.classList.remove('fc-highlight'));
            info.jsEvent.target.classList.add('fc-highlight');
        }
    });
    calendar.render();

    // Update modules bij voertuigselectie
    document.querySelector('[name="vehicle_id"]').addEventListener('change', function() {
        const vehicleId = this.value;
        document.querySelectorAll('.vehicle-module').forEach(el => {
            el.parentElement.style.display = el.dataset.vehicle === vehicleId ? 'flex' : 'none';
        });
    });
});
</script>
@endsection