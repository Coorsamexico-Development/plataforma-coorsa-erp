<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UbicacionRequest extends FormRequest
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
        if ($this->isMethod('´put')) {
            $unique = ',' . $this->id . ',id';
        }
        return [
            'name' => ['required', 'unique:ubicaciones,name' . $unique]
        ];
    }
}
