<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-lg border border-transparent bg-indigo-600 px-4 py-2.5 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition duration-150 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-indigo-800']) }}>
    {{ $slot }}
</button>
