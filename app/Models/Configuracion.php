<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';

    protected $fillable = ['nombre_estacionamiento', 'logo'];

    // Dirección del logo: el que se subió o el de la plantilla
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('uploads/' . $this->logo);
        }

        return asset('celestial/images/logo.svg');
    }
}
