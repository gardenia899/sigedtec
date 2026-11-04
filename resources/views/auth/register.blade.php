<x-guest-layout>
    <div class="text-center">
        <h2 class="text-2xl font-bold tracking-tight text-white">Crear <span class="text-sky-400">cuenta</span></h2>
        <p class="mt-1.5 text-[13px] text-slate-400">Registra tus datos para solicitar equipos</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-3.5">
        @csrf

        {{-- Nombre --}}
        <div>
            <label for="name" class="sr-only">Nombre completo</label>
            <div class="auth-field">
                <x-ui.icon name="user" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                <input id="name" name="name" type="text" value="{{ old('name') }}"
                       class="auth-input" placeholder="Nombre completo"
                       required autofocus autocomplete="name">
            </div>
            @error('name')
                <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        {{-- Correo --}}
        <div>
            <label for="email" class="sr-only">Correo institucional</label>
            <div class="auth-field">
                <x-ui.icon name="file-text" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                <input id="email" name="email" type="email" value="{{ old('email') }}"
                       class="auth-input" placeholder="Correo institucional"
                       required autocomplete="username">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        {{-- Oficina --}}
        <div>
            <label for="office_id" class="sr-only">Oficina</label>
            <div class="relative">
                <x-ui.icon name="building" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                <select id="office_id" name="office_id" required
                        class="auth-select pl-11 appearance-none">
                    <option value="">Seleccione su oficina…</option>
                    @foreach ($oficinas as $oficina)
                        <option value="{{ $oficina->id }}" @selected((string) old('office_id') === (string) $oficina->id)>
                            {{ $oficina->code }} — {{ $oficina->name }}
                        </option>
                    @endforeach
                </select>
                <x-ui.icon name="chevron-down" class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
            </div>
            <p class="mt-1.5 text-[11px] text-slate-500">Su oficina determinará el destino de las solicitudes que cree.</p>
            @error('office_id')
                <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        {{-- Contraseña --}}
        <div>
            <label for="password" class="sr-only">Contraseña</label>
            <div class="auth-field">
                <x-ui.icon name="lock" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                <input id="password" name="password" type="password"
                       class="auth-input" placeholder="Contraseña"
                       required autocomplete="new-password">
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirmar contraseña --}}
        <div>
            <label for="password_confirmation" class="sr-only">Confirmar contraseña</label>
            <div class="auth-field">
                <x-ui.icon name="lock" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                <input id="password_confirmation" name="password_confirmation" type="password"
                       class="auth-input" placeholder="Confirmar contraseña"
                       required autocomplete="new-password">
            </div>
            @error('password_confirmation')
                <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <p class="rounded-xl border border-sky-400/20 bg-sky-400/[0.07] px-3.5 py-2.5 text-[11px] leading-snug text-sky-200">
            Su cuenta se creará con el rol <strong>Solicitante (Jefe de Oficina)</strong>.
        </p>

        <button type="submit" class="auth-btn-primary">
            <x-ui.icon name="user-plus" class="h-5 w-5" />
            Registrarme
        </button>
    </form>

    <div class="my-5 flex items-center gap-3 text-[11px] text-slate-500">
        <span class="h-px flex-1 bg-white/10"></span>
        o
        <span class="h-px flex-1 bg-white/10"></span>
    </div>

    <a href="{{ route('login') }}" class="auth-btn-outline">
        <x-ui.icon name="login" class="h-5 w-5" />
        Ya tengo una cuenta
    </a>
</x-guest-layout>
