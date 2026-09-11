<?php

namespace Tests\Feature;

use App\Models\Formation;
use App\Models\Inscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormationInscriptionTest extends TestCase
{
    use RefreshDatabase;

    private function donneesInscription(array $overrides = []): array
    {
        return array_merge([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'telephone' => '+226 70 00 00 00',
            'email' => 'jean.dupont@example.com',
            'direction_service' => 'Direction des Sports',
        ], $overrides);
    }

    public function test_liste_des_formations_saffiche(): void
    {
        Formation::factory()->count(3)->create();

        $response = $this->get(route('formations.index'));

        $response->assertOk();
    }

    public function test_un_visiteur_peut_sinscrire_a_une_formation(): void
    {
        $formation = Formation::factory()->create(['places' => 5]);

        $response = $this->post(
            route('formations.inscriptions.store', $formation),
            $this->donneesInscription(),
        );

        $response->assertOk();
        $response->assertSee('Inscription réussie');

        $this->assertDatabaseHas('inscriptions', [
            'formation_id' => $formation->id,
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@example.com',
            'direction_service' => 'Direction des Sports',
        ]);
    }

    public function test_les_champs_obligatoires_sont_valides(): void
    {
        $formation = Formation::factory()->create();

        $response = $this->post(route('formations.inscriptions.store', $formation), []);

        $response->assertSessionHasErrors(['nom', 'prenom', 'telephone', 'email', 'direction_service']);
        $this->assertDatabaseCount('inscriptions', 0);
    }

    public function test_lemail_doit_etre_valide(): void
    {
        $formation = Formation::factory()->create();

        $response = $this->post(
            route('formations.inscriptions.store', $formation),
            $this->donneesInscription(['email' => 'pas-un-email']),
        );

        $response->assertSessionHasErrors('email');
    }

    public function test_on_ne_peut_pas_sinscrire_deux_fois_avec_le_meme_email(): void
    {
        $formation = Formation::factory()->create();
        Inscription::factory()->for($formation)->create(['email' => 'jean.dupont@example.com']);

        $response = $this->post(
            route('formations.inscriptions.store', $formation),
            $this->donneesInscription(),
        );

        $response->assertSessionHasErrors('email');
        $this->assertSame(1, $formation->inscriptions()->count());
    }

    public function test_on_ne_peut_pas_sinscrire_a_une_formation_complete(): void
    {
        $formation = Formation::factory()->create(['places' => 1]);
        Inscription::factory()->for($formation)->create();

        $response = $this->post(
            route('formations.inscriptions.store', $formation),
            $this->donneesInscription(),
        );

        $response->assertOk();
        $response->assertSee("Échec de l'inscription");
        $this->assertSame(1, $formation->inscriptions()->count());
    }

    public function test_le_formulaire_disparait_apres_inscription_reussie(): void
    {
        $formation = Formation::factory()->create(['places' => 5]);

        $avant = $this->get(route('formations.show', $formation));
        $avant->assertSee('id="nom"', false);

        $this->post(route('formations.inscriptions.store', $formation), $this->donneesInscription());

        $apres = $this->get(route('formations.show', $formation));
        $apres->assertSee('Vous êtes inscrit');
        $apres->assertDontSee('id="nom"', false);
    }
}
