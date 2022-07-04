<?php

namespace App\Http\Requests\Planillas;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionSalario extends FormRequest
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
            'sal_empresa'=>'required|numeric',
            'sal_terminal'=>'required|numeric',
            'sal_empleado' => 'required|numeric',
            'sal_puesto' => 'required|numeric',
            'sal_salario'=>'required|numeric',
            'sal_igss'=>'nullable',
            'sal_tipo'=>'required',
            'sal_inicio'=>'required',
            'sal_fin'=>'nullable',

        ];
    }


    public function attributes()
    {
        return [
            'sal_empresa' => '<strong>Empresa</strong>',
            'sal_terminal' => '<strong>Terminal</strong>',
            'sal_empleado' => '<strong>Empleado</strong>',
            'sal_puesto'=>'<strong>Puesto</strong>',
            'sal_salario' => '<strong>Salario</strong>',
            'sal_igss' => '<strong>IGSS</strong>',
            'sal_tipo' => '<strong>Tipo</strong>',
            'sal_inicio' => '<strong>Inicio</strong>',
            'sal_fin' => '<strong>Fin</strong>',
        ];
    }
}
