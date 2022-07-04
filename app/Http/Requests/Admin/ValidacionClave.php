<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionClave extends FormRequest
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
            'cla_UsuarioFirma' => 'required|max:35|',
            'cla_LlaveFirma' => 'required|max:35',
            'cla_UsuarioApi' => 'required|max:35',
            'cla_LlaveApi' => 'required|max:35',

        ];
    }

    public function attributes()
    {
        return [
            'cla_UsuarioFirma' => '<strong>Usurio Firma</strong>',
            'cla_LlaveFirma' => '<strong>Llave Firma</strong>',
            'cla_UsuarioApi' => '<strong>Usurio Api</strong>',
            'cla_LlaveApi' => '<strong>Llave APi</strong>',
                  ];
    }

}
