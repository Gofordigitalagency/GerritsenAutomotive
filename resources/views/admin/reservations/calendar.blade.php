@extends('admin.layout')
@section('title','Agenda')
@section('page_title','Agenda')

@section('content')
  <div class="form-card">
    <div class="form-card-head">
      <h3>Agenda</h3>
    </div>
    <div class="form-card-body">

      {{-- Legend --}}
      <div class="legend" style="display:flex;gap:16px;align-items:center;margin-bottom:12px;">
        <span style="display:inline-flex;align-items:center;gap:8px;">
          <span style="width:12px;height:12px;border-radius:3px;background:#0ea5e9;display:inline-block;"></span> Aanhanger
        </span>
        <span style="display:inline-flex;align-items:center;gap:8px;">
          <span style="width:12px;height:12px;border-radius:3px;background:#f97316;display:inline-block;"></span> Stofzuiger
        </span>
      </div>

      <div id="calendar"></div>
    </div>
  </div>

  {{-- FullCalendar CSS/JS (CDN) --}}
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales-all.global.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const calendarEl = document.getElementById('calendar');
      const events = @json($events);

const cal = new FullCalendar.Calendar(calendarEl, {
  locale: 'nl',
  timeZone: 'Europe/Amsterdam',
  initialView: 'timeGridWeek',
  headerToolbar: { left:'prev,next today', center:'title', right:'dayGridMonth,timeGridWeek,timeGridDay' },

  // NL-knoppen
  buttonText: { month: 'maand', week: 'week', day: 'dag', today: 'vandaag' },

  slotDuration: '00:30:00',
  expandRows: true,
  dayMaxEventRows: true,
  eventOverlap: true,
  slotEventOverlap: true,
  eventDisplay: 'block',
  eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },

  // Iets nettere titel
  titleFormat: { year: 'numeric', month: 'long', day: '2-digit' },

  // (optioneel) werkslots/zichtbaar raster
  slotMinTime: '08:00:00',
  slotMaxTime: '20:00:00',
  businessHours: { daysOfWeek: [1,2,3,4,5], startTime: '08:00', endTime: '18:00' },

  events,
  eventClick(info) {
    if (info.event.url) {
      window.location.href = info.event.url;
      info.jsEvent.preventDefault();
    }
  }
});

      cal.render();
    });
  </script>

  <style>
    /* Klein beetje theming zodat het bij je admin past */
    #calendar .fc-toolbar-title{ font-weight:700; }
    #calendar .fc-button{ border-radius:10px; padding:6px 10px; }
    #calendar .fc-daygrid-event{ border-radius:8px; }
    #calendar .fc-col-header-cell-cushion{ padding:8px 0; }
  </style>
@endsection
