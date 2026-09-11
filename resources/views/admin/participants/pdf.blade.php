<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Liste de présence — {{ $formation->titre }}</title>
<style>
    @page {
        size: landscape;
        margin: 1.5cm;
    }

    body {
        font-family: "DejaVu Sans", sans-serif;
        font-size: 11px;
        color: #111827;
    }

    .letterhead-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 18px;
    }

    .letterhead-table td {
        vertical-align: top;
        font-size: 10px;
        line-height: 1.5;
    }

    .letterhead-table .right {
        text-align: right;
    }

    .dotted {
        border-bottom: 1px dotted #000000;
        width: 90px;
        margin: 3px 0;
    }

    .dotted.right {
        margin-left: auto;
    }

    .bold {
        font-weight: bold;
        text-transform: uppercase;
    }

    .title {
        text-align: center;
        font-size: 13px;
        font-weight: bold;
        text-decoration: underline;
        margin: 10px 0 8px 0;
    }

    .meta {
        font-size: 10px;
        margin-bottom: 14px;
    }

    .meta strong {
        font-weight: bold;
    }

    table.liste {
        width: 100%;
        border-collapse: collapse;
    }

    table.liste th,
    table.liste td {
        border: 1px solid #000000;
        padding: 5px 6px;
        text-align: left;
        font-size: 10px;
    }

    table.liste th {
        font-weight: bold;
        background-color: #f3f4f6;
    }
</style>
</head>
<body>

    <table class="letterhead-table">
        <tr>
            <td width="50%">
                <div class="bold">Ministère des Sports</div>
                <div class="bold">de la Jeunesse et de l'Emploi</div>
                <div class="dotted"></div>
                <div class="bold">Secrétariat Général</div>
                <div class="dotted"></div>
                <div class="bold">Direction des Systèmes</div>
                <div class="bold">d'Information</div>
            </td>
            <td width="50%" class="right">
                <div class="bold">Burkina Faso</div>
                <div class="dotted right"></div>
                <div><em>La Patrie ou la Mort, nous Vaincrons</em></div>
            </td>
        </tr>
    </table>

    <p class="title">Liste de présence de la formation {{ $formation->titre }}</p>

    <p class="meta">
        <strong>Date :</strong> {{ now()->translatedFormat('d M Y') }}
        @if ($formation->lieu)
            &nbsp;&nbsp;&nbsp; <strong>Lieu :</strong> {{ $formation->lieu }}
        @endif
    </p>

    <table class="liste">
        <tbody>
            <tr>
                <th style="width:5%;">N°</th>
                <th style="width:22%;">Nom et prénom(s)</th>
                <th style="width:20%;">Structure</th>
                <th style="width:13%;">Téléphone</th>
                <th style="width:22%;">Email</th>
                <th style="width:18%;">Signature</th>
            </tr>
            @foreach ($inscriptions as $inscription)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $inscription->nom }} {{ $inscription->prenom }}</td>
                    <td>{{ $inscription->direction_service }}</td>
                    <td>{{ $inscription->telephone }}</td>
                    <td>{{ $inscription->email }}</td>
                    <td>&nbsp;</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
