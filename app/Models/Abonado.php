<?php

namespace App\Models;

use App\Models\Tarifa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Abonado extends Model
{
    // const URL = url('/abonados');
    use HasFactory;
    protected $table = 'abonados';
    protected $fillable = [
        'id',
        'nif',
        'nombre',
        'apellidos',
        'telefono',
        'email',
        'numeo_abonado',
        'estado',
        'id_tarifa',
        'pagado_tarifa',
        'foto',
        'qr',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // "created_at",
    ];


    public function tarifa(){
        return $this->belongsTo(Tarifa::class,"id_tarifa","id");
    }

}
