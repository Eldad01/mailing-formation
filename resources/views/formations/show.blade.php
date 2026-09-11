@extends('layouts.app')

@section('title', $formation->titre)

@section('content')
    @php
        $inscritsCount = $formation->inscriptions->count();
        $tauxRemplissage = $formation->places > 0 ? min(100, round(($inscritsCount / $formation->places) * 100)) : 0;
    @endphp

    <a href="{{ route('formations.index') }}" class="mb-4 inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
        &larr; Retour aux formations
    </a>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 bg-bf-green-800 px-6 py-8 sm:px-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    @if ($formation->estComplete())
                        <x-badge tone="red" class="mb-3">Complet</x-badge>
                    @elseif (! $formation->inscriptions_ouvertes)
                        <x-badge tone="red" class="mb-3">Inscriptions clôturées</x-badge>
                    @else
                        <x-badge tone="gold" class="mb-3">Inscriptions ouvertes</x-badge>
                    @endif
                    <h1 class="text-2xl font-semibold text-white sm:text-3xl">{{ $formation->titre }}</h1>
                    <div class="mt-3 flex flex-wrap gap-x-5 gap-y-1 text-sm text-bf-green-100">
                        <span class="flex items-center gap-1.5">
                            <x-icon.calendar class="h-4 w-4" />
                            {{ $formation->debut_a->translatedFormat('d M Y') }}
                        </span>
                        @if ($formation->lieu)
                            <span class="flex items-center gap-1.5">
                                <x-icon.map-pin class="h-4 w-4" />
                                {{ $formation->lieu }}
                            </span>
                        @endif
                    </div>
                </div>

                @auth
                    <div class="flex shrink-0 flex-wrap items-center gap-2">
                        <a
                            href="{{ route('formations.participants.index', $formation) }}"
                            class="inline-flex items-center gap-1.5 rounded-md bg-white/10 px-3.5 py-2 text-sm font-medium text-white ring-1 ring-white/30 hover:bg-white/20"
                        >
                            <x-icon.users class="h-4 w-4" />
                            Gérer la liste de présence
                        </a>
                        <a
                            href="{{ route('formations.edit', $formation) }}"
                            class="inline-flex items-center gap-1.5 rounded-md bg-white/10 px-3.5 py-2 text-sm font-medium text-white ring-1 ring-white/30 hover:bg-white/20"
                        >
                            <x-icon.pencil class="h-4 w-4" />
                            Modifier
                        </a>
                        <form
                            method="POST"
                            action="{{ route('formations.toggle-inscriptions', $formation) }}"
                            onsubmit="return confirm('{{ $formation->inscriptions_ouvertes ? 'Clôturer les inscriptions à cette formation ?' : 'Rouvrir les inscriptions à cette formation ?' }}');"
                        >
                            @csrf
                            @if ($formation->inscriptions_ouvertes)
                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-bf-red-600/90 px-3.5 py-2 text-sm font-medium text-white ring-1 ring-white/30 hover:bg-bf-red-700">
                                    <x-icon.lock class="h-4 w-4" />
                                    Clôturer les inscriptions
                                </button>
                            @else
                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-white/10 px-3.5 py-2 text-sm font-medium text-white ring-1 ring-white/30 hover:bg-white/20">
                                    <x-icon.check-circle class="h-4 w-4" />
                                    Rouvrir les inscriptions
                                </button>
                            @endif
                        </form>
                    </div>
                @endauth
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 p-6 sm:p-10 lg:grid-cols-5">
            <div class="lg:col-span-3">
                @if ($formation->description)
                    <h2 class="mb-2 font-medium text-gray-900">À propos de cette formation</h2>
                    <p class="whitespace-pre-line text-gray-600">{{ $formation->description }}</p>
                @else
                    <p class="text-sm text-gray-400 italic">Aucune description fournie.</p>
                @endif

                <div class="mt-8">
                    <div class="mb-1 flex items-center justify-between text-sm">
                        <span class="flex items-center gap-1.5 font-medium text-gray-700">
                            <x-icon.users class="h-4 w-4 text-gray-400" />
                            {{ $inscritsCount }} / {{ $formation->places }} places occupées
                        </span>
                        <span class="text-gray-500">{{ $tauxRemplissage }}%</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100">
                        <div
                            class="h-full rounded-full {{ $formation->estComplete() ? 'bg-bf-red-600' : 'bg-bf-green-600' }}"
                            style="width: {{ $tauxRemplissage }}%"
                        ></div>
                    </div>
                </div>

                <h2 class="mt-8 mb-3 font-medium text-gray-900">Participants inscrits ({{ $inscritsCount }})</h2>
                @if ($formation->inscriptions->isEmpty())
                    <p class="text-sm text-gray-500">Aucune inscription pour le moment.</p>
                @else
                    <ul class="divide-y divide-gray-100 rounded-lg border border-gray-200">
                        @foreach ($formation->inscriptions as $inscription)
                            <li class="flex items-center gap-3 px-4 py-3 text-sm">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-bf-green-50 text-xs font-semibold text-bf-green-700">
                                    {{ mb_strtoupper(mb_substr($inscription->prenom, 0, 1)) }}
                                </span>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $inscription->prenom }} {{ $inscription->nom }}</p>
                                    <p class="text-gray-500">{{ $inscription->direction_service }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                    @if ($dejaInscrit)
                        <div class="flex items-start gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-bf-green-50 text-bf-green-600">
                                <x-icon.check-circle class="h-5 w-5" />
                            </span>
                            <div>
                                <h2 class="font-medium text-gray-900">Vous êtes inscrit</h2>
                                <p class="mt-1 text-sm text-gray-500">Votre présence à cette formation est enregistrée sur la liste ci-contre.</p>
                            </div>
                        </div>
                    @else
                        <h2 class="mb-1 font-medium text-gray-900">Liste de présence</h2>
                        <p class="mb-4 text-sm text-gray-500">Inscrivez-vous pour être noté sur la liste de présence de cette formation.</p>
                    @endif

                    @if ($dejaInscrit)
                        {{-- déjà inscrit : formulaire masqué --}}
                    @elseif ($formation->estComplete())
                        <div class="flex items-start gap-2 rounded-md border border-bf-red-100 bg-bf-red-50 px-4 py-3 text-sm text-bf-red-800">
                            <x-icon.exclamation class="h-5 w-5 shrink-0" />
                            <span>Cette formation a atteint son nombre maximal de places.</span>
                        </div>
                    @elseif (! $formation->inscriptions_ouvertes)
                        <div class="flex items-start gap-2 rounded-md border border-bf-red-100 bg-bf-red-50 px-4 py-3 text-sm text-bf-red-800">
                            <x-icon.lock class="h-5 w-5 shrink-0" />
                            <span>Les inscriptions à cette formation sont clôturées.</span>
                        </div>
                    @else
                        <form method="POST" action="{{ route('formations.inscriptions.store', $formation) }}" class="space-y-4">
                            @csrf

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                                    <input
                                        type="text"
                                        name="nom"
                                        id="nom"
                                        value="{{ old('nom') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bf-green-500 focus:ring-bf-green-500"
                                    >
                                    @error('nom')
                                        <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom(s)</label>
                                    <input
                                        type="text"
                                        name="prenom"
                                        id="prenom"
                                        value="{{ old('prenom') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bf-green-500 focus:ring-bf-green-500"
                                    >
                                    @error('prenom')
                                        <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="telephone" class="block text-sm font-medium text-gray-700">Numéro de téléphone</label>
                                <input
                                    type="tel"
                                    name="telephone"
                                    id="telephone"
                                    value="{{ old('telephone') }}"
                                    placeholder="+226 XX XX XX XX"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bf-green-500 focus:ring-bf-green-500"
                                >
                                @error('telephone')
                                    <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Adresse e-mail</label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="{{ old('email') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bf-green-500 focus:ring-bf-green-500"
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="direction_service" class="block text-sm font-medium text-gray-700">Direction / Service</label>
                                <input
                                    type="text"
                                    name="direction_service"
                                    id="direction_service"
                                    value="{{ old('direction_service') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bf-green-500 focus:ring-bf-green-500"
                                >
                                @error('direction_service')
                                    <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="flex w-full items-center justify-center gap-1.5 rounded-md bg-bf-green-700 px-4 py-2.5 text-sm font-medium text-white hover:bg-bf-green-800">
                                S'inscrire
                                <x-icon.arrow-right class="h-4 w-4" />
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
