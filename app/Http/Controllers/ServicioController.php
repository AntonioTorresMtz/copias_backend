<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Illuminate\Support\Facades\DB;

class ServicioController extends Controller
{
    public function index()
    {
        // Consulta los datos de la tabla 'users'
        $ventas = Servicio::all();

        // Devuelve los datos como una respuesta JSON
        return response()->json([
            'success' => true,
            'message' => 'Consulta exitosa',
            'codigo' => 200,
            'data' => $ventas
        ], 200);
    }

    public function buscarServicio($servicio)
    {
        // Hacer la consulta con Query Builder
        $resultados = DB::table('cat_copias_servicios')
            ->select('PK_impresion', 'tipo_impresion')
            ->where('tipo_impresion', 'like', '%' . $servicio . '%')
            ->get();

        // Retornar en JSON (ideal para APIs)

        return response()->json([
            'success' => true,
            'message' => 'Consulta exitosa',
            'codigo' => 200,
            'data' => $resultados
        ], 200);
    }

    public function buscarServicioClave($clave)
    {
        // Hacer la consulta con Query Builder
        $resultados = DB::table('cat_copias_servicios')
            ->select('PK_impresion', 'tipo_impresion')
            ->where('tipo_impresion', $clave)
            ->get();

        // Retornar en JSON (ideal para APIs)

        return response()->json([
            'success' => true,
            'message' => 'Consulta exitosa',
            'codigo' => 200,
            'data' => $resultados
        ], 200);
    }

}
