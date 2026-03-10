@extends('layouts.app')

@php($title = 'Busca global')

@section('content')
<div class="card card-soft mb-4">
    <div class="card-body">
        <form class="row g-3 align-items-end">
            <div class="col-md-10">
                <label class="form-label">Pesquisar</label>
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Digite descricao, nome de conta ou categoria">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Buscar</button>
            </div>
        </form>
    </div>
</div>

@if($q !== '')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card card-soft">
            <div class="card-body">
                <h2 class="h5">Lancamentos</h2>
                <ul class="list-group list-group-flush">
                    @forelse($lancamentos as $item)
                        <li class="list-group-item px-0">
                            <div class="fw-semibold">{{ $item->descricao }}</div>
                            <small class="text-secondary">{{ ucfirst($item->tipo) }} • R$ {{ number_format($item->valor, 2, ',', '.') }}</small>
                        </li>
                    @empty
                        <li class="list-group-item px-0 text-secondary">Nenhum lancamento encontrado.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card card-soft">
            <div class="card-body">
                <h2 class="h5">Contas</h2>
                <ul class="list-group list-group-flush">
                    @forelse($contas as $item)
                        <li class="list-group-item px-0">{{ $item->nome }}</li>
                    @empty
                        <li class="list-group-item px-0 text-secondary">Nenhuma conta encontrada.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card card-soft">
            <div class="card-body">
                <h2 class="h5">Categorias</h2>
                <ul class="list-group list-group-flush">
                    @forelse($categorias as $item)
                        <li class="list-group-item px-0">{{ $item->nome }}</li>
                    @empty
                        <li class="list-group-item px-0 text-secondary">Nenhuma categoria encontrada.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
