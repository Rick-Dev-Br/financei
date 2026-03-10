@extends('layouts.app')

@php($title = 'Contas bancarias e caixas')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <p class="text-secondary mb-0">Cadastre as contas usadas pelo sistema para atualizar o saldo automaticamente.</p>
    <a href="{{ route('contas.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nova conta</a>
</div>
<div class="row g-4">
    @forelse($contas as $conta)
        <div class="col-md-6 col-xl-4">
            <div class="card card-soft h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <div class="text-secondary small">{{ ucfirst($conta->tipo) }}</div>
                            <h2 class="h5 mb-0">{{ $conta->nome }}</h2>
                        </div>
                        <i class="bi {{ $conta->icone ?: 'bi-wallet2' }} fs-3 text-primary"></i>
                    </div>
                    <div class="metric text-primary mb-3">R$ {{ number_format($conta->saldo_atual, 2, ',', '.') }}</div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('contas.edit', $conta) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                        <form action="{{ route('contas.destroy', $conta) }}" method="POST" onsubmit="return confirm('Deseja remover esta conta?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-light border">Nenhuma conta cadastrada.</div></div>
    @endforelse
</div>
@endsection
