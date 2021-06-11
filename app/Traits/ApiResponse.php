<?php

namespace App\Traits;

trait ApiResponse{

    public function successResponse($data, $msj = ' ', $code = 200){
        return response()->json(array("error" => false, "data" => $data, "code" => $code, "msj" => $msj), $code);
    }

    public function errorValidateResponse($data, $code = 400, $msj = '')
    {
        return response()->json(array("error" => true, "data" => $data, "code" => $code, "msj" => $msj), $code);
    }

    public function errorResponse($data, $code = 400, $msj = '')
    {
        return response()->json(array("error" => true, "data" => $data, "code" => $code, "msj" => $msj), $code);
    }

}
