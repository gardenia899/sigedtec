<section class="p-6">
    <header class="border-b border-slate-100 pb-4">
        <h2 class="panel-title">
            <x-ui.icon name="lock" class="h-4 w-4 text-blue-600" />
            Cambiar contraseña
        </h2>
        <p class="mt-1.5 text-[13px] text-slate-500">
            Usa una contraseña larga y aleatoria para mantener tu cuenta segura.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-5 space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" value="Contraseña actual" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="field mt-1.5" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="field-error" />
        </div>

        <div>
            <x-input-label for="update_password_password" value="Nueva contraseña" />
            <x-text-input id="update_password_password" name="password" type="password" class="field mt-1.5" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="field-error" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" value="Confirmar contraseña" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="field mt-1.5" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="field-error" />
        </div>

        <div class="flex items-center gap-4">
            <button class="btn btn-primary" type="submit">Actualizar contraseña</button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-[13px] font-medium text-emerald-600">Guardado.</p>
            @endif
        </div>
    </form>
</section>
