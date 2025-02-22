<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadHistory;
use Illuminate\Database\Console\ShowCommand;
use Illuminate\Support\Facades\Auth;

class UploadHistoryController extends Controller
{
       
    public function index(Request $request)
    {
        $user = Auth::user(); // Obtém o usuário logado
        $query = UploadHistory::query();

        // Aplica filtro baseado em user_id para não-admins ou admin sem filtro específico
        //if ($user->is_admin != 1 || !$request->has('i_cod_cnes_fonte')) {
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }

        // Se o usuário é admin e um filtro por i_cod_cnes_fonte foi aplicado
        if ($user->is_admin == 1 && $request->has('i_cod_cnes_fonte') && $request->i_cod_cnes_fonte != '') {
            $query->where('i_cod_cnes_fonte', $request->i_cod_cnes_fonte);
        }

        // Aplicar filtro por in_conferido (se checado)
        if ($request->has('in_conferido')) {
            $query->where('in_conferido', true);
        }


        $histories = $query->get();

        // Para admin, fornecer a lista de códigos CNES, para não-admins não fornecer
        $cnesCodes = $user->is_admin == 1 ? UploadHistory::select('i_cod_cnes_fonte')->distinct()->pluck('i_cod_cnes_fonte') : collect();

        return view('upload_history', compact('histories', 'cnesCodes', 'user'));
    }

    public function downloadFile($filename)
    {
        $path = storage_path('app/'.config('filesystems.paths.uploads') . $filename);

        if (!file_exists($path)) {
            abort(404, config('filesystems.paths.uploads') );
        }

        // Verificação adicional para garantir que o usuário tem direito ao arquivo
        $user = auth()->user();
        if (!$user->is_admin) { // Exemplo: só permitir para administradores
            abort(403, 'Você não tem permissão para acessar este arquivo.');
        }

        return response()->download($path);
    }


    public function marcarConferido(Request $request, $id)
    {
        $history = UploadHistory::findOrFail($id);
        $history->in_conferido = $request->has('in_conferido');
        $history->save();

        return redirect()->route('upload_history')->with('success', 'Arquivo conferido com sucesso!');
    }


    
}

