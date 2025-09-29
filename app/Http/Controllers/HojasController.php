<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hojas;
use Illuminate\Support\Facades\DB;

class HojasController extends Controller
{
    public function index()
    {
        // Consulta los datos de la tabla 'users'
        $ventas = Hojas::all();

        // Devuelve los datos como una respuesta JSON
        return response()->json([
            'success' => true,
            'message' => 'Consulta exitosa',
            'codigo' => 200,
            'data' => $ventas
        ], 200);
    }
}
