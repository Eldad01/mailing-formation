@php
    $formation ??= null;
@endphp

<div>
    <label for="titre" class="block text-sm font-medium text-gray-700">Titre de la formation</label>
    <input
        type="text"
        name="titre"
        id="titre"
        value="{{ old('titre', $formation?->titre) }}"
        placeholder="Ex. Initiation à la gestion de projets sportifs"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bf-green-500 focus:ring-bf-green-500"
    >
    @error('titre')
        <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
    <textarea
        name="description"
        id="description"
        rows="4"
        placeholder="Objectifs, public visé, prérequis..."
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bf-green-500 focus:ring-bf-green-500"
    >{{ old('description', $formation?->description) }}</textarea>
    @error('description')
        <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="lieu" class="block text-sm font-medium text-gray-700">Lieu</label>
        <input
            type="text"
            name="lieu"
            id="lieu"
            value="{{ old('lieu', $formation?->lieu) }}"
            placeholder="Ex. Ouagadougou"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bf-green-500 focus:ring-bf-green-500"
        >
        @error('lieu')
            <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="places" class="block text-sm font-medium text-gray-700">Places disponibles</label>
        <input
            type="number"
            min="1"
            name="places"
            id="places"
            value="{{ old('places', $formation?->places ?? 20) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bf-green-500 focus:ring-bf-green-500"
        >
        @error('places')
            <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div>
    <label for="debut_a" class="block text-sm font-medium text-gray-700">Date et heure de début</label>
    <input
        type="datetime-local"
        name="debut_a"
        id="debut_a"
        value="{{ old('debut_a', $formation?->debut_a?->format('Y-m-d\TH:i')) }}"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bf-green-500 focus:ring-bf-green-500"
    >
    @error('debut_a')
        <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
    @enderror
</div>
