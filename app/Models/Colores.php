<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Colores extends Model
{
    protected $table = 'CAT_COPIAS_COLOR';
    protected $primaryKey = 'PK_color';

    public static function buscarColores()
    {
        return DB::table('CAT_COPIAS_COLOR')
            ->select('PK_color', 'costo_color')
            ->get();
    }
}
