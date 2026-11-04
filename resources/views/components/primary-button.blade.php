<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-transparent bg-primary-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60']) }}>
    {{ $slot }}
</button>
