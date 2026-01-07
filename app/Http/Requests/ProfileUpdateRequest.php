<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname'  => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique(User::class)->ignore($this->user()->id),
                'ends_with:@u-picardie.fr,@etud.u-picardie.fr' 
            ],
            'num_tel' => ['nullable', 'string', 'regex:/^0[1-9]([ .-]?[0-9]{2}){4}$/'],
            'photo'   => ['nullable', 'image', 'max:2048'],
            
            'Accepte_animaux'    => ['boolean'],
            'Accepte_fumeurs'    => ['boolean'],
            'Accepte_musique'    => ['boolean'],
            'accepte_discussion' => ['required', 'integer', 'min:1', 'max:5'],
            'delete_photo'       => ['nullable', 'boolean'],
        ];
    }

}