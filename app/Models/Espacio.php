<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    protected $table = 'espacios_estacionamiento';

    protected $fillable = ['piso', 'sector', 'numero', 'esta_ocupado', 'estado'];

    // Código del espacio, por ejemplo P1-A-3
    public function getCodigoAttribute()
    {
        return 'P' . $this->piso . '-' . $this->sector . '-' . $this->numero;
    }
}
