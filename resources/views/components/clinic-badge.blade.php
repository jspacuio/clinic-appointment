@props(['status'])

@php
    $label = $status instanceof \App\Enums\AppointmentStatus ? $status->label() : (string) $status;
    $cssClass = $status instanceof \App\Enums\AppointmentStatus
        ? $status->cssClass()
        : strtolower(str_replace(' ', '-', $label));
@endphp

<span class="badge {{ $cssClass }}"><i></i>{{ $label }}</span>
