@props(['name', 'class' => ''])
<svg class="ui-icon {{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('dashboard')<rect x="3" y="3" width="8" height="8" rx="2"/><rect x="13" y="3" width="8" height="5" rx="2"/><rect x="13" y="10" width="8" height="11" rx="2"/><rect x="3" y="13" width="8" height="8" rx="2"/>@break
        @case('vault')<path d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v11a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 17.5v-11Z"/><circle cx="12" cy="12" r="3.5"/><path d="M12 8.5v7M8.5 12h7"/>@break
        @case('key')<circle cx="8" cy="15" r="5"/><path d="m11.5 11.5 8-8M16 7l2 2M13.5 9.5l2 2"/>@break
        @case('scan')<path d="M4 8V5a1 1 0 0 1 1-1h3M16 4h3a1 1 0 0 1 1 1v3M20 16v3a1 1 0 0 1-1 1h-3M8 20H5a1 1 0 0 1-1-1v-3M7 12h10M9 9h.01M15 15h.01"/>@break
        @case('file')<path d="M7 3h7l5 5v12a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path d="M14 3v5h5M8 13h8M8 17h8"/>@break
        @case('user')<circle cx="12" cy="8" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0"/>@break
        @case('mail')<rect x="3" y="5" width="18" height="14" rx="3"/><path d="m4 7 8 6 8-6"/>@break
        @case('logout')<path d="M10 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h5M15 16l4-4-4-4M19 12H9"/>@break
        @case('shield')<path d="M12 3 20 6v5.5c0 5-3.2 8.1-8 9.9-4.8-1.8-8-4.9-8-9.9V6l8-3Z"/><path d="m9 12 2 2 4-4"/>@break
        @case('lock')<rect x="4" y="10" width="16" height="11" rx="2.5"/><path d="M8 10V7a4 4 0 1 1 8 0v3M12 14v3"/>@break
        @case('spark')<path d="m12 3 1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3ZM19 16l.9 2.1L22 19l-2.1.9L19 22l-.9-2.1L16 19l2.1-.9L19 16Z"/>@break
        @default<circle cx="12" cy="12" r="8"/><path d="M12 8v4l2.5 2.5"/>
    @endswitch
</svg>
