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
        return [
            'foto' => 'mimes:png,jpg',
            'nif' => 'required|min:9|unique:abonados',
            'nombre' => 'required|min:3',
            'apellidos' => 'required|min:3',
            'telefono' => 'required',
            'email' => 'required|email|unique:abonados',
            // 'numero_abonado' => '',
            // 'estado' => '',
            'id_tarifa' => 'required|exists:tarifas,id',
            // 'pagado_tarifa' => '',
            // 'qr' => '',

        ];
    }

    public static function updateRules ($id) {
        return [
            // 'id' => 'exists:App\Models\Abonado,id',
            'id' => 'required|exists:abonados,id',
            'nif' => 'required|min:9|unique:abonados,nif,'.$id,
            'foto' => 'mimes:png,jpg',
            'nombre' => 'min:3',
            // 'apellidos' => 'required|min:5',
            // 'telefono' => '',
            'email' => 'required|email|unique:abonados,email,'.$id,
            'id_tarifa' => 'required|exists:tarifas,id',

            // 'numero_abonado' => '',
            // 'estado' => '',
            // 'id_tarifa' => '',
            // 'pagado_tarifa' => '',
            
            // 'qr' => '',
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
