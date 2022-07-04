<?php

namespace App\Http\Requests\Planillas;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionDetalleTurnos extends FormRequest
{
    /**
     * @var mixed
     */

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
            'dett_turnos' => 'required|numeric',
            'dett_extras' => 'nullable|numeric',
            'dett_ordinales' => 'nullable|numeric',
            'dett_salario' => 'required|numeric',
            'dett_descripcion'=>'required|max:75',

        ];
    }

    public function attributes()
    {
        return [
            'dett_turnos' => '<strong>Turnos</strong>',
            'dett_extras' => '<strong>Extras</strong>',
            'dett_ordinales' => '<strong>Ordinales</strong>',
            'dett_salario' => '<strong>Empleado</strong>',
            'dett_descripcion' => '<strong>Descripcion</strong>',

        ];
    }
}
