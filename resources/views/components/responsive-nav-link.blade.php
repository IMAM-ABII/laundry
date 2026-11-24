@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-indigo-500 bg-indigo-50 py-2 pe-4 ps-3 text-start text-base font-semibold text-indigo-700 transition duration-150 focus:outline-none focus:bg-indigo-100'
            : 'block w-full border-l-4 border-transparent py-2 pe-4 ps-3 text-start text-base font-medium text-slate-600 transition duration-150 hover:border-slate-300 hover:bg-slate-50 hover:text-indigo-700 focus:outline-none focus:bg-slate-50';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
