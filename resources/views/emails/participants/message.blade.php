@component('mail::message')
Bonjour {{ $inscription->prenom }} {{ $inscription->nom }},

{{ $corps }}

---

Formation concernée : **{{ $inscription->formation->titre }}**

Cordialement,<br>
{{ config('app.name') }}
@endcomponent
