<?php

namespace App\Http\Controllers;

use App\Models\MetaFinanceira;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MetaFinanceiraController extends Controller
{
    public function index(): View
    {
        $metas = MetaFinanceira::query()
            ->where('user_id', auth()->id())
            ->orderBy('data_limite')
            ->get();

        return view('metas.index', compact('metas'));
    }

    public function create(): View
    {
        return view('metas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $this->validar($request);

        MetaFinanceira::query()->create([
            ...$dados,
            'user_id' => auth()->id(),
            'status' => 'ativa',
        ]);

        return redirect()->route('metas.index')->with('success', 'Meta criada com sucesso.');
    }

    public function edit(MetaFinanceira $meta): View
    {
        abort_if($meta->user_id !== auth()->id(), 403);

        return view('metas.edit', ['meta' => $meta]);
    }

    public function update(Request $request, MetaFinanceira $meta): RedirectResponse
    {
        abort_if($meta->user_id !== auth()->id(), 403);

        $meta->update($this->validar($request));

        return redirect()->route('metas.index')->with('success', 'Meta atualizada com sucesso.');
    }

    public function destroy(MetaFinanceira $meta): RedirectResponse
    {
        abort_if($meta->user_id !== auth()->id(), 403);
        $meta->delete();

        return redirect()->route('metas.index')->with('success', 'Meta removida com sucesso.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'titulo' => ['required', 'string', 'max:100'],
            'descricao' => ['nullable', 'string'],
            'valor_meta' => ['required', 'numeric', 'min:1'],
            'valor_atual' => ['required', 'numeric', 'min:0'],
            'data_limite' => ['required', 'date'],
        ]);
    }
}
