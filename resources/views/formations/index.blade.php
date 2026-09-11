@extends('layouts.app')

@section('title', 'Toutes les formations')

@section('content')
    <div class="mb-8 overflow-hidden rounded-2xl bg-bf-green-800">
        <div class="relative px-6 py-10 sm:px-10">
            <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(135deg, #fff 0 2px, transparent 2px 14px);"></div>
            <div class="relative">
                <p class="text-sm font-semibold tracking-widest text-bf-gold-400 uppercase">Version de démonstration</p>
                <h1 class="mt-2 max-w-2xl text-2xl font-semibold text-white sm:text-3xl">
                    Plateforme de test — gestion de listes de présence
                </h1>
                <p class="mt-2 max-w-xl text-sm text-bf-green-100">
                    Ce site est un prototype à usage interne, non officiel. Il sert uniquement à tester le fonctionnement de la plateforme.
                </p>
            </div>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-bf-green-50 text-bf-green-700">
                <x-icon.calendar class="h-6 w-6" />
            </span>
            <div>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['formations'] }}</p>
                <p class="text-sm text-gray-500">Formations au total</p>
            </div>
        </div>
        <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-bf-gold-100 text-yellow-700">
                <x-icon.star class="h-6 w-6" />
            </span>
            <div>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['a_venir'] }}</p>
                <p class="text-sm text-gray-500">À venir</p>
            </div>
        </div>
        <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-bf-red-50 text-bf-red-700">
                <x-icon.users class="h-6 w-6" />
            </span>
            <div>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['inscrits'] }}</p>
                <p class="text-sm text-gray-500">Inscriptions enregistrées</p>
            </div>
        </div>
    </div>

    @if ($formations->isEmpty())
        <div class="flex flex-col items-center rounded-xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center">
            <span class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                <x-icon.inbox class="h-7 w-7" />
            </span>
            <p class="mt-4 font-medium text-gray-700">Aucune formation pour le moment</p>
            <p class="mt-1 text-sm text-gray-500">Les nouvelles formations apparaîtront ici dès leur publication.</p>
            @auth
                <a href="{{ route('formations.create') }}" class="mt-5 inline-flex items-center gap-1.5 rounded-md bg-bf-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-bf-green-800">
                    <x-icon.plus class="h-4 w-4" />
                    Créer la première formation
                </a>
            @endauth
        </div>
    @else
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($formations as $formation)
                @php
                    $tauxRemplissage = $formation->places > 0
                        ? min(100, round(($formation->inscriptions_count / $formation->places) * 100))
                        : 0;
                    $complete = $formation->inscriptions_count >= $formation->places;
                @endphp
                <a
                    href="{{ route('formations.show', $formation) }}"
                    class="group flex flex-col rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="mb-3 flex items-start justify-between gap-3">
                        <h2 class="font-semibold text-gray-900 group-hover:text-bf-green-700">{{ $formation->titre }}</h2>
                        @if ($complete)
                            <x-badge tone="red">Complet</x-badge>
                        @elseif ($formation->places - $formation->inscriptions_count <= 3)
                            <x-badge tone="gold">Places limitées</x-badge>
                        @else
                            <x-badge tone="green">Ouvert</x-badge>
                        @endif
                    </div>

                    <div class="space-y-1.5 text-sm text-gray-500">
                        <p class="flex items-center gap-1.5">
                            <x-icon.calendar class="h-4 w-4 text-gray-400" />
                            {{ $formation->debut_a->translatedFormat('d M Y') }}
                        </p>
                        @if ($formation->lieu)
                            <p class="flex items-center gap-1.5">
                                <x-icon.map-pin class="h-4 w-4 text-gray-400" />
                                {{ $formation->lieu }}
                            </p>
                        @endif
                    </div>

                    <div class="mt-4">
                        <div class="mb-1 flex items-center justify-between text-xs text-gray-500">
                            <span>{{ $formation->inscriptions_count }} / {{ $formation->places }} inscrits</span>
                            <span>{{ $tauxRemplissage }}%</span>
                        </div>
                        <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100">
                            <div
                                class="h-full rounded-full {{ $complete ? 'bg-bf-red-600' : 'bg-bf-green-600' }}"
                                style="width: {{ $tauxRemplissage }}%"
                            ></div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $formations->links() }}
        </div>
    @endif
@endsection
