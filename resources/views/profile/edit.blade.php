<x-app-layout>
    <x-slot name="header">Mi perfil</x-slot>

    <x-ui.page-header title="Mi perfil" subtitle="Administra tus datos personales y la seguridad de tu cuenta." />

    <div class="mt-6 grid gap-5 xl:grid-cols-[1.5fr_1fr]">
        <div class="space-y-5">
            <div class="panel">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="panel">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="panel h-fit">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
