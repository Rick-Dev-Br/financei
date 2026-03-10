<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Conta;
use App\Models\Lancamento;
use App\Services\SaldoService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LancamentoController extends Controller
{
    public function __construct(private SaldoService $saldoService)
    {
    }

    public function index(Request $request): View
    {
        $query = Lancamento::query()
            ->with(['conta', 'categoria'])
            ->where('user_id', (int) Auth::id());

        if ($request->filled('mes')) {
            $query->doMes($request->string('mes')->toString());
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->string('tipo')->toString());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('q')) {
            $termo = $request->string('q')->toString();
            $query->where('descricao', 'like', "%{$termo}%");
        }

        $lancamentos = $query->orderBy('data_vencimento')->paginate(12)->withQueryString();

        return view('lancamentos.index', compact('lancamentos'));
    }

    public function create(): View
    {
        return view('lancamentos.create', [
            'contas' => Conta::query()
                ->where('user_id', (int) Auth::id())
                ->where('ativa', true)
                ->orderBy('nome')
                ->get(),
            'categorias' => Categoria::query()
                ->where('user_id', (int) Auth::id())
                ->where('ativa', true)
                ->orderBy('nome')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $this->validar($request);
        $dados = $this->normalizarRecorrencia($dados);

        Lancamento::query()->create([
            ...$dados,
            'user_id' => (int) Auth::id(),
            'status' => 'pendente',
            'parcela_atual' => 1,
        ]);

        if ($dados['recorrente'] && $dados['parcelas'] > 1) {
            $intervaloMeses = $this->mesesPorFrequencia($dados['frequencia']);

            for ($i = 2; $i <= $dados['parcelas']; $i++) {
                Lancamento::query()->create([
                    ...$dados,
                    'user_id' => (int) Auth::id(),
                    'status' => 'pendente',
                    'parcela_atual' => $i,
                    'data_competencia' => Carbon::parse($dados['data_competencia'])->addMonths(($i - 1) * $intervaloMeses),
                    'data_vencimento' => Carbon::parse($dados['data_vencimento'])->addMonths(($i - 1) * $intervaloMeses),
                ]);
            }
        }

        return redirect()->route('lancamentos.index')->with('success', 'Lancamento cadastrado com sucesso.');
    }

    public function edit(Lancamento $lancamento): View
    {
        abort_if($lancamento->user_id !== (int) Auth::id(), 403);

        return view('lancamentos.edit', [
            'lancamento' => $lancamento,
            'contas' => Conta::query()
                ->where('user_id', (int) Auth::id())
                ->orderBy('nome')
                ->get(),
            'categorias' => Categoria::query()
                ->where('user_id', (int) Auth::id())
                ->orderBy('nome')
                ->get(),
        ]);
    }

    public function update(Request $request, Lancamento $lancamento): RedirectResponse
    {
        abort_if($lancamento->user_id !== (int) Auth::id(), 403);

        $dados = $this->validar($request);
        $dados = $this->normalizarRecorrencia($dados);

        $lancamento->update($dados);

        return redirect()->route('lancamentos.index')->with('success', 'Lancamento atualizado com sucesso.');
    }

    public function destroy(Lancamento $lancamento): RedirectResponse
    {
        abort_if($lancamento->user_id !== (int) Auth::id(), 403);

        $conta = $lancamento->conta;
        $lancamento->delete();

        if ($conta) {
            $this->saldoService->recalcularConta($conta);
        }

        return redirect()->route('lancamentos.index')->with('success', 'Lancamento removido com sucesso.');
    }

    public function baixar(Lancamento $lancamento): RedirectResponse
    {
        abort_if($lancamento->user_id !== (int) Auth::id(), 403);

        $lancamento->update([
            'status' => 'pago',
            'data_pagamento' => now()->toDateString(),
        ]);

        $this->saldoService->recalcularConta($lancamento->conta);

        return back()->with('success', 'Baixa realizada com sucesso. Saldo atualizado automaticamente.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'conta_id' => ['required', Rule::exists('contas', 'id')->where('user_id', (int) Auth::id())],
            'categoria_id' => ['required', Rule::exists('categorias', 'id')->where('user_id', (int) Auth::id())],
            'tipo' => ['required', 'in:pagar,receber'],
            'descricao' => ['required', 'string', 'max:140'],
            'valor' => ['required', 'numeric', 'min:0.01'],
            'data_competencia' => ['required', 'date'],
            'data_vencimento' => ['required', 'date'],
            'observacoes' => ['nullable', 'string'],
            'recorrente' => ['nullable', 'boolean'],
            'frequencia' => ['nullable', 'in:mensal,bimestral,trimestral,anual'],
            'parcelas' => ['nullable', 'integer', 'min:1', 'max:48'],
        ], [
            'categoria_id.exists' => 'A categoria selecionada nao pertence ao usuario logado.',
            'conta_id.exists' => 'A conta selecionada nao pertence ao usuario logado.',
        ]);
    }

    private function normalizarRecorrencia(array $dados): array
    {
        $dados['recorrente'] = (bool) ($dados['recorrente'] ?? false);

        if (!$dados['recorrente']) {
            $dados['frequencia'] = null;
            $dados['parcelas'] = 1;

            return $dados;
        }

        $dados['frequencia'] = $dados['frequencia'] ?? 'mensal';
        $dados['parcelas'] = max(1, (int) ($dados['parcelas'] ?? 1));

        return $dados;
    }

    private function mesesPorFrequencia(?string $frequencia): int
    {
        return match ($frequencia) {
            'bimestral' => 2,
            'trimestral' => 3,
            'anual' => 12,
            default => 1,
        };
    }
}
