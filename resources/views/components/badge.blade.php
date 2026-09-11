@props(['tone' => 'gray'])

@php
    $tones = [
        'green' => 'bg-bf-green-50 text-bf-green-800 ring-bf-green-600/20',
        'gold' => 'bg-bf-gold-100 text-yellow-800 ring-bf-gold-600/30',
        'red' => 'bg-bf-red-50 text-bf-red-800 ring-bf-red-600/20',
        'gray' => 'bg-gray-100 text-gray-700 ring-gray-500/10',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset '.($tones[$tone] ?? $tones['gray'])]) }}>
    {{ $slot }}
</span>
