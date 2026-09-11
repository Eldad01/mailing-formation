<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nom' => is_string($this->nom) ? trim(strip_tags($this->nom)) : $this->nom,
            'prenom' => is_string($this->prenom) ? trim(strip_tags($this->prenom)) : $this->prenom,
            'telephone' => is_string($this->telephone) ? trim(strip_tags($this->telephone)) : $this->telephone,
            'email' => is_string($this->email) ? trim(strtolower($this->email)) : $this->email,
            'direction_service' => is_string($this->direction_service) ? trim(strip_tags($this->direction_service)) : $this->direction_service,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30', 'regex:/^[0-9+\s\-]{6,30}$/'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('inscriptions')->where('formation_id', $this->route('formation')->id),
            ],
            'direction_service' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'telephone.regex' => 'Le numéro de téléphone n\'est pas valide.',
            'email.unique' => 'Cette adresse email est déjà inscrite à cette formation.',
        ];
    }
}
