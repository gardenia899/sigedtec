<x-guest-layout>
    <div class="text-center">
        <h2 class="text-2xl font-bold tracking-tight text-white">Verificar <span class="text-sky-400">correo</span></h2>
        <p class="mt-2 text-[13px] leading-relaxed text-slate-400">
            Gracias por registrarte. Antes de comenzar, verifica tu correo electrónico
            con el enlace que te enviamos.
        </p>
    </div>

    @if (session('status') === 'verification-link-sent')
        <p class="mt-4 rounded-xl border border-emerald-400/25 bg-emerald-400/10 px-3.5 py-2.5 text-[12px] text-emerald-200">
            Se envió un nuevo enlace de verificación al correo indicado.
        </p>
    @endif

    <div class="mt-6 space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="auth-btn-primary">
                <x-ui.icon name="file-text" class="h-5 w-5" />
                Reenviar verificación
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="auth-btn-outline">
                <x-ui.icon name="logout" class="h-5 w-5" />
                Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>
