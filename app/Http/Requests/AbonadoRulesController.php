<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class AbonadoRulesController extends FormRequest
{

    public function messages()
    {
        return [
            'email.required' => 'el email es requerido',
        ];
    }

    public static function abonadoRules () {
        //TODO controlar boolean que no sea texto
        return [
            'foto' => 'required',
            'nif' => 'required|min:9|unique:abonados',
            'nombre' => 'required|min:3',
            'apellidos' => 'required|min:3',
            'telefono' => 'required|min:9',
            'email' => 'required|email|unique:abonados',
            'estado' => 'required',
            'id_tarifa' => 'required',
            // 'pagado_tarifa' => '',
            // 'qr' => '',

        ];
    }

    public static function updateRules ($id) {
        return [
            // 'id' => 'exists:App\Models\Abonado,id',
            'id' => 'required|exists:abonados,id',
            'nif' => 'required|min:9|unique:abonados,nif,'.$id,
            'foto' => 'required',
            'nombre' => 'required|min:3',
            'apellidos' => 'required|min:5',
            'telefono' => 'required|min:9',
            'email' => 'required|email|unique:abonados,email,'.$id,
            'id_tarifa' => 'required|exists:tarifas,id',

            // 'numero_abonado' => '',
            // 'estado' => '',
            // 'id_tarifa' => '',
            // 'pagado_tarifa' => '',
            
            // 'qr' => '',
        ];
    }

    public static function tarifaRules () {
        //TODO controlar boolean que no sea texto
        return [
            'nombre' => 'required|min:3|unique:tarifas',
            'precio' => 'required|numeric',
        ];
    }

    public static function tarifaUpdateRules ( $id ) {
        //TODO controlar boolean que no sea texto
        return [
            'id' => 'required|exists:tarifas,id',
            'nombre' => 'required|min:3|unique:tarifas,nombre,'.$id,
            'precio' => 'numeric',
        ];
    }

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
        return $this->abonadoRules();
    }
}
