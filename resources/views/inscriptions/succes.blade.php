@extends('layouts.app')

@section('title', 'Inscription réussie')

@section('content')
    <div class="mx-auto max-w-md text-center">
        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-bf-green-50 text-bf-green-600">
            <x-icon.check-circle class="h-9 w-9" />
        </span>

        <h1 class="mt-5 text-2xl font-semibold text-gray-900">Inscription réussie !</h1>
        <p class="mt-2 text-gray-600">Votre inscription à la formation a bien été enregistrée.</p>

        <div class="mt-8 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('formations.show', $formation) }}" class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Voir la formation
            </a>
            <a href="{{ route('formations.index') }}" class="inline-flex items-center gap-1.5 rounded-md bg-bf-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-bf-green-800">
                Retour à l'accueil
            </a>
        </div>
    </div>
@endsection
