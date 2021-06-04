<?php

namespace App\Http\Controllers\api;

use App\Models\Abonado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AbonadoRulesController;
use Illuminate\Filesystem\FilesystemManager;

// use Illuminate\Support\Facades\File;

class AbonadoController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $abonados = Abonado::all();
        return $abonados;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        // $tarifas = Tarifa::all();
        return view('form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), AbonadoRulesController::abonadoRules());

        if ($validator->fails()) {
            return $this->errorValidateResponse($validator->errors());

        }else{
            // return "validado";
            $new_abonado = $request->all();
            if($request->foto){
                $filename = $request->nif."-".time().".".$request->foto->extension();
                $request->foto->move(public_path('abonados'),$filename);
                $new_abonado["foto"] = $filename;
            }
            $abonado = Abonado::create($new_abonado);
            return $this->successResponse("Abonado dado de alta correctamente");

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $abonado = Abonado::where('id',$id)->first();
        $abonado->foto = url('/abonados')."/".$abonado->foto;
        $abonado->tarifa;
        // dd($abonado);
        // return response()->json($abonado, 200);
        return $this->successResponse($abonado);

        // return view('abonado')->with('abonado',$abonado);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $validator = Validator::make($request->all(), AbonadoRulesController::updateRules());
        if ($validator->fails()) {
            return $this->errorValidateResponse($validator->errors());
        }else{
            $abonado = Abonado::where('id',$request->id)->first();
            
            $abonado->update($request->all());
            return $this->successResponse("Abonado actualizado correctamente");

        }
  
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $abonado = Abonado::where('id',$id)->first();
        $abonado->delete();
        return "Abonado eliminado satifasctoriamente";
    }

    public function search($term)
    {
        //
        $abonados = Abonado::where('nombre', 'like', '%'.$term.'%')
                    ->orWhere('apellidos', 'like','%'.$term.'%')
                    ->get();
        dd($abonados);
        return $abonados;
    }

}
