@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Productieplanning</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Kalender -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div id="calendar" class="w-full"></div>
            <div id="selectedTimeDisplay" class="mt-2 text-gray-700"></div>
        </div>

        <!-- Voertuigplanning -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Plan nieuw voertuig</h2>

            <div id="formMessages" class="mb-4 hidden">
                <div id="formSuccess" class="text-green-600 font-semibold"></div>
                <div id="formErrors" class="text-red-600 font-semibold"></div>
            </div>

            <form id="planningForm" method="POST" action="{{ route('planner.store') }}">
                @csrf
                <input type="hidden" name="start_time" id="selectedSlot">

                <!-- Voertuigselectie -->
                <div class="mb-4">
                    <label class="block font-medium mb-2">Selecteer voertuig:</label>
                    <select name="vehicle_id" class="w-full p-2 border rounded" required>
                        <option value="">-- Kies een voertuig --</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->name }} ({{ $vehicle->customer->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Modules -->
                <div class="mb-4">
                    <label class="block font-medium mb-2">Selecteer modules:</label>
                    <div id="moduleList" class="space-y-2">
                        @foreach($vehicles as $vehicle)
                            <div class="vehicle-modules" id="vehicle-{{ $vehicle->id }}" style="{{ old('vehicle_id') == $vehicle->id ? 'display:block;' : 'display:none;' }}">
                                @foreach($vehicle->modules as $module)
                                    <label class="flex items-center p-2 border rounded hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" 
                                            name="modules[]" 
                                            value="{{ $module->id }}" 
                                            class="vehicle-module mr-2"
                                            {{ in_array($module->id, old('modules', [])) ? 'checked' : '' }}>
                                        <span class="flex-1">{{ $module->name }} ({{ $module->type }})</span>
                                        <span class="text-sm text-gray-500">{{ $module->assembly_time }} uur</span>
                                    </label>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded w-full">
                    Inplannen
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Kalender scripts -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/nl.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const selectedTimeDisplay = document.getElementById('selectedTimeDisplay');
    const formMessages = document.getElementById('formMessages');
    const formSuccess = document.getElementById('formSuccess');
    const formErrors = document.getElementById('formErrors');
    const planningForm = document.getElementById('planningForm');

    let tempEvent = null;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'nl',
        timeZone: 'local',
        initialView: 'timeGridWeek',
        slotDuration: '02:00:00',
        slotMinTime: '08:00:00',
        slotMaxTime: '18:00:00',
        selectable: true,
        editable: false,
        events: function(info, successCallback, failureCallback) {
            // Haal de geplande evenementen op via een AJAX-aanroep
            fetch('/get-scheduled-events')
                .then(response => response.json())
                .then(data => successCallback(data.events))
                .catch(error => failureCallback(error));
        },

        select(info) {
            if (tempEvent) {
                calendar.getEventById('temp')?.remove();
            }

            tempEvent = calendar.addEvent({
                id: 'temp',
                title: 'Geselecteerd tijdslot',
                start: info.startStr,
                end: info.endStr,
                backgroundColor: '#93c5fd',
                borderColor: '#3b82f6',
                textColor: '#000',
                display: 'auto'
            });

            document.getElementById('selectedSlot').value = info.startStr;
            const selectedDate = new Date(info.startStr);
            selectedTimeDisplay.textContent = `Geselecteerd: ${selectedDate.toLocaleString('nl-NL', {
                dateStyle: 'short',
                timeStyle: 'short'
            })}`;
        },

        eventClick(info) {
            alert('Dit tijdslot is al bezet.');
        }
    });

    calendar.render();

    // Toon de modules van het geselecteerde voertuig
    document.querySelector('[name="vehicle_id"]').addEventListener('change', function () {
        const vehicleId = this.value;
        document.querySelectorAll('.vehicle-modules').forEach(el => el.style.display = 'none');
        if (vehicleId) {
            document.getElementById('vehicle-' + vehicleId).style.display = 'block';
        }
    });

    // Formulierverwerking
    planningForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!document.getElementById('selectedSlot').value) {
            showFormMessage('Kies eerst een tijdslot.', false);
            return;
        }

        const formData = new FormData(this);

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                showFormMessage('Voertuig succesvol ingepland!', true);
                if (tempEvent) {
                    calendar.getEventById('temp')?.remove();
                    tempEvent = null;
                }

                // Update de kalender zodat het ingeplande tijdslot nu permanent zichtbaar is
                calendar.refetchEvents();

                // Verwijder ingeplande modules uit de lijst van het geselecteerde voertuig
                const vehicleId = formData.get('vehicle_id');
                const checkedModules = document.querySelectorAll(`#vehicle-${vehicleId} input[type="checkbox"]:checked`);
                checkedModules.forEach(cb => {
                    cb.closest('label').remove();
                });

                // Reset de tijdslot-selectie
                document.getElementById('selectedSlot').value = '';
                selectedTimeDisplay.textContent = '';
            } else {
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join('<br>');
                    showFormMessage(errorMessages, false);
                } else {
                    showFormMessage(data.message || 'Er is een fout opgetreden bij het inplannen.', false);
                }
            }
        } catch (error) {
            console.error('Fout bij verzenden formulier:', error);
            showFormMessage('Onverwachte fout bij het inplannen. Zie console voor details.', false);
        }
    });

    function showFormMessage(message, isSuccess) {
        formMessages.classList.remove('hidden');
        formSuccess.innerHTML = '';
        formErrors.innerHTML = '';

        if (isSuccess) {
            formSuccess.innerHTML = message;
        } else {
            formErrors.innerHTML = message;
        }
    }
});
</script>
@endsection
