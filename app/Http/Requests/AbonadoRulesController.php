<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class AbonadoRulesController extends FormRequest
{
    public static function abonadoRules () {
        return [
            'foto' => 'mimes:png',
            'nif' => 'required|min:9',
            'nombre' => 'required|min:3',
            // 'apellidos' => 'required|min:5',
            // 'telefono' => '',
            'email' => 'required|email|unique:abonados',
            // 'numero_abonado' => '',
            // 'estado' => '',
            // 'id_tarifa' => '',
            // 'pagado_tarifa' => '',
            
            // 'qr' => '',

        ];
    }
    public static function updateRules () {
        return [
            'foto' => 'mimes:png',
            'nif' => 'min:9',
            'nombre' => 'min:3',
            // 'apellidos' => 'required|min:5',
            // 'telefono' => '',
            'email' => 'email|unique:abonados',
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
