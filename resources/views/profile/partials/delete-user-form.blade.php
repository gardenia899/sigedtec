<section class="p-6">
    <header class="border-b border-slate-100 pb-4">
        <h2 class="panel-title text-rose-600">
            <x-ui.icon name="bell" class="h-4 w-4" />
            Eliminar cuenta
        </h2>
        <p class="mt-1.5 text-[13px] leading-relaxed text-slate-500">
            Al eliminar tu cuenta se borrarán de forma permanente todos sus datos. Antes de continuar,
            descarga cualquier información que necesites conservar.
        </p>
    </header>

    <div class="mt-5">
        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >Eliminar cuenta</x-danger-button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-slate-900">
                ¿Seguro que deseas eliminar tu cuenta?
            </h2>

            <p class="mt-1.5 text-[13px] leading-relaxed text-slate-600">
                Esta acción es permanente. Escribe tu contraseña para confirmar que deseas
                eliminar la cuenta y todos sus datos.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Contraseña" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="field mt-1 w-3/4"
                    placeholder="Contraseña"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="field-error" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>

                <x-danger-button>
                    Eliminar cuenta
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
