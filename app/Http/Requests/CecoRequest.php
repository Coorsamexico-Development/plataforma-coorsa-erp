<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CecoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $unique = '';

        if ($this->isMethod('put')) {
            $unique = ',' . $this->id . ',id';
        }
        return [
            'nombre' => ['required', 'unique:cecos,nombre' . $unique],
            'ubicacione_id' => ['required', 'exists:ubicaciones,id'],
            'cliente_id' => ['required', 'exists:clientes,id']
        ];
    }
}
