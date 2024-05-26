<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadHistory;
use Illuminate\Support\Facades\Auth;

class UploadHistoryController extends Controller
{
    public function index()
    {
        // Busca os registros de histórico do usuário logado
        $user_id = Auth::id(); // Obtém o ID do usuário logado
        $histories = UploadHistory::where('user_id', $user_id)->get();

        return view('upload_history', compact('histories'));
    }
}

