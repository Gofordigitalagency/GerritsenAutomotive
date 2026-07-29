@if(function_exists('vacation_active') && vacation_active())
  <div class="px-vacation-banner" id="pxVacationBanner" role="status">
    <span class="px-vacation-banner-icon" aria-hidden="true">🏖️</span>
    <span class="px-vacation-banner-text">{{ vacation_message() }}</span>
  </div>
@endif
