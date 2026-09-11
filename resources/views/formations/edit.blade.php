@extends('layouts.app')

@section('title', 'Modifier la formation')

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('formations.show', $formation) }}" class="mb-4 inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            &larr; Retour à la formation
        </a>

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-5">
                <h1 class="text-xl font-semibold text-gray-900">Modifier la formation</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $formation->titre }}</p>
            </div>

            <form method="POST" action="{{ route('formations.update', $formation) }}" class="space-y-5 px-6 py-6">
                @csrf
                @method('PUT')
                @include('formations._form', ['formation' => $formation])

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                    <a href="{{ route('formations.show', $formation) }}" class="text-sm font-medium text-gray-600 hover:underline">Annuler</a>
                    <button type="submit" class="rounded-md bg-bf-green-700 px-5 py-2 text-sm font-medium text-white hover:bg-bf-green-800">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 flex items-center justify-between rounded-xl border border-bf-red-100 bg-bf-red-50 px-5 py-4">
            <div>
                <p class="text-sm font-medium text-bf-red-800">Zone sensible</p>
                <p class="text-sm text-bf-red-700">La suppression est définitive et retire aussi les inscriptions liées.</p>
            </div>
            <form method="POST" action="{{ route('formations.destroy', $formation) }}" onsubmit="return confirm('Supprimer définitivement cette formation ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex shrink-0 items-center gap-1.5 rounded-md border border-bf-red-300 bg-white px-3.5 py-2 text-sm font-medium text-bf-red-700 hover:bg-bf-red-100">
                    <x-icon.trash class="h-4 w-4" />
                    Supprimer
                </button>
            </form>
        </div>
    </div>
@endsection
