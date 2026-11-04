{{--
    Pantalla de carga del sistema.
    Se muestra al entrar a una página, al enviar un formulario y al abrir un
    enlace interno. Permanece al menos dos segundos desde que aparece, aunque
    la página termine de cargar antes, y se retira al cumplirse ese tiempo.
--}}
<div
    x-data="{
        visible: true,
        inicio: Date.now(),
        ocultar() {
            const restante = Math.max(0, 2000 - (Date.now() - this.inicio));

            window.setTimeout(() => { this.visible = false; }, restante);
        },
        mostrar() {
            this.inicio = Date.now();
            this.visible = true;
        }
    }"
    x-init="
        document.readyState === 'complete'
            ? ocultar()
            : window.addEventListener('load', () => ocultar(), { once: true });
        document.addEventListener('submit', () => mostrar());
        document.addEventListener('click', (evento) => {
            const enlace = evento.target.closest('a[href]');

            if (! enlace || enlace.target === '_blank' || enlace.hasAttribute('download')) {
                return;
            }

            const destino = new URL(enlace.href, window.location.href);

            if (destino.origin !== window.location.origin || destino.href.includes('#')) {
                return;
            }

            mostrar();
        });
    "
    x-show="visible"
    x-cloak
    x-transition.opacity.duration.300ms
    class="fixed inset-0 z-[100] flex flex-col items-center justify-center gap-5 bg-[#040D1C]"
    role="status"
    aria-live="polite"
    aria-label="Cargando el sistema"
>
    <div class="flex items-center gap-3">
        <x-ui.icon name="logo" class="h-12 w-12 text-sky-400" />
        <span class="text-3xl font-extrabold leading-none tracking-tight text-white">
            Siged<span class="text-sky-400">Tec</span>
        </span>
    </div>

    <span class="h-10 w-10 rounded-full border-2 border-white/15 border-t-sky-400 motion-safe:animate-spin" aria-hidden="true"></span>

    <p class="text-[13px] text-slate-400">Cargando información…</p>
</div>
