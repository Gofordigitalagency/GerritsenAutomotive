@if(function_exists('vacation_active') && vacation_active())
  {{-- Layout-offset staat hier inline zodat de header altijd nét onder de banner
       zit, óók zonder JavaScript (JS verfijnt de hoogte later nog). --}}
  <style>
    :root { --vac-h: 46px; }
    body { padding-top: var(--vac-h); }
    .px-nav { top: var(--vac-h); }
    @@media (max-width: 640px) { :root { --vac-h: 60px; } }
  </style>
  <div class="px-vacation-banner" id="pxVacationBanner" role="status">
    <span class="px-vacation-banner-icon" aria-hidden="true">🏖️</span>
    <span class="px-vacation-banner-text">{{ vacation_message() }}</span>
  </div>
@endif
