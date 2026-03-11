<?php

namespace App\Http\Controllers;

use App\Exports\LancamentosExport;
use App\Models\Lancamento;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RelatorioController extends Controller
{
    public function lancamentosPdf(Request $request): View
    {
        $export = $this->makeExport($request);

        return view('relatorios.lancamentos_pdf', [
            'rows' => $export->rows(),
            'resumo' => $export->summary(),
            'filtros' => $this->activeFilters($request),
            'geradoEm' => now(),
            'autoPrint' => ! $request->boolean('preview'),
        ]);
    }

    public function lancamentosExcel(Request $request): StreamedResponse
    {
        $export = $this->makeExport($request);

        return response()->streamDownload(
            static function () use ($export): void {
                echo $export->toSpreadsheetXml();
            },
            'lancamentos-' . now()->format('Y-m-d-His') . '.xls',
            [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
                'Cache-Control' => 'max-age=0, no-cache, no-store, must-revalidate',
                'Pragma' => 'public',
            ]
        );
    }

    private function makeExport(Request $request): LancamentosExport
    {
        return new LancamentosExport($this->filteredQuery($request)->get());
    }

    private function filteredQuery(Request $request): Builder
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

        return $query->orderBy('data_vencimento');
    }

    private function activeFilters(Request $request): array
    {
        $mes = $request->string('mes')->toString();

        return array_filter([
            'Mes' => $mes !== '' ? Carbon::createFromFormat('Y-m', $mes)->format('m/Y') : null,
            'Tipo' => $request->filled('tipo') ? ucfirst($request->string('tipo')->toString()) : null,
            'Status' => $request->filled('status') ? ucfirst($request->string('status')->toString()) : null,
            'Busca' => $request->filled('q') ? $request->string('q')->toString() : null,
        ]);
    }
}
