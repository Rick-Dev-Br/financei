<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function index(): View
    {
        $categorias = Categoria::query()
            ->where('user_id', auth()->id())
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
            'cor' => ['nullable', 'string', 'max:20'],
        ]);

        Categoria::query()->create([
            ...$dados,
            'user_id' => auth()->id(),
            'ativa' => true,
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoria criada com sucesso.');
    }

    public function edit(Categoria $categoria): View
    {
        abort_if($categoria->user_id !== auth()->id(), 403);

        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        abort_if($categoria->user_id !== auth()->id(), 403);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'tipo' => ['required', 'in:pagar,receber'],
            'icone' => ['nullable', 'string', 'max:50'],
            'cor' => ['nullable', 'string', 'max:20'],
            'ativa' => ['nullable', 'boolean'],
        ]);

        $categoria->update([
            ...$dados,
            'ativa' => $request->boolean('ativa'),
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        abort_if($categoria->user_id !== auth()->id(), 403);
        $categoria->delete();

        return redirect()->route('categorias.index')->with('success', 'Categoria removida com sucesso.');
    }
}
