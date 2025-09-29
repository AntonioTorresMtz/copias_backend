<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Hojas extends Model
{
    protected $table = 'CAT_COPIAS_HOJAS';
    protected $primaryKey = 'PK_hoja';

    public static function buscarHojas()
    {
        return DB::table('CAT_COPIAS_HOJAS')
            ->select('PK_hoja', 'costo_hoja')
            ->get();
    }

}
