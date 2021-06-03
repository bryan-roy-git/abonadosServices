<?php

namespace App\Http\Controllers\api;

use App\Models\Tarifa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TarifaController extends Controller
{
    //
    public function index()
    {
        //
        $tarifas = Tarifa::all();
        return $tarifas;
    }
}
