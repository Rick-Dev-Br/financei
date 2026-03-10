<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function index(): View
    {
        $categorias = Categoria::query()
            ->where('user_id', (int) Auth::id())
            ->orderBy('tipo')
            ->orderBy('nome')
            ->get();

        return view('categorias.index', compact('categorias'));
    }

    public function create(): View
    {
        return view('categorias.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'tipo' => ['required', 'in:pagar,receber'],
            'icone' => ['nullable', 'string', 'max:50'],
            'cor' => ['nullable', 'string', 'max:20', 'regex:/^#?[0-9A-Fa-f]{6}$/'],
        ]);

        $dados = $this->normalizarVisual($dados);

        Categoria::query()->create([
            ...$dados,
            'user_id' => (int) Auth::id(),
            'ativa' => true,
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoria criada com sucesso.');
    }

    public function edit(Categoria $categoria): View
    {
        abort_if($categoria->user_id !== (int) Auth::id(), 403);

        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        abort_if($categoria->user_id !== (int) Auth::id(), 403);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'tipo' => ['required', 'in:pagar,receber'],
            'icone' => ['nullable', 'string', 'max:50'],
            'cor' => ['nullable', 'string', 'max:20', 'regex:/^#?[0-9A-Fa-f]{6}$/'],
            'ativa' => ['nullable', 'boolean'],
        ]);

        $dados = $this->normalizarVisual($dados);

        $categoria->update([
            ...$dados,
            'ativa' => $request->boolean('ativa'),
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoria atualizada com sucesso.');
    }



    private function normalizarVisual(array $dados): array
    {
        $icone = trim((string) ($dados['icone'] ?? ''));
        $dados['icone'] = $icone !== '' ? $icone : 'bi-tag';

        $cor = trim((string) ($dados['cor'] ?? ''));
        if ($cor === '') {
            $dados['cor'] = '#0d6efd';
        } else {
            $dados['cor'] = str_starts_with($cor, '#') ? $cor : "#{$cor}";
        }

        return $dados;
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        abort_if($categoria->user_id !== (int) Auth::id(), 403);
        $categoria->delete();

        return redirect()->route('categorias.index')->with('success', 'Categoria removida com sucesso.');
    }
}
