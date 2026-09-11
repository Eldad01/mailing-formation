<?php

namespace App\Models;

use Database\Factories\FormationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['titre', 'description', 'lieu', 'debut_a', 'places'])]
class Formation extends Model
{
    /** @use HasFactory<FormationFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'debut_a' => 'datetime',
            'places' => 'integer',
            'inscriptions_ouvertes' => 'boolean',
        ];
    }

    /**
     * @return HasMany<Inscription, $this>
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    public function placesRestantes(): int
    {
        return max(0, $this->places - $this->inscriptions()->count());
    }

    public function estComplete(): bool
    {
        return $this->placesRestantes() === 0;
    }

    public function accepteInscriptions(): bool
    {
        return $this->inscriptions_ouvertes && ! $this->estComplete();
    }
}
