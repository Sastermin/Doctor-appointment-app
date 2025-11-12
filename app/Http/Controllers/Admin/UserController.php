<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // 👈 usamos el modelo User

class UserController extends Controller
{
    /**
     * Mostrar la lista de usuarios
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Mostrar el formulario para crear un nuevo usuario
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Guardar un nuevo usuario en la base de datos
     */
    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // Crear el usuario
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Alerta de éxito
        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario creado correctamente',
            'text'  => 'El usuario ha sido registrado exitosamente'
        ]);

        return redirect()->route('admin.users.index');
    }

    /**
     * Mostrar el formulario para editar un usuario
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Actualizar los datos del usuario
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Si no hubo cambios
        if ($user->name === $request->name && $user->email === $request->email) {
            session()->flash('swal', [
                'icon'  => 'info',
                'title' => 'Sin cambios',
                'text'  => 'No se detectaron modificaciones'
            ]);
            return redirect()->route('admin.users.edit', $user);
        }

        $user->update($request->only('name', 'email'));

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario actualizado correctamente',
            'text'  => 'Los datos del usuario fueron actualizados exitosamente'
        ]);

        return redirect()->route('admin.users.index');
    }

    /**
     * Eliminar un usuario
     */
    public function destroy(User $user)
    {
        // Evitar que un administrador se borre a sí mismo
        if (auth()->id() === $user->id) {
            session()->flash('swal', [
                'icon'  => 'error',
                'title' => 'Acción no permitida',
                'text'  => 'No puedes eliminar tu propio usuario.'
            ]);
            return redirect()->route('admin.users.index');
        }

        $user->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario eliminado correctamente',
            'text'  => 'El usuario ha sido eliminado exitosamente'
        ]);

        return redirect()->route('admin.users.index');
    }
}

