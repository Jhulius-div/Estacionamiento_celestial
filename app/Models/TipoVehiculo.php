<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    protected $table = 'tipos_vehiculo';

    public $timestamps = false;

    protected $fillable = ['nombre', 'tarifa_hora'];
}
