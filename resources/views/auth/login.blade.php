@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
    <div class="mx-auto max-w-sm">
        <div class="mb-6 flex justify-center">
            <a
                href="{{ route('formations.index') }}"
                class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                <x-icon.arrow-right class="h-4 w-4 rotate-180" />
                Retour à l'accueil
            </a>
        </div>

        <div class="mb-6 flex flex-col items-center text-center">
            <img
                src="{{ asset('images/drapeau-burkina-faso.svg') }}"
                alt="Drapeau du Burkina Faso"
                class="h-10 w-auto rounded-md shadow-sm ring-1 ring-black/10"
            >
            <h1 class="mt-4 text-xl font-semibold text-gray-900">Espace administration (test)</h1>
            <p class="mt-1 text-sm text-gray-500">Environnement de démonstration, non officiel.</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        autofocus
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bf-green-500 focus:ring-bf-green-500"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bf-green-500 focus:ring-bf-green-500"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-bf-green-700 focus:ring-bf-green-500">
                    Se souvenir de moi
                </label>

                <button type="submit" class="w-full rounded-md bg-bf-green-700 px-4 py-2.5 text-sm font-medium text-white hover:bg-bf-green-800">
                    Se connecter
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-gray-400">
            Plateforme des formations — Ministère des Sports, de la Jeunesse et de l'Emploi
        </p>
    </div>
@endsection
