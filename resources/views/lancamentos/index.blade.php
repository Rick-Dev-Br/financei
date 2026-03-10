@extends('layouts.app')

@php($title = 'Lancamentos')

@section('content')
<div class="card card-soft mb-4">
    <div class="card-body">
        <form class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Mes</label>
                <input type="month" name="mes" value="{{ request('mes') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="">Todos</option>
                    <option value="pagar" @selected(request('tipo') === 'pagar')>Pagar</option>
                    <option value="receber" @selected(request('tipo') === 'receber')>Receber</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="pendente" @selected(request('status') === 'pendente')>Pendente</option>
                    <option value="pago" @selected(request('status') === 'pago')>Pago</option>
                    <option value="cancelado" @selected(request('status') === 'cancelado')>Cancelado</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Busca</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Descricao">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filtrar</button>
                <a href="{{ route('lancamentos.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card card-soft">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Descricao</th>
                        <th>Conta</th>
                        <th>Categoria</th>
                        <th>Vencimento</th>
                        <th>Status</th>
                        <th class="text-end">Valor</th>
                        <th class="text-end">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lancamentos as $item)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $item->descricao }}</div>
                                <small class="text-secondary">{{ ucfirst($item->tipo) }} • Parcela {{ $item->parcela_atual }}/{{ $item->parcelas }}</small>
                            </td>
                            <td>{{ $item->conta?->nome }}</td>
                            <td>{{ $item->categoria?->nome }}</td>
                            <td>{{ optional($item->data_vencimento)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $item->status === 'pago' ? 'text-bg-primary' : ($item->eh_vencido ? 'text-bg-warning' : 'text-bg-secondary') }}">
                                    {{ $item->eh_vencido ? 'Vencido' : ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="text-end">R$ {{ number_format($item->valor, 2, ',', '.') }}</td>
                            <td class="text-end">
                                @if($item->status !== 'pago')
                                    <form class="d-inline" action="{{ route('lancamentos.baixar', $item) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-outline-success btn-sm">Baixar</button>
                                    </form>
                                @endif
                                <a href="{{ route('lancamentos.edit', $item) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                                <form class="d-inline" action="{{ route('lancamentos.destroy', $item) }}" method="POST" onsubmit="return confirm('Deseja excluir este lancamento?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-secondary">Nenhum lancamento encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $lancamentos->links() }}
    </div>
</div>
@endsection
