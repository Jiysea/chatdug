<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-center items-center px-1 py-2 sm:px-4 sm:py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
