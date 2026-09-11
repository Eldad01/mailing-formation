<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendParticipantsEmailRequest;
use App\Mail\MessageParticipant;
use App\Models\Formation;
use App\Models\Inscription;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ParticipantController extends Controller
{
    /**
     * Display the full participant list for a formation.
     */
    public function index(Formation $formation): View
    {
        $inscriptions = $formation->inscriptions()->orderBy('nom')->get();

        return view('admin.participants.index', [
            'formation' => $formation,
            'inscriptions' => $inscriptions,
        ]);
    }

    /**
     * Download the attendance list as a PDF.
     */
    public function pdf(Formation $formation): Response
    {
        $inscriptions = $formation->inscriptions()->orderBy('nom')->get();

        $pdf = Pdf::loadView('admin.participants.pdf', [
            'formation' => $formation,
            'inscriptions' => $inscriptions,
        ])->setPaper('a4', 'landscape');

        $nomFichier = 'liste-presence-'.Str::slug($formation->titre).'-'.now()->format('Y-m-d').'.pdf';

        return $pdf->download($nomFichier);
    }

    /**
     * Remove a participant from the attendance list.
     */
    public function destroy(Inscription $inscription): RedirectResponse
    {
        $formation = $inscription->formation;
        $inscription->delete();

        return redirect()
            ->route('formations.participants.index', $formation)
            ->with('status', 'Participant retiré de la liste.');
    }

    /**
     * Send an email to the selected participants.
     */
    public function email(SendParticipantsEmailRequest $request, Formation $formation): RedirectResponse
    {
        $inscriptions = $formation->inscriptions()
            ->whereIn('id', $request->validated('inscriptions'))
            ->get();

        $piecesJointes = array_map(
            fn ($fichier) => [
                'path' => $fichier->store('attachments/'.$formation->id, 'local'),
                'name' => $fichier->getClientOriginalName(),
            ],
            $request->file('pieces_jointes', []),
        );

        foreach ($inscriptions as $inscription) {
            Mail::to($inscription->email)->send(new MessageParticipant(
                $inscription,
                $request->validated('objet'),
                $request->validated('message'),
                $piecesJointes,
            ));
        }

        foreach ($piecesJointes as $piece) {
            Storage::disk('local')->delete($piece['path']);
        }

        return redirect()
            ->route('formations.participants.index', $formation)
            ->with('status', "Email envoyé à {$inscriptions->count()} participant(s).");
    }
}
