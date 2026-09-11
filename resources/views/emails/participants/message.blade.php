<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="color-scheme" content="light">
<title>{{ $objet }}</title>
<style>
    @media only screen and (max-width: 600px) {
        .email-container { width: 100% !important; border-radius: 0 !important; }
        .email-padding { padding-left: 20px !important; padding-right: 20px !important; }
    }
</style>
<!--[if mso]>
<noscript>
<xml>
<o:OfficeDocumentSettings>
<o:PixelsPerInch>96</o:PixelsPerInch>
</o:OfficeDocumentSettings>
</xml>
</noscript>
<style>
    table, td { font-family: Arial, Helvetica, sans-serif !important; }
</style>
<![endif]-->
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">
<div style="display:none; max-height:0; overflow:hidden; mso-hide:all;">
    {{ Illuminate\Support\Str::limit(trim($corps), 100) }}
</div>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6;">
    <tr>
        <td align="center" style="padding:24px 16px;">

            <table role="presentation" class="email-container" width="100%" cellpadding="0" cellspacing="0" style="width:100%; max-width:600px; background-color:#ffffff; border:1px solid #e5e7eb; border-radius:8px; overflow:hidden;">

                <tr>
                    <td style="padding:0; line-height:0; font-size:0;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="50%" style="background-color:#EF2B2D; height:4px; font-size:1px; line-height:4px;">&nbsp;</td>
                                <td width="50%" style="background-color:#009E49; height:4px; font-size:1px; line-height:4px;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="email-padding" style="background-color:#00602e; padding:24px 32px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="font-family:Arial,Helvetica,sans-serif; color:#FCD116; font-size:11px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">
                                    Burkina Faso
                                </td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial,Helvetica,sans-serif; color:#ffffff; font-size:17px; font-weight:bold; padding-top:4px; line-height:1.3;">
                                    Ministère des Sports, de la Jeunesse et de l'Emploi
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="email-padding" style="padding:32px; font-family:Arial,Helvetica,sans-serif; color:#1f2937; font-size:15px; line-height:1.6;">
                        <p style="margin:0 0 16px 0;">Bonjour {{ $inscription->prenom }} {{ $inscription->nom }},</p>

                        @foreach (preg_split('/\R/', trim($corps)) as $paragraphe)
                            @if (trim($paragraphe) !== '')
                                <p style="margin:0 0 12px 0;">{{ $paragraphe }}</p>
                            @endif
                        @endforeach

                        @if ($inscription->formation)
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:8px 0 24px 0; background-color:#f9fafb; border-radius:6px;">
                                <tr>
                                    <td style="padding:16px 18px; border-left:3px solid #009E49; font-family:Arial,Helvetica,sans-serif; font-size:14px; color:#374151;">
                                        <span style="display:block; font-weight:bold; color:#111827;">{{ $inscription->formation->titre }}</span>
                                        @if ($inscription->formation->lieu)
                                            <span style="display:block; color:#6b7280; margin-top:2px;">{{ $inscription->formation->lieu }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 8px 0;">
                                <tr>
                                    <td style="border-radius:6px; background-color:#009E49;" bgcolor="#009E49">
                                        <a href="{{ route('formations.show', $inscription->formation) }}" target="_blank" style="display:inline-block; padding:12px 26px; font-family:Arial,Helvetica,sans-serif; font-size:14px; font-weight:bold; color:#ffffff; text-decoration:none; border-radius:6px;">
                                            Voir la formation
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td class="email-padding" style="padding:20px 32px 32px 32px; border-top:1px solid #f3f4f6; font-family:Arial,Helvetica,sans-serif; font-size:14px; color:#374151; line-height:1.5;">
                        Cordialement,<br>
                        <strong>{{ config('mail.from.name') }}</strong>
                    </td>
                </tr>

                <tr>
                    <td class="email-padding" style="background-color:#f9fafb; border-top:1px solid #e5e7eb; padding:20px 32px; font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:1.6; color:#9ca3af; text-align:center;">
                        {{ config('app.name') }} — Ministère des Sports, de la Jeunesse et de l'Emploi, Burkina Faso<br>
                        Cet email vous a été envoyé car vous êtes inscrit(e) sur notre plateforme.<br>
                        © {{ date('Y') }} {{ config('app.name') }}
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>
</body>
</html>
