<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ventas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Colores;
use App\Models\Hojas;
class VentasController extends Controller
{
    public function index()
    {
        // Consulta los datos de la tabla 'users'
        $ventas = Ventas::all();

        // Devuelve los datos como una respuesta JSON
        return response()->json([
            'success' => true,
            'message' => 'Consulta exitosa',
            'codigo' => 200,
            'data' => $ventas
        ], 200);
    }
    public function insertarDetalleVenta(Request $request, $id)
    {
        //Guardar gastos de color y hoja
        $colores = Colores::buscarColores();
        $hojas = Hojas::buscarHojas();

        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            '*.id' => 'required|integer',
            '*.servicio' => 'required|integer',
            '*.cantidad' => 'required|integer|min:1',
            '*.color' => 'required|integer',
            '*.precio' => 'required|numeric|min:0',
            '*.hoja' => 'required|integer',
            '*.desperdicio' => 'required|integer',
            '*.cara' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // ✅ Insertar los datos en la base de datos
        try {
            DB::beginTransaction();

            // ✅ 1. Insertar en TBL_COPIAS_VENTAS
            $total = 0;
            foreach ($request->all() as $detalle) {
                $total += ($detalle['cantidad'] * $detalle['precio']);
            }

            $idVenta = DB::table('TBL_COPIAS_VENTAS')->insertGetId([
                'total' => $total,
                'usuario_crear' => $id
            ]);
            // ✅ 2. Insertar en TBL_COPIAS y REL_COPIAS_VENTAS
            foreach ($request->all() as $detalle) {
                $costo_color = ($colores[$detalle['color'] - 1]->costo_color) * $detalle['cantidad'] * $detalle['cara'];
                $costo_hoja = ($hojas[$detalle['hoja'] - 1]->costo_hoja) * $detalle['cantidad'];
                $desperdicio_hoja = ($hojas[$detalle['hoja'] - 1]->costo_hoja) * $detalle['desperdicio'];
                $desperdicio_color = ($colores[$detalle['color'] - 1]->costo_color) * $detalle['desperdicio'] * $detalle['cara'];

                $idDetalle = DB::table('TBL_COPIAS')->insertGetId([
                    'FK_color' => $detalle['color'],
                    'FK_servicio' => $detalle['servicio'],
                    'FK_hoja' => $detalle['hoja'],
                    'precio_unitario' => $detalle['precio'],
                    'cantidad' => $detalle['cantidad'],
                    'desperdicio' => $detalle['desperdicio'],
                    'caras' => $detalle['cara'],
                    'costo_tienda' => $costo_color + $costo_hoja + $desperdicio_color + $desperdicio_hoja
                ]);

                // ✅ 3. Insertar en REL_DETALLE_VENTA
                DB::table('REL_COPIAS_VENTAS')->insert([
                    'FK_copia' => $idDetalle,
                    'FK_venta' => $idVenta,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta registrada con exito',
                'codigo' => 201,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al insertar detalle de venta: ' . $e->getMessage()], 500);
        }
    }

    public function cancelarVenta(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_usuario' => 'required|integer',
                'id_venta' => 'required|integer',
            ]);

            // Buscar el usuario por ID
            $venta = Ventas::findOrFail($validated['id_venta']);

            // Actualizar los datos
            $venta->update([
                'estado' => 0,
                'usuario_cancelar' => $validated['id_usuario'],
                'fecha_cancelado' => now()
            ]);
            return response()->json([
                'mensaje' => 'Venta cancelada con exito',
                'success' => true,
                'codigo' => 201,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el usuario', 'error' => $e->getMessage()], 500);
        }

    }

    public function obtenerDetalleVentas($id)
    {
        $detalles = DB::table('tbl_copias as c')
            ->join('cat_copias_color as col', 'col.PK_color', '=', 'c.FK_color')
            ->join('cat_copias_servicios as ser', 'ser.PK_impresion', '=', 'c.FK_servicio')
            ->join('cat_copias_hojas as ho', 'ho.PK_hoja', '=', 'c.FK_hoja')
            ->join('rel_copias_ventas as rel', 'rel.FK_copia', '=', 'c.PK_copia')
            ->join('tbl_copias_ventas as v', 'v.PK_venta', '=', 'rel.FK_venta')
            ->where('v.PK_venta', $id)
            ->select(
                'ser.tipo_impresion',
                'col.tipo_color',
                'ho.tipo_hoja',
                'c.precio_unitario',
                'c.cantidad',
                'c.desperdicio',
                'c.caras',
                'c.fecha'
            )
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Consulta exitosa',
            'codigo' => 200,
            'data' => $detalles
        ], 200);
    }


}
