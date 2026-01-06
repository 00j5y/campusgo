<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehiculeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Autoriser tout utilisateur connecté (géré par le middleware)
    }

    public function rules(): array
    {
        return [
            'Marque' => 'required|string|max:20',
            'Modele' => 'required|string|max:20',
            'Couleur' => 'required|string|max:20',
            'immatriculation' => ['required', 'string', 'regex:/^[A-Za-z]{2}[-\s]?[0-9]{3}[-\s]?[A-Za-z]{2}$/'],
            'NombrePlace' => 'required|integer|min:1|max:9',
        ];
    }

}