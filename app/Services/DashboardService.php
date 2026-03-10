<?php

namespace App\Services;

use App\Models\Conta;
use App\Models\Lancamento;
use App\Models\MetaFinanceira;
use Carbon\Carbon;

class DashboardService
{
    public function montar(int $userId, ?string $mes = null): array
    {
        $hoje = Carbon::today();
        $mes = $mes ?: now()->format('Y-m');

        $lancamentos = Lancamento::query()
            ->with(['conta', 'categoria'])
            ->where('user_id', $userId)
            ->doMes($mes)
            ->orderBy('data_vencimento')
            ->get();

        $saldoTotal = Conta::query()
            ->where('user_id', $userId)
            ->sum('saldo_atual');

        $receberMes = (float) $lancamentos->where('tipo', 'receber')->sum('valor');
        $pagarMes = (float) $lancamentos->where('tipo', 'pagar')->sum('valor');

        $vencidos = Lancamento::query()
            ->where('user_id', $userId)
            ->where('status', 'pendente')
            ->whereDate('data_vencimento', '<', $hoje)
            ->count();

        $metas = MetaFinanceira::query()
            ->where('user_id', $userId)
            ->orderBy('data_limite')
            ->take(4)
            ->get();

        $grafico = [];
        for ($i = 5; $i >= 0; $i--) {
            $data = now()->startOfMonth()->subMonths($i);
            $receitas = Lancamento::query()
                ->where('user_id', $userId)
                ->where('tipo', 'receber')
                ->whereYear('data_vencimento', $data->year)
                ->whereMonth('data_vencimento', $data->month)
                ->sum('valor');

            $despesas = Lancamento::query()
                ->where('user_id', $userId)
                ->where('tipo', 'pagar')
                ->whereYear('data_vencimento', $data->year)
                ->whereMonth('data_vencimento', $data->month)
                ->sum('valor');

            $grafico[] = [
                'mes' => $data->format('m/Y'),
                'receitas' => (float) $receitas,
                'despesas' => (float) $despesas,
            ];
        }

        return compact('lancamentos', 'saldoTotal', 'receberMes', 'pagarMes', 'vencidos', 'metas', 'grafico', 'mes');
    }
}
