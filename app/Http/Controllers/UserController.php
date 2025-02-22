<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Método para listar usuários
    public function index()
    {
        $users = User::all(); // Obtém todos os usuários
        return view('users.index', compact('users'));
    }

    // Método para excluir um usuário
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso.');
    }
}
