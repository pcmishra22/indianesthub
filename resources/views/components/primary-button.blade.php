<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150', 'style' => 'background-color:#0078d4; --tw-ring-color:#0078d4;']) }}
    onmouseover="this.style.backgroundColor='#0a2d5e'"
    onmouseout="this.style.backgroundColor='#0078d4'"
    onfocus="this.style.backgroundColor='#0a2d5e'"
    onblur="this.style.backgroundColor='#0078d4'">
    {{ $slot }}
</button>
