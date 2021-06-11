<?php

namespace App\Http\Controllers\api;

use App\Models\Abonado;
use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

// use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
// use Illuminate\Filesystem\FilesystemManager;
use App\Http\Requests\AbonadoRulesController;

use Illuminate\Support\Facades\File;

class AbonadoController extends ApiResponseController
{
    const CONSTANTS = [
        'qrExtension' => '.png'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $abonados = Abonado::select("*", 
                        DB::raw("CONCAT('".url('/qrcodes')."/',abonados.qr) AS qr"),
                        DB::raw("CONCAT('".url('/abonados')."/',abonados.foto) AS foto"))
                    ->with('tarifa')
                    ->get();
        return $this->successResponse($abonados);

        // return $abonados;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            $new_abonado = $request->all();
            $hash= md5($request->nif."-".time());
            
            $filename = $hash.".".$request->foto->extension(); // ----> NAME FILE FOTO
            $request->foto->move(public_path('abonados'),$filename);
            $new_abonado["foto"] = $filename;

            $const = self::CONSTANTS;
            $qrFile = $hash.$const['qrExtension']; // ----> NAME FILE QrCode
            QrCode::format('png')->size(200)->generate($hash,'../public/qrcodes/'.$qrFile);
            $new_abonado["qr"] = $qrFile;

            $abonado = Abonado::create($new_abonado);
            $mapAbonado = $this->mapAbonado($abonado);
            return $this->successResponse($mapAbonado,"Abonado dado de alta correctamente");

        }
    }

    private function mapAbonado($abonado){
        //TODO si es null abonado
        $abonado->foto = url('/abonados')."/".$abonado->foto;
        $abonado->qr = url('/qrcodes')."/".$abonado->qr;
        $abonado->tarifa;

        return $abonado;
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
        // dd($abonado);
        if ($abonado){
            $mapAbonado = $this->mapAbonado($abonado);
            return $this->successResponse($mapAbonado);
        }else{
            return $this->errorResponse("Abonado no existe");
        }
        
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
        $id = $request->id;
        $validator = Validator::make($request->all(), AbonadoRulesController::updateRules($id));
        if ($validator->fails()) {
            return $this->errorValidateResponse($validator->errors());
        }else{
            $abonado = Abonado::where('id',$id)->first();

            if ($request->foto) {
                $hash= md5($request->nif."-".time()).".".$request->foto->extension();
                File::delete(public_path('abonados/'.$abonado->foto));
                $request->foto->move(public_path('abonados'),$hash);
                
                $new_values = $request->all();
                $new_values["foto"] = $hash;
                // dd($new_values);
                $abonado->update($new_values);

            }else{
                $abonado->update($request->all());
            }
                $mapAbonado = $this->mapAbonado($abonado);
            return $this->successResponse($mapAbonado,"Abonado actualizado correctamente");

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
        File::delete(public_path('abonados/'.$abonado->foto));
        File::delete(public_path('qrcodes/'.$abonado->qr));
        $abonado->delete();
        return $this->successResponse("Eliminado correctamente");
    }

    public function search($term)
    {
        $abonados = Abonado::where('nombre', 'like', '%'.$term.'%')
                    ->orWhere('apellidos', 'like','%'.$term.'%')
                    ->get();
        return $this->successResponse($abonados);
    }

}
