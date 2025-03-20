<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\HasDatabaseNotifications;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ Correcto


class Usuarios extends Authenticatable
{
    use HasApiTokens, HasFactory, HasDatabaseNotifications;
    protected $table = 'TBL_USUARIOS';
    protected $primaryKey = 'PK_usuario';
    protected $fillable = [
        'nombre_usuario',
        'nombre_real',
        'password',
        'permisos'
    ];

    public $timestamps = false; // Desactiva los timestamps

}
