<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = ['nombre', 'telefono', 'correo', 'es_suscriptor', 'fecha_vencimiento', 'estado'];

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'id_cliente');
    }
}
