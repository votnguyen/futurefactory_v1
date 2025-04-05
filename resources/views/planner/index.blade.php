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

        <!-- Voertuig Selectie -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Plan nieuw voertuig</h2>

            {{-- Feedback messages --}}
            <div id="formMessages" class="mb-4 hidden">
                <div id="formSuccess" class="text-green-600 font-semibold"></div>
                <div id="formErrors" class="text-red-600 font-semibold"></div>
            </div>

            <form id="planningForm" method="POST" action="{{ route('planner.store') }}">
                @csrf
                <input type="hidden" name="start_time" id="selectedSlot">

                <div class="mb-4">
                    <label class="block font-medium mb-2">Selecteer voertuig:</label>
                    <select name="vehicle_id" class="w-full p-2 border rounded" required>
                        <option value="">-- Kies een voertuig --</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->name }} ({{ $vehicle->customer->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-2">Selecteer modules:</label>
                    <div class="space-y-2" id="moduleList">
                        <!-- Dynamische Modules Weergave per Voertuig -->
                        @foreach($vehicles as $vehicle)
                            <div class="vehicle-modules" id="vehicle-{{ $vehicle->id }}" style="display:none;">
                                @foreach($vehicle->modules as $module)
                                    <label class="flex items-center p-2 border rounded hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" 
                                               name="modules[]" 
                                               value="{{ $module->id }}"
                                               data-vehicle="{{ $vehicle->id }}"
                                               class="vehicle-module mr-2">
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

<!-- FullCalendar CSS & JS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/nl.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedSlotDate = null;
    let tempEvent = null;

    const calendarEl = document.getElementById('calendar');
    const selectedTimeDisplay = document.getElementById('selectedTimeDisplay');
    const formMessages = document.getElementById('formMessages');
    const formSuccess = document.getElementById('formSuccess');
    const formErrors = document.getElementById('formErrors');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'nl',
        initialView: 'timeGridWeek',
        slotDuration: '02:00:00',
        slotMinTime: '08:00:00',
        slotMaxTime: '18:00:00',
        selectable: true,
        editable: false,
        events: '/api/schedules',

        eventDidMount: function(info) {
            info.el.style.backgroundColor = '#3b82f6';
            info.el.style.borderColor = '#2563eb';
            info.el.style.color = 'white';
        },

        select: function(info) {
            selectedSlotDate = info.start;

            if (tempEvent) {
                calendar.getEventById('temp')?.remove();
            }

            tempEvent = calendar.addEvent({
                id: 'temp',
                title: 'Geselecteerd tijdslot',
                start: info.start,
                end: info.end,
                backgroundColor: '#93c5fd',
                borderColor: '#3b82f6',
                textColor: '#000',
                display: 'auto'
            });

            document.getElementById('selectedSlot').value = selectedSlotDate.toISOString();
            selectedTimeDisplay.textContent = `Geselecteerd: ${selectedSlotDate.toLocaleString('nl-NL', {
                dateStyle: 'short',
                timeStyle: 'short'
            })}`;
        },

        eventClick: function(info) {
            alert('Dit tijdslot is al bezet.');
        },
    });

    calendar.render();

    // Filter modules op voertuig selectie
    document.querySelector('[name="vehicle_id"]').addEventListener('change', function() {
        const vehicleId = this.value;
        // Verberg alle module lijsten
        document.querySelectorAll('.vehicle-modules').forEach(el => el.style.display = 'none');
        // Toon de geselecteerde voertuig modules
        if (vehicleId) {
            document.getElementById('vehicle-' + vehicleId).style.display = 'block';
        }
    });

    // Formulier versturen
    document.getElementById('planningForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!selectedSlotDate) {
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

                calendar.addEvent({
                    id: 'confirmed-' + Date.now(),
                    title: 'Ingepland voertuig',
                    start: selectedSlotDate,
                    end: new Date(new Date(selectedSlotDate).getTime() + 2 * 60 * 60 * 1000),
                    backgroundColor: '#93c5fd',
                    borderColor: '#3b82f6',
                    textColor: '#000'
                });

                this.reset();
                selectedTimeDisplay.textContent = `Geselecteerd: ${selectedSlotDate.toLocaleString('nl-NL', {
                    dateStyle: 'short',
                    timeStyle: 'short'
                })}`;
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
            if (error instanceof TypeError) {
                showFormMessage('Server niet bereikbaar of netwerkfout.', false);
            } else {
                showFormMessage('Onverwachte fout bij het inplannen. Zie console voor details.', false);
            }
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
