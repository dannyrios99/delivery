<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function index()
    {
        $sucursales = Sucursal::orderBy('nombre')->get();
        return view('sucursales.index', compact('sucursales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'ciudad' => 'nullable|string|max:100'
        ]);

        Sucursal::create($request->all());

        return back()->with('success', 'Sucursal creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'ciudad' => 'nullable|string|max:100'
        ]);

        $sucursal = Sucursal::findOrFail($id);
        $sucursal->update($request->all());

        return back()->with('success', 'Sucursal actualizada correctamente.');
    }

    public function destroy($id)
    {
        Sucursal::findOrFail($id)->delete();
        return back()->with('success', 'Sucursal eliminada correctamente.');
    }
}
