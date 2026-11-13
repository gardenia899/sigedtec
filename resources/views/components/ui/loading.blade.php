<div
    x-data="{
        visible: false,
        mostrar() {
            this.visible = true;
            sessionStorage.setItem('sigedtec-loading-start', Date.now().toString());
        },
        continuar() {
            const inicio = Number(sessionStorage.getItem('sigedtec-loading-start'));

            if (! inicio) return;

            this.visible = true;
            window.setTimeout(() => {
                this.visible = false;
                sessionStorage.removeItem('sigedtec-loading-start');
            }, Math.max(0, 1000 - (Date.now() - inicio)));
        }
    }"
    x-init="
        continuar();
        document.addEventListener('submit', (evento) => {
            const formulario = evento.target;

            if (formulario instanceof HTMLFormElement && formulario.method.toUpperCase() !== 'GET') {
                mostrar();
            }
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
