<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('roles.manager');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $unique = $this->id != -1 ? ',' . $this->id . ',id' : '';
        return [
            'nombre' => ['required', 'max:60', 'unique:roles,nombre' . $unique],
        ];
    }
}
