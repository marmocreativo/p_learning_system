<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Clase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $usuarios = User::paginate();
        return view('admin/usuario_lista', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $clases = Clase::where('elementos','usuarios')->get();
        return view('admin/usuario_form', compact('clases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        
        $usuario = new User();

        $usuario->legacy_id = uniqid('',true);
        $usuario->nombre = $request->Nombre;
        $usuario->apellidos = $request->Apellidos;
        $usuario->email = $request->Email;
        $usuario->telefono = $request->Telefono;
        $usuario->whatsapp = $request->Whatsapp;
        $usuario->fecha_nacimiento = $request->FechaNacimiento;
        $usuario->password = Hash::make($request->Password);
        $usuario->lista_correo = $request->ListaCorreo;
        $usuario->imagen = 'default.jpg';
        $usuario->clase = $request->Clase;
        $usuario->estado = $request->Estado;

        $usuario->save();

        return redirect()->route('admin_usuarios.show', $usuario->id);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        
        $usuario = User::find($id);
        return view('admin/usuario_detalles', compact('usuario'));
        

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $clases = Clase::where('elementos','usuarios')->get();
        $usuario = User::find($id);
        return view('admin/usuario_form_actualizar')->with(compact('clases','usuario'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        
        $usuario = User::find($id);

        $usuario->legacy_id = $request->LegacyId;
        $usuario->nombre = $request->Nombre;
        $usuario->apellidos = $request->Apellidos;
        $usuario->email = $request->Email;
        $usuario->telefono = $request->Telefono;
        $usuario->whatsapp = $request->Whatsapp;
        $usuario->fecha_nacimiento = $request->FechaNacimiento;
        $usuario->lista_correo = $request->ListaCorreo;
        $usuario->clase = $request->Clase;
        $usuario->estado = $request->Estado;

        $usuario->save();

        return redirect()->route('admin_usuarios.show', $usuario->id);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        
        $usuario = User::find($id);
        $usuario->delete();
        return redirect()->route('admin_usuarios');
        
    }
}
