@extends('admin.layout')
@section('title', 'Dashboard — Gerritsen Admin')
@section('page_title', 'Dashboard')

@section('content')
<div class="adm-dash">

  {{-- ============ OMZET KPI ROW (laatste 30 dagen) ============ --}}
  <div class="adm-kpis">
    <div class="adm-kpi" style="--accent:#1f8f3a">
      <div class="adm-kpi-head">
        <span class="adm-kpi-label">Omzet (30d)</span>
        <span class="adm-kpi-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </span>
      </div>
      <div class="adm-kpi-value">€ {{ number_format($omzet30d, 0, ',', '.') }}</div>
      <div class="adm-kpi-sub">{{ $verkocht30dCount }} {{ $verkocht30dCount === 1 ? 'auto' : "auto's" }} verkocht</div>
    </div>

    <div class="adm-kpi" style="--accent:#a855f7">
      <div class="adm-kpi-head">
        <span class="adm-kpi-label">Marge gerealiseerd</span>
        <span class="adm-kpi-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 17 13.5 8.5 8.5 13.5 2 7"/><polyline points="16 17 22 17 22 11"/></svg>
        </span>
      </div>
      <div class="adm-kpi-value">€ {{ number_format($margeRealised30d, 0, ',', '.') }}</div>
      <div class="adm-kpi-sub">laatste 30 dagen</div>
    </div>

    <div class="adm-kpi" style="--accent:#f59e0b">
      <div class="adm-kpi-head">
        <span class="adm-kpi-label">Voorraad-omloop</span>
        <span class="adm-kpi-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </span>
      </div>
      <div class="adm-kpi-value">{{ $avgDaysInStock !== null ? $avgDaysInStock : '·' }}</div>
      <div class="adm-kpi-sub">{{ $avgDaysInStock !== null ? 'gem. dagen tot verkoop' : 'nog geen verkopen geregistreerd' }}</div>
    </div>

    <div class="adm-kpi" style="--accent:#3b82f6">
      <div class="adm-kpi-head">
        <span class="adm-kpi-label">Actieve voorraad</span>
        <span class="adm-kpi-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l3-6h12l3 6M3 9v10a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V9M3 9h18"/><circle cx="7.5" cy="14.5" r="1.5"/><circle cx="16.5" cy="14.5" r="1.5"/></svg>
        </span>
      </div>
      <div class="adm-kpi-value">{{ $activeCount }}</div>
      <div class="adm-kpi-sub">€ {{ number_format($stockValue, 0, ',', '.') }} voorraad-waarde</div>
    </div>
  </div>

  {{-- ============ OMZET-GRAFIEK (12 weken) ============ --}}
  <div class="adm-panel">
    <div class="adm-panel-head">
      <h3>Omzet per week — laatste 12 weken</h3>
      <span style="font-size:12px;color:var(--muted)">
        Klik op staaf voor details · Totaal: € {{ number_format(collect($weeks)->sum('omzet'), 0, ',', '.') }}
      </span>
    </div>

    @if(collect($weeks)->sum('count') === 0)
      <div class="adm-panel-empty" style="padding:32px 16px">
        <div class="adm-panel-empty-icon">📊</div>
        <p style="margin:0 0 4px"><strong style="color:var(--text)">Nog geen verkoopgegevens</strong></p>
        <p style="margin:0">Markeer auto's als "Verkocht" via hun edit-pagina om hier omzet en marge te zien.</p>
      </div>
    @else
      <div class="adm-chart">
        @foreach($weeks as $w)
          @php
            $omzetPct = $weeksMaxOmzet > 0 ? ($w['omzet'] / $weeksMaxOmzet) * 100 : 0;
            $margePct = $w['omzet'] > 0 ? ($w['marge'] / $w['omzet']) * 100 : 0;
          @endphp
          <div class="adm-chart-bar" title="{{ $w['count'] }} verkoop{{ $w['count'] === 1 ? '' : 'en' }} · Omzet € {{ number_format($w['omzet'], 0, ',', '.') }} · Marge € {{ number_format($w['marge'], 0, ',', '.') }}">
            <div class="adm-chart-bar-value">
              @if($w['omzet'] > 0)€ {{ number_format($w['omzet'] / 1000, $w['omzet'] >= 10000 ? 0 : 1, ',', '.') }}k @endif
            </div>
            <div class="adm-chart-bar-stack" style="height: {{ max($omzetPct, 2) }}%">
              <div class="adm-chart-bar-marge" style="height: {{ $margePct }}%"></div>
            </div>
            <div class="adm-chart-bar-label">{{ $w['label'] }}</div>
          </div>
        @endforeach
      </div>
      <div class="adm-chart-legend">
        <span><span class="adm-chart-dot" style="background:var(--accent)"></span> Omzet</span>
        <span><span class="adm-chart-dot" style="background:var(--success)"></span> Gerealiseerde marge</span>
      </div>
    @endif
  </div>

  {{-- ============ OPERATIONELE KPIs (taken etc) ============ --}}
  <div class="adm-kpis">
    <div class="adm-kpi" style="--accent:#f59e0b">
      <div class="adm-kpi-head">
        <span class="adm-kpi-label">Open taken</span>
        <span class="adm-kpi-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        </span>
      </div>
      <div class="adm-kpi-value">{{ $tasksOpen }}</div>
      <div class="adm-kpi-sub @if($tasksOverdue > 0) adm-kpi-sub-warn @endif">
        @if($tasksOverdue > 0)⚠ {{ $tasksOverdue }} verlopen
        @elseif($tasksToday > 0){{ $tasksToday }} vandaag
        @else alles op schema @endif
      </div>
    </div>

    <div class="adm-kpi" style="--accent:#a855f7">
      <div class="adm-kpi-head">
        <span class="adm-kpi-label">Verwachte marge</span>
        <span class="adm-kpi-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 17 13.5 8.5 8.5 13.5 2 7"/><polyline points="16 17 22 17 22 11"/></svg>
        </span>
      </div>
      <div class="adm-kpi-value">€ {{ number_format($totalMargin, 0, ',', '.') }}</div>
      <div class="adm-kpi-sub">
        @if($marginRows->count() > 0)
          gem. € {{ number_format($avgMargin, 0, ',', '.') }} ({{ number_format($avgMarginPct, 1, ',', '.') }}%) op huidige voorraad
        @else
          vul inkoopprijzen in
        @endif
      </div>
    </div>

    @if($binnenkortCount > 0)
      <div class="adm-kpi" style="--accent:#0ea5e9">
        <div class="adm-kpi-head">
          <span class="adm-kpi-label">Binnenkort verwacht</span>
          <span class="adm-kpi-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
          </span>
        </div>
        <div class="adm-kpi-value">{{ $binnenkortCount }}</div>
        <div class="adm-kpi-sub">auto's in aankomst</div>
      </div>
    @endif
  </div>

  {{-- ============ AANDACHT NODIG ============ --}}
  @if($missingInkoop > 0 || $tasksOverdue > 0)
    <div class="adm-attention">
      <h3>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        Aandacht nodig
      </h3>
      <ul class="adm-attention-list">
        @if($missingInkoop > 0)
          <li class="adm-attention-item">
            <span><strong>{{ $missingInkoop }}</strong> auto{{ $missingInkoop === 1 ? '' : "'s" }} zonder inkoopprijs — marge niet zichtbaar</span>
            <a href="{{ route('admin.occasions.index') }}" class="btn sm">Bekijk →</a>
          </li>
        @endif
        @if($tasksOverdue > 0)
          <li class="adm-attention-item is-error">
            <span><strong>{{ $tasksOverdue }}</strong> verlopen ta{{ $tasksOverdue === 1 ? 'ak' : 'ken' }} — verdienen je aandacht</span>
            <a href="{{ route('admin.tasks.index', ['filter' => 'overdue']) }}" class="btn sm">Bekijk →</a>
          </li>
        @endif
      </ul>
    </div>
  @endif

  {{-- ============ TAKEN + LANGE STOCK (2-koloms) ============ --}}
  <div class="adm-grid-2">

    <div class="adm-panel">
      <div class="adm-panel-head">
        <h3>Aankomende taken</h3>
        <a href="{{ route('admin.tasks.index') }}" class="btn sm">Alles →</a>
      </div>
      @if($upcomingTasks->isEmpty())
        <div class="adm-panel-empty">
          <div class="adm-panel-empty-icon">✓</div>
          Geen open taken.
        </div>
      @else
        <ul class="adm-list">
          @foreach($upcomingTasks as $task)
            <li class="adm-list-item @if($task->is_overdue) is-error @endif">
              <span class="adm-list-dot is-{{ $task->priority }}"></span>
              <div class="adm-list-body">
                <div class="adm-list-title">{{ $task->title }}</div>
                <div class="adm-list-meta @if($task->is_overdue) adm-list-meta-warn @endif">
                  @if($task->due_at){{ $task->due_at->diffForHumans() }}@else geen deadline @endif
                  @if($task->occasion) · {{ trim($task->occasion->merk.' '.$task->occasion->model) }}@endif
                </div>
              </div>
            </li>
          @endforeach
        </ul>
      @endif
    </div>

    <div class="adm-panel">
      <div class="adm-panel-head">
        <h3>Langer dan 60 dagen in voorraad</h3>
      </div>
      @if($oldStock->isEmpty())
        <div class="adm-panel-empty">
          <div class="adm-panel-empty-icon">👌</div>
          Geen oude voorraad.
        </div>
      @else
        <ul class="adm-list">
          @foreach($oldStock as $car)
            <li class="adm-list-item">
              <img class="adm-list-thumb" src="{{ $car->hoofdfoto_path ? asset('storage/'.$car->hoofdfoto_path) : asset('images/placeholder-car.jpg') }}" alt="">
              <div class="adm-list-body">
                <div class="adm-list-title">
                  <a href="{{ route('admin.occasions.edit', $car) }}">{{ trim($car->merk.' '.$car->model) }}</a>
                </div>
                <div class="adm-list-meta">
                  {{ $car->created_at->diffForHumans() }} toegevoegd · € {{ number_format($car->prijs ?? 0, 0, ',', '.') }}
                </div>
              </div>
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>

  {{-- ============ RECENT VERKOCHT ============ --}}
  @if($recentlySold->isNotEmpty())
    <div class="adm-panel">
      <div class="adm-panel-head">
        <h3>Recent verkocht</h3>
      </div>
      <ul class="adm-list">
        @foreach($recentlySold as $car)
          <li class="adm-list-item">
            <img class="adm-list-thumb" src="{{ $car->hoofdfoto_path ? asset('storage/'.$car->hoofdfoto_path) : asset('images/placeholder-car.jpg') }}" alt="">
            <div class="adm-list-body">
              <div class="adm-list-title">
                <a href="{{ route('admin.occasions.edit', $car) }}">{{ trim(str_replace('(VERKOCHT)', '', $car->merk.' '.$car->model)) }}</a>
              </div>
              <div class="adm-list-meta">
                {{ $car->verkocht_datum->format('d-m-Y') }} ·
                € {{ number_format($car->verkoopprijs ?? 0, 0, ',', '.') }}
                @if($car->gerealiseerde_marge !== null)
                  ·
                  <span style="color:{{ $car->gerealiseerde_marge >= 0 ? 'var(--success)' : 'var(--danger)' }}">
                    marge {{ $car->gerealiseerde_marge >= 0 ? '+' : '' }}€ {{ number_format($car->gerealiseerde_marge, 0, ',', '.') }}
                  </span>
                @endif
                @if($car->dagen_in_voorraad !== null) · {{ $car->dagen_in_voorraad }}d in voorraad @endif
              </div>
            </div>
          </li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- ============ RECENT TOEGEVOEGD ============ --}}
  <div class="adm-panel">
    <div class="adm-panel-head">
      <h3>Recent toegevoegd</h3>
      <a href="{{ route('admin.occasions.create') }}" class="btn primary sm">+ Nieuwe auto</a>
    </div>
    @if($recentlyAdded->isEmpty())
      <div class="adm-panel-empty">Nog geen auto's. Voeg er een toe.</div>
    @else
      <div class="adm-recent-grid">
        @foreach($recentlyAdded as $car)
          <a href="{{ route('admin.occasions.edit', $car) }}" class="adm-recent-card">
            <img src="{{ $car->hoofdfoto_path ? asset('storage/'.$car->hoofdfoto_path) : asset('images/placeholder-car.jpg') }}" alt="">
            <div class="adm-recent-body">
              <div class="adm-recent-title">{{ trim($car->merk.' '.$car->model) }}</div>
              <div class="adm-recent-meta">
                € {{ number_format($car->prijs ?? 0, 0, ',', '.') }} · {{ $car->created_at->diffForHumans() }}
              </div>
            </div>
          </a>
        @endforeach
      </div>
    @endif
  </div>

</div>
@endsection
