<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Conta;
use App\Models\Lancamento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelatorioExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_pdf_report_respects_filters_for_logged_user(): void
    {
        [$user, $conta, $categoria] = $this->createBaseData();

        Lancamento::query()->create([
            'user_id' => $user->id,
            'conta_id' => $conta->id,
            'categoria_id' => $categoria->id,
            'tipo' => 'pagar',
            'descricao' => 'Aluguel de marco',
            'valor' => 1500,
            'data_competencia' => '2026-03-01',
            'data_vencimento' => '2026-03-10',
            'status' => 'pendente',
            'parcelas' => 1,
            'parcela_atual' => 1,
        ]);

        Lancamento::query()->create([
            'user_id' => $user->id,
            'conta_id' => $conta->id,
            'categoria_id' => $categoria->id,
            'tipo' => 'receber',
            'descricao' => 'Salario de marco',
            'valor' => 5000,
            'data_competencia' => '2026-03-01',
            'data_vencimento' => '2026-03-05',
            'status' => 'pago',
            'data_pagamento' => '2026-03-05',
            'parcelas' => 1,
            'parcela_atual' => 1,
        ]);

        Lancamento::query()->create([
            'user_id' => $user->id,
            'conta_id' => $conta->id,
            'categoria_id' => $categoria->id,
            'tipo' => 'pagar',
            'descricao' => 'Conta fora do mes',
            'valor' => 200,
            'data_competencia' => '2026-04-01',
            'data_vencimento' => '2026-04-10',
            'status' => 'pendente',
            'parcelas' => 1,
            'parcela_atual' => 1,
        ]);

        $otherUser = User::factory()->create();
        $otherConta = Conta::query()->create([
            'user_id' => $otherUser->id,
            'nome' => 'Conta externa',
            'tipo' => 'corrente',
            'saldo_inicial' => 0,
            'saldo_atual' => 0,
            'ativa' => true,
        ]);
        $otherCategoria = Categoria::query()->create([
            'user_id' => $otherUser->id,
            'nome' => 'Categoria externa',
            'tipo' => 'pagar',
            'ativa' => true,
        ]);
        Lancamento::query()->create([
            'user_id' => $otherUser->id,
            'conta_id' => $otherConta->id,
            'categoria_id' => $otherCategoria->id,
            'tipo' => 'pagar',
            'descricao' => 'Outro usuario',
            'valor' => 100,
            'data_competencia' => '2026-03-01',
            'data_vencimento' => '2026-03-15',
            'status' => 'pendente',
            'parcelas' => 1,
            'parcela_atual' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('relatorios.lancamentos.pdf', [
            'mes' => '2026-03',
            'tipo' => 'pagar',
        ]));

        $response->assertOk();
        $response->assertSeeText('Relatorio de lancamentos');
        $response->assertSeeText('Aluguel de marco');
        $response->assertDontSeeText('Salario de marco');
        $response->assertDontSeeText('Conta fora do mes');
        $response->assertDontSeeText('Outro usuario');
        $response->assertSeeText('Mes: 03/2026');
        $response->assertSeeText('Tipo: Pagar');
    }

    public function test_excel_report_downloads_filtered_spreadsheet_xml(): void
    {
        [$user, $conta, $categoria] = $this->createBaseData();

        Lancamento::query()->create([
            'user_id' => $user->id,
            'conta_id' => $conta->id,
            'categoria_id' => $categoria->id,
            'tipo' => 'pagar',
            'descricao' => 'Internet do escritorio',
            'valor' => 199.9,
            'data_competencia' => '2026-03-01',
            'data_vencimento' => '2026-03-12',
            'status' => 'pendente',
            'parcelas' => 1,
            'parcela_atual' => 1,
        ]);

        Lancamento::query()->create([
            'user_id' => $user->id,
            'conta_id' => $conta->id,
            'categoria_id' => $categoria->id,
            'tipo' => 'receber',
            'descricao' => 'Receita nao filtrada',
            'valor' => 50,
            'data_competencia' => '2026-03-01',
            'data_vencimento' => '2026-03-08',
            'status' => 'pendente',
            'parcelas' => 1,
            'parcela_atual' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('relatorios.lancamentos.excel', [
            'mes' => '2026-03',
            'tipo' => 'pagar',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.ms-excel; charset=UTF-8');
        $response->assertHeader('content-disposition');

        $content = $response->streamedContent();

        $this->assertStringContainsString('<?mso-application progid="Excel.Sheet"?>', $content);
        $this->assertStringContainsString('Internet do escritorio', $content);
        $this->assertStringNotContainsString('Receita nao filtrada', $content);
        $this->assertStringContainsString('Relatorio de lancamentos', $content);
    }

    private function createBaseData(): array
    {
        $user = User::factory()->create();

        $conta = Conta::query()->create([
            'user_id' => $user->id,
            'nome' => 'Conta principal',
            'tipo' => 'corrente',
            'saldo_inicial' => 1000,
            'saldo_atual' => 1000,
            'ativa' => true,
        ]);

        $categoria = Categoria::query()->create([
            'user_id' => $user->id,
            'nome' => 'Moradia',
            'tipo' => 'pagar',
            'ativa' => true,
        ]);

        return [$user, $conta, $categoria];
    }
}
