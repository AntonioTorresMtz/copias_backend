<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Http\Requests\StoreUsuariosRequest;
use App\Http\Requests\UpdateUsuariosRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    public function index()
    {
        // Consulta los datos de la tabla 'users'
        $ventas = Usuarios::all();

        // Devuelve los datos como una respuesta JSON
        return response()->json([
            'success' => true,
            'message' => 'Consulta exitosa',
            'codigo' => 200,
            'data' => $ventas
        ], 200);
    }
    public function perfil(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Consulta exitosa',
            'codigo' => 200,
            'user' => Auth::user()
        ], 200);
    }

    public function crearUsuario(Request $request)
    {
        $validated = $request->validate([
            'nombre_usuario' => 'required|string|max:255|unique:tbl_usuarios',
            'nombre_real' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'permisos' => 'required|integer',
        ]);
        $usuario = Usuarios::create([
            'nombre_usuario' => $validated['nombre_usuario'],
            'nombre_real' => $validated['nombre_real'],
            'password' => Hash::make($validated['password']), // Encripta la contraseña
            'permisos' => $validated['permisos'], // Encriptar contraseña
        ]);
        return response()->json([
            'mensaje' => 'Usuario creado correctamente',
            'success' => true,
            'codigo' => 201,
            'usuario' => $usuario,
        ], 201);
    }


    public function eliminarUsuario($id)
    {
        $usuario = Usuarios::find($id);
        if ($usuario) {
            $usuario->delete();
            return response()->json([
                'mensaje' => 'Usuario eliminado correctamente',
                'success' => true,
                'codigo' => 201,
            ], 201);
        } else {
            return response()->json([
                'mensaje' => 'Usuario no encontrado',
                'success' => false,
                'codigo' => 404,
            ], 404);
        }

    }

    public function editarUsuario(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nombre_usuario' => 'required|string|max:255|unique:tbl_usuarios',
                'nombre_real' => 'required|string|max:255',
                'permisos' => 'required|integer',
            ]);

            // Buscar el usuario por ID
            $usuario = Usuarios::findOrFail($id);

            // Actualizar los datos
            $usuario->update([
                'nombre_usuario' => $validated['nombre_usuario'],
                'nombre_real' => $validated['nombre_real'],
                'permisos' => $validated['permisos'],
            ]);
            return response()->json([
                'mensaje' => 'Usuario actualizado correctamente',
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

}
