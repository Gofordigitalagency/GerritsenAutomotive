@extends('admin.layout')
@section('title', 'Taken — Gerritsen Admin')
@section('page_title', 'Taken')

@section('content')
<div class="adm-dash">

  {{-- Filter chips --}}
  <div class="adm-tab-row">
    @php
      $tabs = [
        'open'    => ['label' => 'Open',     'count' => $counts['open']],
        'today'   => ['label' => 'Vandaag',  'count' => $counts['today']],
        'overdue' => ['label' => 'Verlopen', 'count' => $counts['overdue']],
        'done'    => ['label' => 'Gedaan',   'count' => $counts['done']],
        'all'     => ['label' => 'Alles',    'count' => null],
      ];
    @endphp
    @foreach($tabs as $key => $t)
      <a href="{{ route('admin.tasks.index', ['filter' => $key]) }}"
         class="adm-tab @if($filter === $key) is-active @endif">
        {{ $t['label'] }}
        @if($t['count'] !== null && $t['count'] > 0)
          <span class="adm-tab-count">{{ $t['count'] }}</span>
        @endif
      </a>
    @endforeach
  </div>

  {{-- Snel toevoegen --}}
  <div class="adm-panel">
    <div class="adm-panel-head"><h3>Nieuwe taak</h3></div>
    <form method="POST" action="{{ route('admin.tasks.store') }}" class="adm-task-add">
      @csrf
      <input type="text" name="title" required maxlength="200" placeholder="Wat moet er gebeuren?">
      <select name="occasion_id">
        <option value="">— niet aan auto gekoppeld —</option>
        @foreach(\App\Models\Occasion::orderBy('merk')->get(['id','merk','model','bouwjaar']) as $o)
          <option value="{{ $o->id }}">{{ trim($o->merk.' '.$o->model) }} @if($o->bouwjaar)({{ $o->bouwjaar }})@endif</option>
        @endforeach
      </select>
      <input type="datetime-local" name="due_at">
      <select name="priority">
        <option value="normal">Normaal</option>
        <option value="high">Hoog</option>
        <option value="low">Laag</option>
      </select>
      <button type="submit" class="btn primary">Toevoegen</button>
    </form>
  </div>

  {{-- Takenlijst --}}
  @if($tasks->isEmpty())
    <div class="adm-panel">
      <div class="adm-panel-empty">
        <div class="adm-panel-empty-icon">✓</div>
        <h3 style="margin:0 0 4px;font-size:15px">Geen taken in deze filter</h3>
        <p style="margin:0">Voeg er eentje toe of kies een andere filter.</p>
      </div>
    </div>
  @else
    <ul class="adm-list adm-tasks">
      @foreach($tasks as $task)
        <li class="adm-list-item @if($task->is_overdue && ! $task->is_completed) is-error @endif @if($task->is_completed) is-done @endif">
          <form method="POST" action="{{ route('admin.tasks.toggle', $task) }}" class="adm-task-toggle-form">
            @csrf
            <button type="submit" class="adm-task-check @if($task->is_completed) is-checked @endif" title="{{ $task->is_completed ? 'Markeer als open' : 'Markeer als gedaan' }}">
              @if($task->is_completed)
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12l5 5L20 7"/></svg>
              @endif
            </button>
          </form>

          <div class="adm-list-body">
            <div class="adm-task-title-row">
              <span class="adm-task-title @if($task->is_completed) is-done @endif">{{ $task->title }}</span>
              @if($task->priority === 'high')
                <span class="adm-task-prio is-high">hoog</span>
              @elseif($task->priority === 'low')
                <span class="adm-task-prio is-low">laag</span>
              @endif
            </div>
            <div class="adm-list-meta">
              @if($task->due_at)
                <span class="@if($task->is_overdue && ! $task->is_completed) adm-list-meta-warn @endif">
                  {{ $task->is_overdue && ! $task->is_completed ? '⚠' : '🕐' }} {{ $task->due_at->format('d-m-Y H:i') }} · {{ $task->due_at->diffForHumans() }}
                </span>
              @endif
              @if($task->occasion)
                · <a href="{{ route('admin.occasions.edit', $task->occasion) }}" class="adm-task-link">🚗 {{ trim($task->occasion->merk.' '.$task->occasion->model) }}</a>
              @endif
            </div>
          </div>

          <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" onsubmit="return confirm('Taak verwijderen?')" class="adm-task-del-form">
            @csrf @method('DELETE')
            <button type="submit" class="btn sm danger">×</button>
          </form>
        </li>
      @endforeach
    </ul>

    <div class="pagination-wrap">{{ $tasks->links() }}</div>
  @endif
</div>
@endsection
