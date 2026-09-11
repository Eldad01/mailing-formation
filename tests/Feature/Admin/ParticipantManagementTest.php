<?php

namespace Tests\Feature\Admin;

use App\Mail\MessageParticipant;
use App\Models\Formation;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ParticipantManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_visiteur_ne_peut_pas_acceder_a_la_liste_des_participants(): void
    {
        $formation = Formation::factory()->create();

        $response = $this->get(route('formations.participants.index', $formation));

        $response->assertRedirect(route('login'));
    }

    public function test_un_admin_voit_toutes_les_donnees_des_participants(): void
    {
        $this->actingAs(User::factory()->create());

        $formation = Formation::factory()->create();
        $inscription = Inscription::factory()->for($formation)->create([
            'email' => 'participant@example.com',
            'telephone' => '+226 70 11 22 33',
        ]);

        $response = $this->get(route('formations.participants.index', $formation));

        $response->assertOk();
        $response->assertSee($inscription->nom);
        $response->assertSee($inscription->prenom);
        $response->assertSee('participant@example.com');
        $response->assertSee('+226 70 11 22 33');
        $response->assertSee($inscription->direction_service);
    }

    public function test_la_page_publique_de_la_formation_ne_montre_pas_lemail_ni_le_telephone(): void
    {
        $formation = Formation::factory()->create();
        $inscription = Inscription::factory()->for($formation)->create([
            'email' => 'secret@example.com',
            'telephone' => '+226 70 99 88 77',
        ]);

        $response = $this->get(route('formations.show', $formation));

        $response->assertOk();
        $response->assertSee($inscription->nom);
        $response->assertSee($inscription->direction_service);
        $response->assertDontSee('secret@example.com');
        $response->assertDontSee('+226 70 99 88 77');
    }

    public function test_un_visiteur_ne_peut_pas_telecharger_la_liste_de_presence(): void
    {
        $formation = Formation::factory()->create();

        $response = $this->get(route('formations.participants.pdf', $formation));

        $response->assertRedirect(route('login'));
    }

    public function test_un_admin_peut_telecharger_la_liste_de_presence_en_pdf(): void
    {
        $this->actingAs(User::factory()->create());

        $formation = Formation::factory()->create();
        Inscription::factory()->for($formation)->create(['nom' => 'SIMPORE', 'prenom' => 'Eldad']);

        $response = $this->get(route('formations.participants.pdf', $formation));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_un_admin_peut_retirer_un_participant(): void
    {
        $this->actingAs(User::factory()->create());

        $formation = Formation::factory()->create();
        $inscription = Inscription::factory()->for($formation)->create();

        $response = $this->delete(route('participants.destroy', $inscription));

        $response->assertRedirect(route('formations.participants.index', $formation));
        $this->assertDatabaseMissing('inscriptions', ['id' => $inscription->id]);
    }

    public function test_la_page_contient_bien_le_formulaire_denvoi_groupe(): void
    {
        $this->actingAs(User::factory()->create());

        $formation = Formation::factory()->create();
        Inscription::factory()->for($formation)->create();

        $response = $this->get(route('formations.participants.index', $formation));

        // The bulk-email inputs live outside the <form> and reference it via
        // the HTML `form` attribute, so the <form id="bulk-email-form"> tag
        // itself must exist in the markup or the browser submits nothing.
        $response->assertSee(
            '<form id="bulk-email-form" method="POST" action="'.route('formations.participants.email', $formation).'"',
            false,
        );
    }

    public function test_un_admin_peut_envoyer_un_email_aux_participants_selectionnes(): void
    {
        Mail::fake();
        $this->actingAs(User::factory()->create());

        $formation = Formation::factory()->create();
        $destinataire = Inscription::factory()->for($formation)->create();
        $autre = Inscription::factory()->for($formation)->create();

        $response = $this->post(route('formations.participants.email', $formation), [
            'objet' => 'Votre attestation',
            'message' => 'Veuillez trouver votre attestation en pièce jointe.',
            'inscriptions' => [$destinataire->id],
        ]);

        $response->assertRedirect(route('formations.participants.index', $formation));

        Mail::assertSent(MessageParticipant::class, fn (MessageParticipant $mail) => $mail->hasTo($destinataire->email));
        Mail::assertNotSent(MessageParticipant::class, fn (MessageParticipant $mail) => $mail->hasTo($autre->email));
    }

    public function test_un_admin_peut_joindre_un_fichier_a_lemail(): void
    {
        Mail::fake();
        Storage::fake('local');
        $this->actingAs(User::factory()->create());

        $formation = Formation::factory()->create();
        $destinataire = Inscription::factory()->for($formation)->create();
        $fichier = UploadedFile::fake()->create('attestation.pdf', 500, 'application/pdf');

        $response = $this->post(route('formations.participants.email', $formation), [
            'objet' => 'Votre attestation',
            'message' => 'Veuillez trouver votre attestation en pièce jointe.',
            'inscriptions' => [$destinataire->id],
            'pieces_jointes' => [$fichier],
        ]);

        $response->assertRedirect(route('formations.participants.index', $formation));

        Mail::assertSent(MessageParticipant::class, function (MessageParticipant $mail) {
            return $mail->hasTo($mail->inscription->email)
                && count($mail->piecesJointes) === 1
                && $mail->piecesJointes[0]['name'] === 'attestation.pdf';
        });
    }

    public function test_un_fichier_joint_invalide_est_rejete(): void
    {
        Mail::fake();
        $this->actingAs(User::factory()->create());

        $formation = Formation::factory()->create();
        $destinataire = Inscription::factory()->for($formation)->create();
        $fichier = UploadedFile::fake()->create('virus.exe', 500, 'application/x-msdownload');

        $response = $this->post(route('formations.participants.email', $formation), [
            'objet' => 'Votre attestation',
            'message' => 'Message.',
            'inscriptions' => [$destinataire->id],
            'pieces_jointes' => [$fichier],
        ]);

        $response->assertSessionHasErrors('pieces_jointes.0');
        Mail::assertNothingSent();
    }
}
