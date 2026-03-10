<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Conta;
use App\Models\Lancamento;
use App\Models\MetaFinanceira;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoFinanceiroSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => 'demo@financeiro.test'],
            [
                'name' => 'Usuario Demo',
                'password' => Hash::make('12345678'),
            ]
        );

        $contaPrincipal = Conta::query()->create([
            'user_id' => $user->id,
            'nome' => 'Conta Principal',
            'tipo' => 'conta',
            'saldo_inicial' => 2500,
            'saldo_atual' => 2500,
            'cor' => '#0d6efd',
            'icone' => 'bi-wallet2',
            'ativa' => true,
        ]);

        $caixa = Conta::query()->create([
            'user_id' => $user->id,
            'nome' => 'Caixa',
            'tipo' => 'cash',
            'saldo_inicial' => 300,
            'saldo_atual' => 300,
            'cor' => '#198754',
            'icone' => 'bi-cash-stack',
            'ativa' => true,
        ]);

        $salario = Categoria::query()->create([
            'user_id' => $user->id,
            'nome' => 'Salario',
            'tipo' => 'receber',
            'icone' => 'bi-coin',
            'cor' => '#198754',
            'ativa' => true,
        ]);

        $vendas = Categoria::query()->create([
            'user_id' => $user->id,
            'nome' => 'Vendas',
            'tipo' => 'receber',
            'icone' => 'bi-cart-check',
            'cor' => '#20c997',
            'ativa' => true,
        ]);

        $aluguel = Categoria::query()->create([
            'user_id' => $user->id,
            'nome' => 'Aluguel',
            'tipo' => 'pagar',
            'icone' => 'bi-house',
            'cor' => '#dc3545',
            'ativa' => true,
        ]);

        $fornecedor = Categoria::query()->create([
            'user_id' => $user->id,
            'nome' => 'Fornecedor',
            'tipo' => 'pagar',
            'icone' => 'bi-box-seam',
            'cor' => '#fd7e14',
            'ativa' => true,
        ]);

        for ($i = 0; $i < 8; $i++) {
            $data = Carbon::now()->startOfMonth()->subMonths(2)->addMonths($i);

            Lancamento::query()->create([
                'user_id' => $user->id,
                'conta_id' => $contaPrincipal->id,
                'categoria_id' => $salario->id,
                'tipo' => 'receber',
                'descricao' => 'Recebimento mensal - ' . $data->format('m/Y'),
                'valor' => 3500,
                'data_competencia' => $data->copy()->day(5),
                'data_vencimento' => $data->copy()->day(5),
                'data_pagamento' => $data->copy()->day(5),
                'status' => 'pago',
                'observacoes' => 'Lancamento gerado pelo seeder.',
                'recorrente' => true,
                'frequencia' => 'mensal',
                'parcelas' => 12,
                'parcela_atual' => $i + 1,
            ]);

            Lancamento::query()->create([
                'user_id' => $user->id,
                'conta_id' => $contaPrincipal->id,
                'categoria_id' => $aluguel->id,
                'tipo' => 'pagar',
                'descricao' => 'Aluguel - ' . $data->format('m/Y'),
                'valor' => 1200,
                'data_competencia' => $data->copy()->day(10),
                'data_vencimento' => $data->copy()->day(10),
                'data_pagamento' => $i < 3 ? $data->copy()->day(10) : null,
                'status' => $i < 3 ? 'pago' : 'pendente',
                'observacoes' => 'Despesa fixa mensal.',
                'recorrente' => true,
                'frequencia' => 'mensal',
                'parcelas' => 12,
                'parcela_atual' => $i + 1,
            ]);
        }

        Lancamento::query()->create([
            'user_id' => $user->id,
            'conta_id' => $caixa->id,
            'categoria_id' => $vendas->id,
            'tipo' => 'receber',
            'descricao' => 'Venda futura especial',
            'valor' => 950,
            'data_competencia' => now()->addMonth()->day(12),
            'data_vencimento' => now()->addMonth()->day(12),
            'status' => 'pendente',
            'observacoes' => 'Receita prevista para o proximo mes.',
            'recorrente' => false,
            'parcelas' => 1,
            'parcela_atual' => 1,
        ]);

        Lancamento::query()->create([
            'user_id' => $user->id,
            'conta_id' => $contaPrincipal->id,
            'categoria_id' => $fornecedor->id,
            'tipo' => 'pagar',
            'descricao' => 'Reposicao de estoque',
            'valor' => 680,
            'data_competencia' => now()->addDays(5),
            'data_vencimento' => now()->addDays(5),
            'status' => 'pendente',
            'observacoes' => 'Compra para manter estoque.',
            'recorrente' => false,
            'parcelas' => 1,
            'parcela_atual' => 1,
        ]);

        MetaFinanceira::query()->create([
            'user_id' => $user->id,
            'titulo' => 'Reserva de emergencia',
            'descricao' => 'Meta para seis meses de despesas.',
            'valor_meta' => 10000,
            'valor_atual' => 4200,
            'data_limite' => now()->addMonths(8),
            'status' => 'ativa',
        ]);

        MetaFinanceira::query()->create([
            'user_id' => $user->id,
            'titulo' => 'Novo computador',
            'descricao' => 'Compra para trabalho e estudo.',
            'valor_meta' => 6500,
            'valor_atual' => 1800,
            'data_limite' => now()->addMonths(5),
            'status' => 'ativa',
        ]);
    }
}
