Bonjour {{ $inscription->prenom }} {{ $inscription->nom }},

{{ trim($corps) }}
@if ($inscription->formation)

{{ $inscription->formation->titre }}
@if ($inscription->formation->lieu)
{{ $inscription->formation->lieu }}
@endif
Voir la formation : {{ route('formations.show', $inscription->formation) }}
@endif

Cordialement,
{{ config('mail.from.name') }}

---
{{ config('app.name') }} — Ministère des Sports, de la Jeunesse et de l'Emploi, Burkina Faso
