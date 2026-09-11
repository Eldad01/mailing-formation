<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_visiteur_ne_peut_pas_acceder_a_la_creation_de_formation(): void
    {
        $response = $this->get(route('formations.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_un_visiteur_ne_peut_pas_creer_de_formation(): void
    {
        $response = $this->post(route('formations.store'), [
            'titre' => 'Formation test',
            'debut_a' => now()->addWeek(),
            'places' => 10,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('formations', 0);
    }

    public function test_un_utilisateur_peut_se_connecter_avec_les_bons_identifiants(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('formations.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_la_connexion_echoue_avec_un_mauvais_mot_de_passe(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'mauvais-mot-de-passe',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_un_utilisateur_connecte_peut_creer_une_formation(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('formations.store'), [
            'titre' => 'Formation test',
            'debut_a' => now()->addWeek(),
            'places' => 10,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('formations', 1);
    }

    public function test_un_utilisateur_connecte_peut_se_deconnecter(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('formations.index'));
        $this->assertGuest();
    }
}
