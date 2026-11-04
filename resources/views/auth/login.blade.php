<x-guest-layout>
    <div class="text-center">
        <h2 class="text-2xl font-bold tracking-tight text-white">Iniciar <span class="text-sky-400">Sesión</span></h2>
        <p class="mt-1.5 text-[13px] text-slate-400">Accede a tu cuenta para continuar</p>
    </div>

    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-3.5" x-data="{ verPassword: false }">
        @csrf

        {{-- Usuario o correo --}}
        <div>
            <label for="email" class="sr-only">Usuario o correo electrónico</label>
            <div class="auth-field">
                <x-ui.icon name="user" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                <input id="email" name="email" type="email" value="{{ old('email') }}"
                       class="auth-input"
                       placeholder="Usuario o correo electrónico"
                       required autofocus autocomplete="username">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        {{-- Contraseña --}}
        <div>
            <label for="password" class="sr-only">Contraseña</label>
            <div class="auth-field">
                <x-ui.icon name="lock" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                <input id="password" name="password"
                       :type="verPassword ? 'text' : 'password'"
                       class="auth-input auth-input-toggle"
                       placeholder="Contraseña"
                       required autocomplete="current-password">
                <button type="button" @click="verPassword = !verPassword"
                        class="absolute right-3 top-1/2 -translate-y-1/2 rounded-lg p-1.5 text-slate-400 transition hover:text-sky-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-400"
                        :aria-label="verPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'">
                    <x-ui.icon name="eye" class="h-5 w-5" x-show="! verPassword" />
                    <x-ui.icon name="eye-off" class="h-5 w-5" x-show="verPassword" x-cloak />
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mantener sesión --}}
        <div class="flex items-center justify-between gap-3 pt-0.5">
            <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2.5 text-[13px] text-slate-300">
                <input id="remember_me" name="remember" type="checkbox" class="auth-check">
                Mantener sesión activa
            </label>
            <a href="{{ route('password.request') }}" class="text-[13px] font-medium text-sky-300 transition hover:text-sky-200 hover:underline">
                ¿Olvidaste tu contraseña?
            </a>
        </div>

        <button type="submit" class="auth-btn-primary !mt-5">
            <x-ui.icon name="login" class="h-5 w-5" />
            Iniciar Sesión
        </button>
    </form>

    {{-- Separador --}}
    <div class="my-5 flex items-center gap-3 text-[11px] text-slate-500">
        <span class="h-px flex-1 bg-white/10"></span>
        o
        <span class="h-px flex-1 bg-white/10"></span>
    </div>

    <a href="{{ route('register') }}" class="auth-btn-outline">
        <x-ui.icon name="user-plus" class="h-5 w-5" />
        Crear una cuenta
    </a>

    {{-- Roles del sistema --}}
    <div class="mt-6 grid grid-cols-2 gap-3 border-t border-white/10 pt-5 text-[11px] text-slate-400">
        <div class="flex items-start gap-2">
            <x-ui.icon name="user" class="h-5 w-5 shrink-0 text-sky-400" />
            <p class="leading-snug">
                <span class="block font-semibold text-slate-200">Administrador</span>
                Soporte Técnico
            </p>
        </div>
        <div class="flex items-start gap-2 border-l border-white/10 pl-3">
            <x-ui.icon name="user" class="h-5 w-5 shrink-0 text-sky-400" />
            <p class="leading-snug">
                <span class="block font-semibold text-slate-200">Solicitante</span>
                Jefe de Oficina
            </p>
        </div>
    </div>
</x-guest-layout>
