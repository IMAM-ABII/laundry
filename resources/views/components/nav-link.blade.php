@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center border-b-2 border-indigo-600 px-1 pt-1 text-sm font-semibold leading-5 text-indigo-700 transition duration-150 focus:outline-none focus:border-indigo-700'
            : 'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium leading-5 text-slate-500 transition duration-150 hover:border-slate-300 hover:text-indigo-700 focus:outline-none focus:border-indigo-500';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
