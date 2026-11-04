<x-guest-layout>
    <div class="text-center">
        <h2 class="text-2xl font-bold tracking-tight text-white">Confirmar <span class="text-sky-400">contraseña</span></h2>
        <p class="mt-1.5 text-[13px] leading-relaxed text-slate-400">
            Esta es un área segura. Confirma tu contraseña para continuar.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="password" class="sr-only">Contraseña</label>
            <div class="auth-field">
                <x-ui.icon name="lock" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                <input id="password" name="password" type="password"
                       class="auth-input" placeholder="Contraseña"
                       required autocomplete="current-password">
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="auth-btn-primary">
            <x-ui.icon name="shield" class="h-5 w-5" />
            Confirmar
        </button>
    </form>
</x-guest-layout>
