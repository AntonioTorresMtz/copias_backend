<?php

use App\Http\Controllers\ServicioController;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\HojasController;
use App\Http\Controllers\ColoresController;
use App\Http\Controllers\VentasController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('/usuarios', [UsuariosController::class, 'index']);
Route::middleware('auth:sanctum')->delete('/usuarios/{id}', [UsuariosController::class, 'eliminarUsuario']);
Route::middleware('auth:sanctum')->patch('/usuarios/{id}', [UsuariosController::class, 'editarUsuario']);
Route::post('/usuarios/crearUsuario', [UsuariosController::class, 'crearUsuario']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/perfil', [UsuariosController::class, 'perfil']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/servicios', [ServicioController::class, 'index']);
Route::middleware('auth:sanctum')->get('/servicios/buscarServicio/{servicio}', [ServicioController::class, 'buscarServicio']);
Route::middleware('auth:sanctum')->get('/servicios/buscarServicioClave/{clave}', [ServicioController::class, 'buscarServicioClave']);
Route::middleware('auth:sanctum')->get('/hojas', [HojasController::class, 'index']);
Route::middleware('auth:sanctum')->get('/hojas', [HojasController::class, 'index']);
Route::middleware('auth:sanctum')->get('/colores', [ColoresController::class, 'index']);
Route::middleware('auth:sanctum')->get('/ventas', [VentasController::class, 'index']);
Route::middleware('auth:sanctum')->post('/ventas/InsertarDetalleVenta/{id}', [VentasController::class, 'insertarDetalleVenta']);
Route::middleware('auth:sanctum')->patch('/ventas/cancelarVenta', [VentasController::class, 'cancelarVenta']);
Route::middleware('auth:sanctum')->get('/ventas/obtenerDetalleVentas/{id}', [VentasController::class, 'obtenerDetalleVentas']);