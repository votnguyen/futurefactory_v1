<div id="calendar"></div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            slotDuration: '02:00:00',
            slotMinTime: '08:00:00',
            slotMaxTime: '18:00:00',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek,timeGridDay'
            },
            events: @json($events ?? []),
            selectable: true,

            select(info) {
                const { start, end } = info;
                Livewire.emit('selectSlot', start, end); // Trigger slot selection modal
            },

            eventClick(info) {
                Livewire.emit('showSchedule', info.event.id); // Show task details
            }
        });

        calendar.render();
    });
</script>
@endpush
