<?php

namespace App\Http\Controllers;

use App\Exports\LancamentosExport;
use App\Models\Lancamento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RelatorioController extends Controller
{
    public function lancamentosPdf(Request $request)
    {
        $lancamentos = Lancamento::query()
            ->with(['conta', 'categoria'])
            ->where('user_id', auth()->id())
            ->orderBy('data_vencimento')
            ->get();

        $pdf = Pdf::loadView('relatorios.lancamentos_pdf', compact('lancamentos'));

        return $pdf->download('lancamentos.pdf');
    }

    public function lancamentosExcel(Request $request): BinaryFileResponse
    {
        return Excel::download(new LancamentosExport(auth()->id()), 'lancamentos.xlsx');
    }
}
