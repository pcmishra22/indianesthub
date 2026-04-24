@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#0078d4] focus:ring-[#0078d4] rounded-md shadow-sm']) }}>
