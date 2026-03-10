<?php

namespace App\Exports;

use App\Models\Lancamento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LancamentosExport implements FromCollection, WithHeadings
{
    public function __construct(private int $userId)
    {
    }

    public function collection()
    {
        return Lancamento::query()
            ->where('user_id', $this->userId)
            ->orderBy('data_vencimento')
            ->get([
                'descricao',
                'tipo',
                'valor',
                'status',
                'data_competencia',
                'data_vencimento',
                'data_pagamento',
            ]);
    }

    public function headings(): array
    {
        return [
            'Descricao',
            'Tipo',
            'Valor',
            'Status',
            'Data competencia',
            'Data vencimento',
            'Data pagamento',
        ];
    }
}
