<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFormationRequest;
use App\Models\Formation;
use App\Models\Inscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $formations = Formation::withCount('inscriptions')
            ->orderBy('debut_a')
            ->paginate(9);

        $stats = [
            'formations' => Formation::count(),
            'a_venir' => Formation::where('debut_a', '>=', now())->count(),
            'inscrits' => Inscription::count(),
        ];

        return view('formations.index', [
            'formations' => $formations,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('formations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormationRequest $request): RedirectResponse
    {
        $formation = Formation::create($request->validated());

        return redirect()
            ->route('formations.show', $formation)
            ->with('status', 'Formation créée.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Formation $formation): View
    {
        $formation->load('inscriptions');

        return view('formations.show', [
            'formation' => $formation,
            'dejaInscrit' => session()->has("inscrit.{$formation->id}"),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formation $formation): View
    {
        return view('formations.edit', ['formation' => $formation]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreFormationRequest $request, Formation $formation): RedirectResponse
    {
        $formation->update($request->validated());

        return redirect()
            ->route('formations.show', $formation)
            ->with('status', 'Formation mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Formation $formation): RedirectResponse
    {
        $formation->delete();

        return redirect()
            ->route('formations.index')
            ->with('status', 'Formation supprimée.');
    }
}
