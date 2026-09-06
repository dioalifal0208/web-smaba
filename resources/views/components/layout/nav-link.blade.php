{{--
    Nav Link — helper kecil untuk item navigasi desktop.
    MASTER.md §9, DESIGN.md §11.

    Props:
        href   — URL tujuan (required)
        active — boolean, apakah halaman ini sedang aktif
--}}

@props([
    'href'   => '#',
    'active' => false,
])

<a
    href="{{ $href }}"
    {{ $attributes->class([
        'px-3 py-2 rounded-md text-sm font-500 transition-colors duration-fast',
        'text-on-primary hover:bg-white/10',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-on-primary focus-visible:ring-offset-2 focus-visible:ring-offset-primary',
        'font-700 underline underline-offset-4' => $active,
    ]) }}
    @if($active) aria-current="page" @endif
>
    {{ $slot }}
</a>
