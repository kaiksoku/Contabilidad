<?php

namespace App\Http\Requests\Planillas;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionLibroSalarios extends FormRequest
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

    public function rules()
    {
        return [
            'empresa' => 'required',
            'terminal' => 'required',

            'salario' => 'required',


        ];
    }

    public function attributes()
    {
        return [
            'empresa' => '<strong>Empresa</strong>',
            'terminal' => '<strong>Terminal</strong>',
            'empleado' => '<strong>Empleado</strong>',

        ];
    }
}
