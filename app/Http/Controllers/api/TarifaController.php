<?php

namespace App\Http\Controllers\api;

use App\Models\Tarifa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AbonadoRulesController;

class TarifaController extends ApiResponseController
{
    //
    public function index()
    {
        $tarifas = Tarifa::all();
        return $this->successResponse($tarifas);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), AbonadoRulesController::tarifaRules());

        if ($validator->fails()) {
            return $this->errorValidateResponse($validator->errors());

        }else{

            $tarifa = Tarifa::create($request->all());
            return $this->successResponse($tarifa,"Tarifa creada correctamente");
        }
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $validator = Validator::make($request->all(), AbonadoRulesController::tarifaUpdateRules($id));

        if ($validator->fails()) {
            return $this->errorValidateResponse($validator->errors());

        }else{
            $tarifa = Tarifa::where('id',$id)->first();

            $tarifa->update($request->all());
            return $this->successResponse($tarifa,"Tarifa actualizado correctamente");
        }
    }

    public function destroy($id)
    {
        $tarifa = Tarifa::where('id',$id)->first();
        $tarifa->delete();
        return $this->successResponse($tarifa,"Eliminado correctamente");
    }


}
