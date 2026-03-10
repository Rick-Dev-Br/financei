<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Conta;
use App\Models\Lancamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BuscaController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));

        $lancamentos = collect();
        $contas = collect();
        $categorias = collect();

        if ($q !== '') {
            $lancamentos = Lancamento::query()
                ->where('user_id', (int) Auth::id())
                ->where('descricao', 'like', "%{$q}%")
                ->take(15)
                ->get();

            $contas = Conta::query()
                ->where('user_id', (int) Auth::id())
                ->where('nome', 'like', "%{$q}%")
                ->take(10)
                ->get();

            $categorias = Categoria::query()
                ->where('user_id', (int) Auth::id())
                ->where('nome', 'like', "%{$q}%")
                ->take(10)
                ->get();
        }

        return view('busca.index', compact('q', 'lancamentos', 'contas', 'categorias'));
    }
}
