<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    protected $table = 'TBL_COPIAS_VENTAS';
    protected $primaryKey = 'PK_venta';
    public $timestamps = false; 
     protected $fillable = [
        'usuario_cancelar',
        'fecha_cancelado',
        'estado', // 👈 agrega este
    ];
}
