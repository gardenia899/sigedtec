@props(['title', 'subtitle' => null])

{{-- Encabezado de página del Design System §20.5: título, apoyo y acciones. --}}
<div {{ $attributes->merge(['class' => 'flex flex-wrap items-start justify-between gap-4']) }}>
    <div class="min-w-0">
        <h1 class="page-title">{{ $title }}</h1>
        @if ($subtitle)
            <p class="page-sub">{{ $subtitle }}</p>
        @endif
    </div>

    @if (! $slot->isEmpty())
        <div class="flex flex-wrap items-center gap-2">
            {{ $slot }}
        </div>
    @endif
</div>
