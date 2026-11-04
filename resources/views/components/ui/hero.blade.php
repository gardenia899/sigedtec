@props(['title', 'subtitle' => null])

{{-- Banda de bienvenida del panel (SDD §20.10): contexto del usuario y lema institucional. --}}
<section class="relative overflow-hidden rounded-2xl bg-navy-900 shadow-card">
    <div aria-hidden="true" class="absolute inset-0 bg-[linear-gradient(105deg,#07182F_0%,#0A2148_48%,#0D2E66_100%)]"></div>
    <div aria-hidden="true" class="pointer-events-none absolute inset-0">
        <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-sky-400/20 blur-3xl"></div>
        <div class="absolute right-24 -bottom-10 h-44 w-64 rounded-t-[46px] border border-sky-300/15 bg-white/[0.04]"></div>
        <div class="absolute right-48 bottom-4 h-24 w-40 rounded-t-[32px] border border-sky-300/10 bg-white/[0.03]"></div>
    </div>

    <div class="relative flex flex-col gap-6 px-6 py-7 sm:px-8 md:flex-row md:items-center md:justify-between">
        <div class="max-w-2xl">
            <span class="block h-1.5 w-11 rounded-full bg-sky-400"></span>
            <h1 class="mt-4 text-[24px] font-bold leading-tight tracking-tight text-white sm:text-[28px]">{{ $title }}</h1>
            @if ($subtitle)
                <p class="mt-2 max-w-xl text-[13px] leading-relaxed text-slate-300 sm:text-sm">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="hidden shrink-0 items-center gap-4 md:flex">
            <p class="max-w-[190px] text-right text-[13px] italic leading-snug text-sky-300">
                Tecnología<br>al servicio<br>de la institución
            </p>
            <x-ui.icon name="logo" class="h-16 w-16 text-sky-400/80" />
        </div>
    </div>
</section>
