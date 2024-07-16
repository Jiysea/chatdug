<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex justify-center items-center px-0 py-2 sm:px-4 sm:py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-tighter sm:tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
