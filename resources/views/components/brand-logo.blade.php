@props(['href' => '/', 'light' => false, 'compact' => false])
<a href="{{ $href }}" {{ $attributes->class(['securevault-brand', 'is-light' => $light, 'is-compact' => $compact]) }} aria-label="SecureVault">
    <svg class="securevault-mark" viewBox="0 0 48 48" role="img" aria-hidden="true">
        <defs>
            <linearGradient id="securevault-gradient" x1="6" y1="4" x2="42" y2="44" gradientUnits="userSpaceOnUse">
                <stop stop-color="#8B7CFF"/><stop offset="1" stop-color="#43D6C5"/>
            </linearGradient>
        </defs>
        <path d="M24 3.5 42 10v12.1c0 11.4-7.2 18.8-18 23.1C13.2 40.9 6 33.5 6 22.1V10l18-6.5Z" fill="url(#securevault-gradient)"/>
        <path d="M18.7 21v-3.2a5.3 5.3 0 0 1 10.6 0V21M17 21h14v11H17V21Z" fill="none" stroke="#101632" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
        <circle cx="24" cy="26.2" r="1.6" fill="#101632"/>
        <path d="M24 27.8v1.6" stroke="#101632" stroke-width="2.2" stroke-linecap="round"/>
    </svg>
    @unless($compact)<span class="securevault-word">Secure<span>Vault</span></span>@endunless
</a>
