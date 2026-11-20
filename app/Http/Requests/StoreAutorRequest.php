<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAutorRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtém as regras de validação que se aplicam à requisição.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'AUTNOME' => 'required|string|max:150|unique:AUTORES,AUTNOME',
            'AUTPSEUDONIMO' => 'nullable|string|max:150',
            'AUTBIOGRAFIA' => 'nullable|string',
            'AUTPAISNASC' => 'nullable|string|max:100',
        ];
    }

    /**
     * Obtém as mensagens personalizadas para os erros do validador.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'AUTNOME.required' => 'O campo nome do autor é obrigatório.',
            'AUTNOME.max' => 'O campo nome não pode ter mais de 150 caracteres.',
            'AUTNOME.unique' => 'Já existe um autor cadastrado com este nome.',
            'AUTPSEUDONIMO.max' => 'O pseudônimo não pode ter mais de 150 caracteres.',
            'AUTPAISNASC.max' => 'O país de nascimento não pode ter mais de 100 caracteres.',
        ];
    }

    /**
     * Obtém os atributos personalizados para os erros do validador.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'AUTNOME' => 'nome do autor',
            'AUTPSEUDONIMO' => 'pseudônimo',
            'AUTBIOGRAFIA' => 'biografia',
            'AUTPAISNASC' => 'país de nascimento',
        ];
    }
}
