<?php

namespace App\Services;

use App\Models\Lancamento;
use Carbon\Carbon;

class PrevisaoFinanceiraService
{
    public function gerar(int $userId, int $meses = 6): array
    {
        $saldoAcumulado = 0;
        $dados = [];

        for ($i = 0; $i < $meses; $i++) {
            $data = Carbon::now()->startOfMonth()->addMonths($i);

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

            $saldoMes = (float) $receitas - (float) $despesas;
            $saldoAcumulado += $saldoMes;

            $dados[] = [
                'mes' => $data->translatedFormat('M/Y'),
                'receitas' => (float) $receitas,
                'despesas' => (float) $despesas,
                'saldo_mes' => $saldoMes,
                'saldo_acumulado' => $saldoAcumulado,
            ];
        }

        return $dados;
    }
}
