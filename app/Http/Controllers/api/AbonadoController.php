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
use Illuminate\Support\Facades\Storage;

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
                    // ->get();
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
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
        // dd($request->foto);
        // var_dump($request->all());
        $validator = Validator::make($request->all(), AbonadoRulesController::abonadoRules());

        if ($validator->fails()) {
            return $this->errorValidateResponse($validator->errors());

        }else{
            $new_abonado = $request->all();
            $hash= md5($request->nif."-".time());

            // ------------------> BASE64 FORMAT <-------------------------
            // $filename = $hash."."."png"; // ----> NAME FILE FOTO
            // $filename = $hash.".".$request->foto->extension(); // ----> NAME FILE FOTO
            // $request->foto->move(public_path('abonados'),$filename);
            // $new_abonado["foto"] = $filename;
            $base64_str = substr($request->foto, strpos($request->foto, ",")+1); 
            $extension = explode('/', mime_content_type($request->foto))[1]; //EXTENSION DEL ARCHIVO

            $image = base64_decode($base64_str);
            $filename = $hash.".".$extension;
            file_put_contents(public_path()."\abonados\\".$filename,$image);
            $new_abonado["foto"] = $filename;


            $const = self::CONSTANTS;
            $qrFile = $hash.$const['qrExtension']; // ----> NAME FILE QrCode
            QrCode::format('png')->size(200)->generate($hash,'../public/qrcodes/'.$qrFile);
            $new_abonado["qr"] = $qrFile;
            
            $abonado = Abonado::create($new_abonado);
            $abonado->hash= $hash;
            $abonado->numero_abonado = $abonado->id;
            $abonado->save();
            $mapAbonado = $this->mapAbonado($abonado);
            return $this->successResponse($mapAbonado,"Abonado dado de alta correctamente");

        }
    }

    private function mapAbonado($abonado){
        if($abonado == null){
            return null;
        }
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
        // dd($request->all());
        $id = $request->id;
        $validator = Validator::make($request->all(), AbonadoRulesController::updateRules($id));
        if ($validator->fails()) {
            return $this->errorValidateResponse($validator->errors());
        }else{
            $abonado = Abonado::where('id',$id)->first();
            // dd($request->foto);
            if ($request->foto) {

                // $hash= md5($request->nif."-".time()).".".$request->foto->extension();
                // File::delete(public_path('abonados/'.$abonado->foto));
                // $request->foto->move(public_path('abonados'),$hash);
                File::delete(public_path('abonados/'.$abonado->foto));

                $hash= md5($request->nif."-".time());
                $base64_str = substr($request->foto, strpos($request->foto, ",")+1);
                $extension = explode('/', mime_content_type($request->foto))[1];

                $image = base64_decode($base64_str);
                $filename = $hash.".".$extension;

                file_put_contents(public_path()."\abonados\\".$filename,$image);

                $new_values = $request->all();
                $new_values["foto"] = $filename;
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
        return $this->successResponse($abonado,"Eliminado correctamente");
    }

    public function search(Request $request)
    {
        $order_by =  json_decode($request->order_by, true);
        // dump($order_by);
        // dd($request->all());
        $params =  [   
            'id',
            'nif',
            'nombre',
            'apellidos',
            'telefono',
            'email',
            'numero_abonado',
            'estado',
            'id_tarifa',
            'pagado_tarifa',
            'foto',
            'qr',
            'hash'   
        ];

        $abonados = Abonado::select("*", 
            DB::raw("CONCAT('".url('/qrcodes')."/',abonados.qr) AS qr"),
            DB::raw("CONCAT('".url('/abonados')."/',abonados.foto) AS foto"));
        foreach ($request->all() as $key => $value) {            
            if (in_array($key, $params)) {
                $abonados = $abonados->where($key, 'like', '%'.$value.'%');
            }
        }
        if ($order_by) {
            foreach ($order_by as $key => $value) {  
                if (in_array($value["name"], $params)) {
                    $abonados = $abonados->orderBy($value["name"] , $value["order"] ? 'asc': 'desc');
                }
            }
        }else{
            $abonados = $abonados->orderBy( 'id' , 'desc');
        }
     
        $abonados = $abonados->with('tarifa')->get();
        return $this->successResponse($abonados);
    }
}
