<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Formations') — {{ config('app.name') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/drapeau-burkina-faso.svg') }}">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900 antialiased">
        <div class="flex h-1.5 print:hidden">
            <div class="flex-1 bg-bf-red-600"></div>
            <div class="flex-1 bg-bf-green-600"></div>
        </div>

        <header class="sticky top-0 z-10 border-b border-gray-200 bg-white/95 backdrop-blur print:hidden">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-6 py-3">
                <a href="{{ route('formations.index') }}" class="flex items-center gap-3">
                    <img
                        src="{{ asset('images/drapeau-burkina-faso.svg') }}"
                        alt="Drapeau du Burkina Faso"
                        class="h-9 w-auto shrink-0 rounded-md shadow-sm ring-1 ring-black/10"
                    >
                    <span class="leading-tight">
                        <span class="block text-[11px] font-semibold tracking-widest text-bf-green-700 uppercase">Burkina Faso</span>
                        <span class="hidden text-sm font-semibold text-gray-900 sm:block sm:text-base">
                            Ministère des Sports, de la Jeunesse et de l'Emploi
                        </span>
                        <span class="block text-sm font-semibold text-gray-900 sm:hidden">
                            MSJE Formations
                        </span>
                    </span>
                </a>

                <div class="flex items-center gap-2 sm:gap-3">
                    @auth
                        <a
                            href="{{ route('formations.create') }}"
                            class="inline-flex items-center gap-1.5 rounded-md bg-bf-green-700 px-3.5 py-2 text-sm font-medium text-white shadow-sm hover:bg-bf-green-800"
                        >
                            <x-icon.plus class="h-4 w-4" />
                            <span class="hidden sm:inline">Nouvelle formation</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-md px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900">
                                <x-icon.logout class="h-4 w-4" />
                                <span class="hidden sm:inline">Déconnexion</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 px-3.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <x-icon.lock class="h-4 w-4" />
                            Connexion
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-6 py-8 print:max-w-none print:px-0 print:py-0">
            @if (session('status'))
                <div class="mb-6 flex items-start gap-3 rounded-lg border border-bf-green-100 bg-bf-green-50 px-4 py-3 text-sm text-bf-green-800 print:hidden">
                    <x-icon.check-circle class="h-5 w-5 shrink-0 text-bf-green-600" />
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="mt-16 border-t-2 border-bf-gold-400 bg-bf-green-900 text-bf-green-50 print:hidden">
            <div class="mx-auto flex max-w-6xl flex-col items-center gap-3 px-6 py-6 text-center text-sm sm:flex-row sm:justify-between sm:text-left">
                <div class="flex items-center gap-3">
                    <img
                        src="{{ asset('images/drapeau-burkina-faso.svg') }}"
                        alt="Drapeau du Burkina Faso"
                        class="h-6 w-auto rounded shadow-sm ring-1 ring-white/20"
                    >
                    <p class="font-medium">Ministère des Sports, de la Jeunesse et de l'Emploi — Burkina Faso</p>
                </div>
                <p class="text-bf-green-200">Sport · Jeunesse · Emploi — © {{ date('Y') }}</p>
            </div>
        </footer>
    </body>
</html>
