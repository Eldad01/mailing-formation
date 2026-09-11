@extends('layouts.app')

@section('title', 'Nouvelle formation')

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('formations.index') }}" class="mb-4 inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            &larr; Retour aux formations
        </a>

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-5">
                <h1 class="text-xl font-semibold text-gray-900">Nouvelle formation</h1>
                <p class="mt-1 text-sm text-gray-500">Ces informations lancent une nouvelle liste de présence sur la plateforme.</p>
            </div>

            <form method="POST" action="{{ route('formations.store') }}" class="space-y-5 px-6 py-6">
                @csrf
                @include('formations._form')

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                    <a href="{{ route('formations.index') }}" class="text-sm font-medium text-gray-600 hover:underline">Annuler</a>
                    <button type="submit" class="rounded-md bg-bf-green-700 px-5 py-2 text-sm font-medium text-white hover:bg-bf-green-800">
                        Publier la formation
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
