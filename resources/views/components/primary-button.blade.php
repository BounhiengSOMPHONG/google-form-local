<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-brand border border-transparent rounded-md font-semibold text-xs text-brand uppercase tracking-widest hover:opacity-90 focus:opacity-90 active:opacity-95 focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
