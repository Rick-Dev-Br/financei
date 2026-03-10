<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\PrevisaoFinanceiraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService,
        private PrevisaoFinanceiraService $previsaoFinanceiraService
    ) {
    }

    public function index(Request $request): View
    {
        $dados = $this->dashboardService->montar(
            (int) Auth::id(),
            $request->string('mes')->toString() ?: null
        );

        $previsao = $this->previsaoFinanceiraService->gerar(
            (int) Auth::id(),
            (int) $request->integer('horizonte', 6)
        );

        return view('dashboard.index', [
            ...$dados,
            'previsao' => $previsao,
            'horizonte' => (int) $request->integer('horizonte', 6),
        ]);
    }
}
