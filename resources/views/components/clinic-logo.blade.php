@props(['compact' => false, 'light' => false])

<div class="logo {{ $light ? 'logo-light' : '' }}">
    <div class="logo-mark">
        <x-clinic-icon name="heart" size="20" />
    </div>
    @if (! $compact)
        <div>
            <strong>ClinicCare</strong>
            <small>CLINIC APPOINTMENT</small>
        </div>
    @endif
</div>
