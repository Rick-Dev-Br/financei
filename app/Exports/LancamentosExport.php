<?php

namespace App\Exports;

use Illuminate\Support\Collection;

class LancamentosExport
{
    public function __construct(private Collection $lancamentos)
    {
    }

    public function rows(): array
    {
        return $this->lancamentos->map(fn ($lancamento) => [
            'descricao' => (string) $lancamento->descricao,
            'tipo' => ucfirst((string) $lancamento->tipo),
            'conta' => (string) ($lancamento->conta?->nome ?? '-'),
            'categoria' => (string) ($lancamento->categoria?->nome ?? '-'),
            'data_competencia' => $lancamento->data_competencia?->format('d/m/Y') ?? '-',
            'data_vencimento' => $lancamento->data_vencimento?->format('d/m/Y') ?? '-',
            'data_pagamento' => $lancamento->data_pagamento?->format('d/m/Y') ?? '-',
            'status' => ucfirst((string) $lancamento->status),
            'valor' => (float) $lancamento->valor,
        ])->all();
    }

    public function summary(): array
    {
        $receitas = (float) $this->lancamentos
            ->where('tipo', 'receber')
            ->sum(fn ($lancamento) => (float) $lancamento->valor);

        $despesas = (float) $this->lancamentos
            ->where('tipo', 'pagar')
            ->sum(fn ($lancamento) => (float) $lancamento->valor);

        return [
            'quantidade' => $this->lancamentos->count(),
            'receitas' => $receitas,
            'despesas' => $despesas,
            'saldo' => $receitas - $despesas,
        ];
    }

    public function toSpreadsheetXml(): string
    {
        $summary = $this->summary();
        $rows = $this->rows();

        $xml = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<?mso-application progid="Excel.Sheet"?>',
            '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"',
            ' xmlns:o="urn:schemas-microsoft-com:office:office"',
            ' xmlns:x="urn:schemas-microsoft-com:office:excel"',
            ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">',
            '<Styles>',
            '<Style ss:ID="header"><Font ss:Bold="1"/><Interior ss:Color="#E5EEF9" ss:Pattern="Solid"/></Style>',
            '<Style ss:ID="title"><Font ss:Bold="1" ss:Size="14"/></Style>',
            '<Style ss:ID="currency"><NumberFormat ss:Format="&quot;R$&quot;\\ #,##0.00"/></Style>',
            '</Styles>',
            '<Worksheet ss:Name="Lancamentos">',
            '<Table>',
            '<Row><Cell ss:MergeAcross="8" ss:StyleID="title"><Data ss:Type="String">Relatorio de lancamentos</Data></Cell></Row>',
            '<Row><Cell><Data ss:Type="String">Gerado em</Data></Cell><Cell><Data ss:Type="String">' . $this->escape(now()->format('d/m/Y H:i')) . '</Data></Cell></Row>',
            '<Row><Cell><Data ss:Type="String">Quantidade</Data></Cell><Cell><Data ss:Type="Number">' . $summary['quantidade'] . '</Data></Cell></Row>',
            '<Row><Cell><Data ss:Type="String">Receitas</Data></Cell><Cell ss:StyleID="currency"><Data ss:Type="Number">' . $summary['receitas'] . '</Data></Cell></Row>',
            '<Row><Cell><Data ss:Type="String">Despesas</Data></Cell><Cell ss:StyleID="currency"><Data ss:Type="Number">' . $summary['despesas'] . '</Data></Cell></Row>',
            '<Row><Cell><Data ss:Type="String">Saldo</Data></Cell><Cell ss:StyleID="currency"><Data ss:Type="Number">' . $summary['saldo'] . '</Data></Cell></Row>',
            '<Row/>',
            '<Row>',
        ];

        foreach ($this->headings() as $heading) {
            $xml[] = '<Cell ss:StyleID="header"><Data ss:Type="String">' . $this->escape($heading) . '</Data></Cell>';
        }

        $xml[] = '</Row>';

        foreach ($rows as $row) {
            $xml[] = '<Row>';
            $xml[] = '<Cell><Data ss:Type="String">' . $this->escape($row['descricao']) . '</Data></Cell>';
            $xml[] = '<Cell><Data ss:Type="String">' . $this->escape($row['tipo']) . '</Data></Cell>';
            $xml[] = '<Cell><Data ss:Type="String">' . $this->escape($row['conta']) . '</Data></Cell>';
            $xml[] = '<Cell><Data ss:Type="String">' . $this->escape($row['categoria']) . '</Data></Cell>';
            $xml[] = '<Cell><Data ss:Type="String">' . $this->escape($row['data_competencia']) . '</Data></Cell>';
            $xml[] = '<Cell><Data ss:Type="String">' . $this->escape($row['data_vencimento']) . '</Data></Cell>';
            $xml[] = '<Cell><Data ss:Type="String">' . $this->escape($row['data_pagamento']) . '</Data></Cell>';
            $xml[] = '<Cell><Data ss:Type="String">' . $this->escape($row['status']) . '</Data></Cell>';
            $xml[] = '<Cell ss:StyleID="currency"><Data ss:Type="Number">' . $row['valor'] . '</Data></Cell>';
            $xml[] = '</Row>';
        }

        $xml[] = '</Table>';
        $xml[] = '</Worksheet>';
        $xml[] = '</Workbook>';

        return implode(PHP_EOL, $xml);
    }

    private function headings(): array
    {
        return [
            'Descricao',
            'Tipo',
            'Conta',
            'Categoria',
            'Data competencia',
            'Data vencimento',
            'Data pagamento',
            'Status',
            'Valor',
        ];
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
