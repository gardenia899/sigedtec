<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nombre -->
        <div>
            <x-input-label for="name" value="Nombre completo" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Ej. María Pérez" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Correo -->
        <div class="mt-4">
            <x-input-label for="email" value="Correo institucional" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nombre@institucion.gob.ve" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Oficina -->
        <div class="mt-4">
            <x-input-label for="office_id" value="Oficina *" />
            <select id="office_id" name="office_id" required
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                <option value="">Seleccione su oficina…</option>
                @foreach ($oficinas as $oficina)
                    <option value="{{ $oficina->id }}" @selected((string) old('office_id') === (string) $oficina->id)>
                        {{ $oficina->code }} — {{ $oficina->name }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500">Su oficina determinará el destino de las solicitudes que cree.</p>
            <x-input-error :messages="$errors->get('office_id')" class="mt-2" />
        </div>

        <!-- Contraseña -->
        <div class="mt-4">
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmar contraseña -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar contraseña" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <p class="mt-3 text-xs text-gray-500">
            Su cuenta se creará con el rol <strong>Solicitante (Jefe de Oficina)</strong>.
        </p>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                ¿Ya está registrado?
            </a>

            <x-primary-button class="ms-4">
                Registrarme
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>