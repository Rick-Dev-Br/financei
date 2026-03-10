@extends('layouts.app')

@php($title = 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card card-soft">
            <div class="card-body">
                <div class="text-secondary small mb-2">Saldo total</div>
                <div class="metric text-primary">R$ {{ number_format($saldoTotal, 2, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-soft">
            <div class="card-body">
                <div class="text-secondary small mb-2">A receber no mes</div>
                <div class="metric text-success">R$ {{ number_format($receberMes, 2, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-soft">
            <div class="card-body">
                <div class="text-secondary small mb-2">A pagar no mes</div>
                <div class="metric text-danger">R$ {{ number_format($pagarMes, 2, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-soft">
            <div class="card-body">
                <div class="text-secondary small mb-2">Alertas vencidos</div>
                <div class="metric text-warning">{{ $vencidos }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card card-soft mb-4">
    <div class="card-body">
        <form class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Mes de analise</label>
                <input type="month" name="mes" value="{{ $mes }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Horizonte da previsao</label>
                <select name="horizonte" class="form-select">
                    @foreach([3,6,9,12] as $op)
                        <option value="{{ $op }}" @selected($horizonte === $op)>{{ $op }} meses</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary">Aplicar filtros</button>
            </div>
            <div class="col-md-3 text-md-end">
                <a href="{{ route('relatorios.lancamentos.pdf') }}" class="btn btn-outline-danger"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
                <a href="{{ route('relatorios.lancamentos.excel') }}" class="btn btn-outline-success"><i class="bi bi-file-earmark-excel"></i> Excel</a>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card card-soft h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Fluxo financeiro dos ultimos meses</h2>
                    <span class="badge text-bg-light">Graficos melhores com Chart.js</span>
                </div>
                <canvas id="graficoFluxo" height="110"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card card-soft h-100">
            <div class="card-body">
                <h2 class="h5 mb-3">Metas financeiras</h2>
                @forelse($metas as $meta)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $meta->titulo }}</strong>
                            <span>{{ $meta->percentual }}%</span>
                        </div>
                        <div class="progress my-2" role="progressbar">
                            <div class="progress-bar" style="width: {{ $meta->percentual }}%"></div>
                        </div>
                        <small class="text-secondary">R$ {{ number_format($meta->valor_atual, 2, ',', '.') }} de R$ {{ number_format($meta->valor_meta, 2, ',', '.') }}</small>
                    </div>
                @empty
                    <p class="text-secondary mb-0">Nenhuma meta cadastrada.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="card card-soft">
            <div class="card-body">
                <h2 class="h5 mb-3">Lancamentos recentes</h2>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Descricao</th>
                            <th>Tipo</th>
                            <th>Vencimento</th>
                            <th class="text-end">Valor</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($lancamentos->take(8) as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $item->descricao }}</div>
                                    <small class="text-secondary">{{ $item->categoria?->nome }} • {{ $item->conta?->nome }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $item->tipo === 'receber' ? 'text-bg-success' : 'text-bg-danger' }}">
                                        {{ ucfirst($item->tipo) }}
                                    </span>
                                </td>
                                <td>{{ optional($item->data_vencimento)->format('d/m/Y') }}</td>
                                <td class="text-end">R$ {{ number_format($item->valor, 2, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $item->status === 'pago' ? 'text-bg-primary' : ($item->eh_vencido ? 'text-bg-warning' : 'text-bg-secondary') }}">
                                        {{ $item->eh_vencido ? 'Vencido' : ucfirst($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-secondary">Nenhum lancamento encontrado.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('lancamentos.index') }}" class="btn btn-outline-primary">Ver todos</a>
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="card card-soft">
            <div class="card-body">
                <h2 class="h5 mb-3">Previsao dos proximos meses</h2>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Mes</th>
                            <th class="text-end">Saldo do mes</th>
                            <th class="text-end">Acumulado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($previsao as $linha)
                            <tr>
                                <td>{{ $linha['mes'] }}</td>
                                <td class="text-end {{ $linha['saldo_mes'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    R$ {{ number_format($linha['saldo_mes'], 2, ',', '.') }}
                                </td>
                                <td class="text-end {{ $linha['saldo_acumulado'] >= 0 ? 'text-primary' : 'text-danger' }}">
                                    R$ {{ number_format($linha['saldo_acumulado'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <small class="text-secondary">Use esta previsao para saber quando vai sobrar ou faltar dinheiro.</small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const grafico = @json($grafico);
const ctx = document.getElementById('graficoFluxo');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: grafico.map(item => item.mes),
        datasets: [
            {
                label: 'Receitas',
                data: grafico.map(item => item.receitas),
                backgroundColor: 'rgba(25, 135, 84, .70)',
                borderRadius: 8
            },
            {
                label: 'Despesas',
                data: grafico.map(item => item.despesas),
                backgroundColor: 'rgba(220, 53, 69, .70)',
                borderRadius: 8
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endpush
