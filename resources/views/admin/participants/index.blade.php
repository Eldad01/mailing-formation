@extends('layouts.app')

@section('title', 'Liste de présence — '.$formation->titre)

@section('content')
    <style>
        @page {
            size: landscape;
            margin: 1.2cm;
        }
    </style>

    <a href="{{ route('formations.show', $formation) }}" class="mb-4 inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 print:hidden">
        &larr; Retour à la formation
    </a>

    <div class="mb-6 flex flex-wrap items-start justify-between gap-4 print:hidden">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Liste de présence</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $formation->titre }} — {{ $inscriptions->count() }} / {{ $formation->places }} inscrits</p>
        </div>

        @if ($inscriptions->isNotEmpty())
            <button
                type="button"
                onclick="window.print()"
                class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                <x-icon.printer class="h-4 w-4" />
                Imprimer
            </button>
        @endif
    </div>

    <div class="hidden print:block print:mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase">Ministère des Sports, de la Jeunesse et de l'Emploi — Burkina Faso</p>
                <h1 class="mt-1 text-xl font-bold text-gray-900">Liste de présence</h1>
            </div>
            <p class="text-xs text-gray-500">Éditée le {{ now()->translatedFormat('d M Y à H:i') }}</p>
        </div>
        <div class="mt-3 flex flex-wrap gap-x-8 gap-y-1 text-sm text-gray-700">
            <span><strong>Formation :</strong> {{ $formation->titre }}</span>
            <span><strong>Date :</strong> {{ $formation->debut_a->translatedFormat('d M Y à H:i') }}</span>
            @if ($formation->lieu)
                <span><strong>Lieu :</strong> {{ $formation->lieu }}</span>
            @endif
            <span><strong>Participants :</strong> {{ $inscriptions->count() }} / {{ $formation->places }}</span>
        </div>
    </div>

    <form id="bulk-email-form" method="POST" action="{{ route('formations.participants.email', $formation) }}" enctype="multipart/form-data"></form>

    @if ($inscriptions->isEmpty())
        <div class="flex flex-col items-center rounded-xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center">
            <span class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                <x-icon.inbox class="h-7 w-7" />
            </span>
            <p class="mt-4 font-medium text-gray-700">Aucun participant pour le moment</p>
        </div>
    @else
        <div class="mb-4 flex items-center gap-2 sm:hidden print:hidden">
            <input type="checkbox" id="select-all-mobile" class="rounded border-gray-300 text-bf-green-700 focus:ring-bf-green-500">
            <label for="select-all-mobile" class="text-sm text-gray-600">Tout sélectionner</label>
        </div>

        <div class="mb-6 space-y-3 sm:hidden print:hidden">
            @foreach ($inscriptions as $inscription)
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <label class="flex items-start gap-3">
                            <input
                                type="checkbox"
                                name="inscriptions[]"
                                value="{{ $inscription->id }}"
                                form="bulk-email-form"
                                class="participant-checkbox mt-1 rounded border-gray-300 text-bf-green-700 focus:ring-bf-green-500"
                            >
                            <span class="font-medium text-gray-800">{{ $inscription->prenom }} {{ $inscription->nom }}</span>
                        </label>
                        <form method="POST" action="{{ route('participants.destroy', $inscription) }}" onsubmit="return confirm('Retirer {{ $inscription->prenom }} {{ $inscription->nom }} de la liste ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-bf-red-600">
                                <x-icon.trash class="h-4 w-4" />
                            </button>
                        </form>
                    </div>
                    <dl class="mt-3 space-y-1 pl-7 text-sm text-gray-600">
                        <div class="flex gap-1"><dt class="text-gray-400">Tél. :</dt><dd>{{ $inscription->telephone }}</dd></div>
                        <div class="flex gap-1"><dt class="text-gray-400">Email :</dt><dd class="break-all">{{ $inscription->email }}</dd></div>
                        <div class="flex gap-1"><dt class="text-gray-400">Direction :</dt><dd>{{ $inscription->direction_service }}</dd></div>
                    </dl>
                </div>
            @endforeach
        </div>

        <div class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm sm:block print:block print:rounded-none print:border-0 print:shadow-none">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm print:w-full print:border print:border-collapse print:divide-y-0 print:border-gray-400">
                    <thead class="bg-gray-50 print:bg-transparent">
                        <tr>
                            <th class="w-10 px-4 py-3 print:hidden">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-bf-green-700 focus:ring-bf-green-500">
                            </th>
                            <th class="px-3 py-3 text-left font-medium text-gray-600 print:border print:border-gray-400 print:px-2 print:py-1.5">Nom</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-600 print:border print:border-gray-400 print:px-2 print:py-1.5">Prénom(s)</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-600 print:border print:border-gray-400 print:px-2 print:py-1.5">Téléphone</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-600 print:border print:border-gray-400 print:px-2 print:py-1.5">Email</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-600 print:border print:border-gray-400 print:px-2 print:py-1.5">Direction / Service</th>
                            <th class="hidden text-left font-medium text-gray-600 print:table-cell print:border print:border-gray-400 print:px-2 print:py-1.5">Signature</th>
                            <th class="px-3 py-3 text-right font-medium text-gray-600 print:hidden">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 print:divide-y-0">
                        @foreach ($inscriptions as $inscription)
                            <tr>
                                <td class="px-4 py-3 print:hidden">
                                    <input
                                        type="checkbox"
                                        name="inscriptions[]"
                                        value="{{ $inscription->id }}"
                                        form="bulk-email-form"
                                        class="participant-checkbox rounded border-gray-300 text-bf-green-700 focus:ring-bf-green-500"
                                    >
                                </td>
                                <td class="px-3 py-3 font-medium text-gray-800 print:border print:border-gray-400 print:px-2 print:py-1.5">{{ $inscription->nom }}</td>
                                <td class="px-3 py-3 text-gray-600 print:border print:border-gray-400 print:px-2 print:py-1.5">{{ $inscription->prenom }}</td>
                                <td class="px-3 py-3 text-gray-600 print:border print:border-gray-400 print:px-2 print:py-1.5">{{ $inscription->telephone }}</td>
                                <td class="px-3 py-3 text-gray-600 print:border print:border-gray-400 print:px-2 print:py-1.5">{{ $inscription->email }}</td>
                                <td class="px-3 py-3 text-gray-600 print:border print:border-gray-400 print:px-2 print:py-1.5">{{ $inscription->direction_service }}</td>
                                <td class="hidden print:table-cell print:border print:border-gray-400 print:px-2 print:py-4"></td>
                                <td class="px-3 py-3 text-right print:hidden">
                                    <form method="POST" action="{{ route('participants.destroy', $inscription) }}" onsubmit="return confirm('Retirer {{ $inscription->prenom }} {{ $inscription->nom }} de la liste ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 text-bf-red-600 hover:underline">
                                            <x-icon.trash class="h-4 w-4" />
                                            Retirer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 rounded-xl border border-gray-200 bg-gray-50 p-5 print:hidden">
            <h2 class="mb-1 font-medium text-gray-900">Envoyer un email aux participants sélectionnés</h2>
            <p class="mb-4 text-sm text-gray-500">Cochez les participants ci-dessus, puis rédigez votre message (attestations, informations, etc.).</p>

            <div class="space-y-4">
                <div>
                    <label for="objet" class="block text-sm font-medium text-gray-700">Objet</label>
                    <input
                        type="text"
                        name="objet"
                        id="objet"
                        form="bulk-email-form"
                        value="{{ old('objet') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bf-green-500 focus:ring-bf-green-500"
                    >
                    @error('objet')
                        <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea
                        name="message"
                        id="message"
                        form="bulk-email-form"
                        rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bf-green-500 focus:ring-bf-green-500"
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
                    @enderror
                    @error('inscriptions')
                        <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="pieces_jointes" class="block text-sm font-medium text-gray-700">Pièces jointes (facultatif)</label>
                    <input
                        type="file"
                        name="pieces_jointes[]"
                        id="pieces_jointes"
                        form="bulk-email-form"
                        multiple
                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                        class="mt-1 block w-full text-sm text-gray-700 file:mr-3 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200"
                    >
                    <p class="mt-1 text-xs text-gray-400">PDF, image ou document Word — 4 Mo maximum par fichier, 5 fichiers maximum (ex. attestations).</p>
                    @error('pieces_jointes')
                        <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
                    @enderror
                    @error('pieces_jointes.*')
                        <p class="mt-1 text-sm text-bf-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" form="bulk-email-form" class="inline-flex items-center gap-1.5 rounded-md bg-bf-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-bf-green-800">
                    Envoyer aux sélectionnés
                </button>
            </div>
        </div>
    @endif

    <script>
        document.querySelectorAll('#select-all, #select-all-mobile').forEach((selectAll) => {
            selectAll.addEventListener('change', function () {
                document.querySelectorAll('#select-all, #select-all-mobile, .participant-checkbox').forEach((checkbox) => {
                    checkbox.checked = this.checked;
                });
            });
        });
    </script>
@endsection
