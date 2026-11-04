@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'h-10 w-full rounded-lg border-slate-300 bg-white text-sm text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500']) }}>
