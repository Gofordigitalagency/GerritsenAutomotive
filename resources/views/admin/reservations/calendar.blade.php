@extends('admin.layout')
@section('title', 'Agenda — Gerritsen Admin')
@section('page_title', 'Agenda')

@section('content')
<div class="adm-dash">

  <div class="form-card">
    <div class="form-card-head">
      <h3>Agenda</h3>
    </div>
    <div class="form-card-body">

      <div class="adm-calendar-legend">
        <span><span class="adm-calendar-dot" style="background:#3b82f6"></span> Aanhanger</span>
        <span><span class="adm-calendar-dot" style="background:#f97316"></span> Tapijtreiniger</span>
        <span><span class="adm-calendar-dot" style="background:#a855f7"></span> Koplampen</span>
        <span><span class="adm-calendar-dot" style="background:#10b981"></span> Werkplaats</span>
      </div>

      <div id="calendar"></div>
    </div>
  </div>
</div>

{{-- FullCalendar CSS/JS --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales-all.global.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('calendar');
    const events = @json($events);

    const cal = new FullCalendar.Calendar(el, {
      locale: 'nl',
      timeZone: 'Europe/Amsterdam',
      initialView: 'timeGridWeek',
      headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
      buttonText: { month: 'maand', week: 'week', day: 'dag', today: 'vandaag' },
      slotDuration: '00:30:00',
      expandRows: true,
      dayMaxEventRows: true,
      eventOverlap: true,
      slotEventOverlap: true,
      eventDisplay: 'block',
      eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
      titleFormat: { year: 'numeric', month: 'long', day: '2-digit' },
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
  /* ============ FullCalendar dark-theme overrides ============ */
  #calendar {
    --fc-border-color: var(--border);
    --fc-page-bg-color: transparent;
    --fc-neutral-bg-color: var(--bg-2);
    --fc-list-event-hover-bg-color: var(--surface-2);
    --fc-today-bg-color: rgba(230, 57, 70, .08);
    --fc-event-bg-color: var(--accent);
    --fc-event-border-color: var(--accent);
    --fc-event-text-color: #fff;
    --fc-button-bg-color: var(--surface);
    --fc-button-border-color: var(--border);
    --fc-button-hover-bg-color: var(--surface-2);
    --fc-button-hover-border-color: rgba(255,255,255,.16);
    --fc-button-active-bg-color: var(--accent);
    --fc-button-active-border-color: var(--accent);
    --fc-now-indicator-color: var(--accent);
    color: var(--text);
  }

  #calendar .fc-toolbar-title {
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    font-weight: 700;
    font-size: 18px !important;
    color: var(--text);
    letter-spacing: -.01em;
  }

  #calendar .fc-button {
    border-radius: 8px !important;
    padding: 6px 12px !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    color: var(--text) !important;
    text-transform: capitalize;
    box-shadow: none !important;
  }
  #calendar .fc-button-primary:not(:disabled).fc-button-active,
  #calendar .fc-button-primary:not(:disabled):active {
    color: #fff !important;
  }
  #calendar .fc-button-group .fc-button + .fc-button { margin-left: 4px; }

  #calendar .fc-col-header-cell { background: var(--bg-2); }
  #calendar .fc-col-header-cell-cushion {
    padding: 10px 4px !important;
    color: var(--muted);
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .06em;
  }

  #calendar .fc-daygrid-day-number,
  #calendar .fc-timegrid-axis-cushion,
  #calendar .fc-timegrid-slot-label-cushion {
    color: var(--muted);
    font-size: 12px;
  }

  #calendar .fc-daygrid-day.fc-day-today .fc-daygrid-day-number,
  #calendar .fc-day-today { color: var(--accent); }

  #calendar .fc-event {
    border-radius: 6px !important;
    padding: 2px 6px;
    font-size: 12px;
    font-weight: 500;
    border: none !important;
  }
  #calendar .fc-daygrid-event { border-radius: 6px; }
  #calendar .fc-event:hover { filter: brightness(1.1); }

  #calendar .fc-non-business { background: rgba(0,0,0,.15); }

  #calendar .fc-scrollgrid,
  #calendar .fc-scrollgrid-section table {
    border-color: var(--border) !important;
  }

  /* Legend */
  .adm-calendar-legend {
    display: flex;
    gap: 18px;
    align-items: center;
    margin-bottom: 16px;
    flex-wrap: wrap;
    font-size: 13px;
    color: var(--muted);
  }
  .adm-calendar-legend span {
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  .adm-calendar-dot {
    width: 10px;
    height: 10px;
    border-radius: 3px;
    display: inline-block;
  }
</style>
@endsection
