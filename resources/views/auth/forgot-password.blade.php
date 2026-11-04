<x-guest-layout>
    <div class="text-center">
        <h2 class="text-2xl font-bold tracking-tight text-white">Recuperar <span class="text-sky-400">contraseña</span></h2>
        <p class="mt-1.5 text-[13px] leading-relaxed text-slate-400">
            Indícanos tu correo institucional y te enviaremos un enlace para restablecerla.
        </p>
    </div>

    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="email" class="sr-only">Correo institucional</label>
            <div class="auth-field">
                <x-ui.icon name="file-text" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                <input id="email" name="email" type="email" value="{{ old('email') }}"
                       class="auth-input" placeholder="Correo institucional"
                       required autofocus autocomplete="username">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="auth-btn-primary">
            <x-ui.icon name="arrow-right" class="h-5 w-5" />
            Enviar enlace
        </button>
    </form>

    <div class="mt-5 text-center">
        <a href="{{ route('login') }}" class="auth-link">Volver al inicio de sesión</a>
    </div>
</x-guest-layout>
