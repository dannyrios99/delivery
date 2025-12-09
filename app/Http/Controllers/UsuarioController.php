<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function show()
    {
        // Traer todos los usuarios registrados
        $usuarios = User::all();

        // Retornar la vista users/show.blade.php
        return view('usuarios.index', compact('usuarios'));
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'username' => 'required|string|max:40|unique:users,username',
                'role'     => 'required|string',
                'password' => 'required|string|min:6|confirmed',
            ]);

            DB::transaction(function () use ($request) {
                User::create([
                    'name'     => $request->name,
                    'username' => $request->username,
                    'role'     => $request->role,
                    'password' => Hash::make($request->password),
                ]);
            });

            return back()->with('success', 'Usuario creado correctamente.');
        } catch (\Throwable $e) {
            \Log::error('Error al crear usuario: ' . $e->getMessage());
            return back()->with('error', 'Error al crear usuario: ' . $e->getMessage());
        }
    }

   public function update(Request $request, $id)
    {
        try {

            // Validación
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'username' => 'required|string|max:40|unique:users,username,' . $id,
                'role'     => 'required|string',
                'password' => 'nullable|string|min:6|confirmed',
            ]);

            DB::transaction(function () use ($validated, $id) {

                $user = User::findOrFail($id);

                $user->name = $validated['name'];
                $user->username = $validated['username'];
                $user->role = $validated['role'];

                // Si envió contraseña, actualizarla
                if (!empty($validated['password'])) {
                    $user->password = Hash::make($validated['password']);
                }

                $user->save();
            });

            return back()->with('success', 'Usuario actualizado correctamente.');

        } catch (\Throwable $e) {
            \Log::error('Error al actualizar usuario: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar usuario: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Usuario eliminado correctamente.');
    }
}