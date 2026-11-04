<section class="p-6">
    <header class="border-b border-slate-100 pb-4">
        <h2 class="panel-title">
            <x-ui.icon name="user" class="h-4 w-4 text-blue-600" />
            Información del perfil
        </h2>
        <p class="mt-1.5 text-[13px] text-slate-500">
            Actualiza el nombre y el correo electrónico asociados a tu cuenta.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-5 space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nombre completo" />
            <x-text-input id="name" name="name" type="text" class="field mt-1.5" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="field-error" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" name="email" type="email" class="field mt-1.5" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="field-error" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-[13px] text-slate-600">
                        Tu correo electrónico no está verificado.
                        <button form="send-verification" class="font-medium text-blue-600 underline-offset-2 hover:text-blue-800 hover:underline">
                            Reenviar el correo de verificación
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-[13px] font-medium text-emerald-600">
                            Se envió un nuevo enlace de verificación a tu correo electrónico.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button class="btn btn-primary" type="submit">Guardar cambios</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-[13px] font-medium text-emerald-600">Guardado.</p>
            @endif
        </div>
    </form>
</section>
