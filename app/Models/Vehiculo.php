<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';

    protected $fillable = ['placa', 'id_cliente', 'id_tipo_vehiculo', 'marca', 'modelo', 'color', 'estado'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'id_tipo_vehiculo');
    }
}
