<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInscriptionRequest;
use App\Models\Formation;
use Illuminate\View\View;
use Throwable;

class InscriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInscriptionRequest $request, Formation $formation): View
    {
        if ($formation->estComplete()) {
            return view('inscriptions.echec', ['formation' => $formation]);
        }

        try {
            $formation->inscriptions()->create($request->validated());
        } catch (Throwable $e) {
            report($e);

            return view('inscriptions.echec', ['formation' => $formation]);
        }

        session()->put("inscrit.{$formation->id}", true);

        return view('inscriptions.succes', ['formation' => $formation]);
    }
}
