<?php

namespace App\Http\Requests\Planillas;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionPuesto extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'pues_desc_ct' => 'required',
            'pues_desc_lg' => 'required',

        ];
    }

    public function attributes()
    {
        return [
            'pues_desc_ct' => '<strong>Descripcion Corta</strong>',
            'pues_desc_lg' => '<strong>Descripcion Larga</strong>',

        ];
    }
}
