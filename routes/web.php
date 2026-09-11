<?php

use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\InscriptionController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/formations');

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login.store');
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('formations', [FormationController::class, 'index'])->name('formations.index');

Route::middleware('auth')->group(function (): void {
    Route::get('formations/create', [FormationController::class, 'create'])->name('formations.create');
    Route::post('formations', [FormationController::class, 'store'])->name('formations.store');
    Route::get('formations/{formation}/edit', [FormationController::class, 'edit'])->name('formations.edit');
    Route::put('formations/{formation}', [FormationController::class, 'update'])->name('formations.update');
    Route::delete('formations/{formation}', [FormationController::class, 'destroy'])->name('formations.destroy');
    Route::post('formations/{formation}/toggle-inscriptions', [FormationController::class, 'toggleInscriptions'])->name('formations.toggle-inscriptions');

    Route::get('formations/{formation}/participants', [ParticipantController::class, 'index'])->name('formations.participants.index');
    Route::get('formations/{formation}/participants/pdf', [ParticipantController::class, 'pdf'])->name('formations.participants.pdf');
    Route::post('formations/{formation}/participants/email', [ParticipantController::class, 'email'])->name('formations.participants.email');
    Route::delete('participants/{inscription}', [ParticipantController::class, 'destroy'])->name('participants.destroy');
});

Route::get('formations/{formation}', [FormationController::class, 'show'])->name('formations.show');
Route::post('formations/{formation}/inscriptions', [InscriptionController::class, 'store'])
    ->name('formations.inscriptions.store');
