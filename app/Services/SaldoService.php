<?php

namespace App\Services;

use App\Models\Conta;
use App\Models\Lancamento;

class SaldoService
{
    public function recalcularConta(Conta $conta): void
    {
        $receitas = Lancamento::query()
            ->where('conta_id', $conta->id)
            ->where('status', 'pago')
            ->where('tipo', 'receber')
            ->sum('valor');

        $despesas = Lancamento::query()
            ->where('conta_id', $conta->id)
            ->where('status', 'pago')
            ->where('tipo', 'pagar')
            ->sum('valor');

        $conta->update([
            'saldo_atual' => $conta->saldo_inicial + $receitas - $despesas,
        ]);
    }
}
