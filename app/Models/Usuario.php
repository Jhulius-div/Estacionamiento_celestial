<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    protected $fillable = ['nombre', 'correo', 'password', 'id_rol', 'estado'];

    protected $hidden = ['password'];

    // La contraseña se guarda encriptada automáticamente
    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    // Revisa si el usuario tiene alguno de los roles indicados
    public function tieneRol(...$roles): bool
    {
        return in_array($this->rol->nombre, $roles);
    }

    // La tabla no usa "recordarme"
    public function getRememberTokenName()
    {
        return '';
    }
}
