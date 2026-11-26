@props(['name', 'tone' => 'teal', 'size' => 'md'])

@php
    $clean = trim(str_replace('Dr. ', '', (string) $name));
    $parts = preg_split('/\s+/', $clean) ?: [];
    $initials = collect($parts)->filter()->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->take(2)->implode('') ?: 'CC';
@endphp

<span class="avatar {{ $tone }} {{ $size }}" aria-label="{{ $name }}">{{ $initials }}</span>
