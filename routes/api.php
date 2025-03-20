<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuariosController;



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
