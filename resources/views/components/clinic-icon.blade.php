@props(['name', 'size' => 20])
@php
$paths = [
    'grid' => '<rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/>',
    'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
    'doctor' => '<path d="M12 21a9 9 0 1 0-9-9"/><path d="M12 7v10M7 12h10"/><path d="M3 17v4h4"/>',
    'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/><path d="m9 16 2 2 4-4"/>',
    'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    'report' => '<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>',
    'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/>',
    'log' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h6"/>',
    'plus' => '<path d="M12 5v14M5 12h14"/>',
    'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>',
    'bell' => '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>',
    'chevron' => '<path d="m9 18 6-6-6-6"/>',
    'menu' => '<path d="M4 6h16M4 12h16M4 18h16"/>',
    'close' => '<path d="m6 6 12 12M18 6 6 18"/>',
    'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
    'download' => '<path d="M12 3v12M7 10l5 5 5-5M5 21h14"/>',
    'print' => '<path d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="7"/>',
    'more' => '<circle cx="5" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/>',
    'patient' => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
    'edit' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4z"/>',
    'filter' => '<path d="M4 5h16l-6 7v6l-4 2v-8z"/>',
    'back' => '<path d="M19 12H5M11 18l-6-6 6-6"/>',
    'check' => '<path d="m5 12 4 4L19 6"/>',
    'alert' => '<circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 17h.01"/>',
    'logout' => '<path d="M10 17l5-5-5-5M15 12H3"/><path d="M14 3h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5"/>',
];
@endphp
<svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $paths[$name] ?? '' !!}</svg>
