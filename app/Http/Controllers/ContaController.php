<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContaController extends Controller
{
    public function index(): View
    {
        $contas = Conta::query()
            ->where('user_id', (int) Auth::id())
            ->latest()
            ->get();

        return view('contas.index', compact('contas'));
    }

    public function create(): View
    {
        return view('contas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'tipo' => ['required', 'in:conta,cash,cartao,poupanca'],
            'saldo_inicial' => ['required', 'numeric'],
            'cor' => ['nullable', 'string', 'max:20'],
            'icone' => ['nullable', 'string', 'max:50'],
        ]);

        Conta::query()->create([
            ...$dados,
            'user_id' => (int) Auth::id(),
            'saldo_atual' => $dados['saldo_inicial'],
            'ativa' => true,
        ]);

        return redirect()->route('contas.index')->with('success', 'Conta criada com sucesso.');
    }

    public function edit(Conta $conta): View
    {
        abort_if($conta->user_id !== (int) Auth::id(), 403);

        return view('contas.edit', compact('conta'));
    }

    public function update(Request $request, Conta $conta): RedirectResponse
    {
        abort_if($conta->user_id !== (int) Auth::id(), 403);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'tipo' => ['required', 'in:conta,cash,cartao,poupanca'],
            'cor' => ['nullable', 'string', 'max:20'],
            'icone' => ['nullable', 'string', 'max:50'],
            'ativa' => ['nullable', 'boolean'],
        ]);

        $conta->update([
            ...$dados,
            'ativa' => $request->boolean('ativa'),
        ]);

        return redirect()->route('contas.index')->with('success', 'Conta atualizada com sucesso.');
    }

    public function destroy(Conta $conta): RedirectResponse
    {
        abort_if($conta->user_id !== (int) Auth::id(), 403);
        $conta->delete();

        return redirect()->route('contas.index')->with('success', 'Conta removida com sucesso.');
    }
}
