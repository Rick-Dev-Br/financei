@extends('layouts.app')

@php($title = 'Metas financeiras')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <p class="text-secondary mb-0">Metas ajudam a acompanhar reserva, compras e objetivos financeiros.</p>
    <a href="{{ route('metas.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nova meta</a>
</div>

<div class="row g-4">
    @forelse($metas as $meta)
        <div class="col-md-6 col-xl-4">
            <div class="card card-soft h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h2 class="h5 mb-1">{{ $meta->titulo }}</h2>
                        <span class="badge text-bg-light">{{ $meta->percentual }}%</span>
                    </div>
                    <p class="text-secondary small">{{ $meta->descricao }}</p>
                    <div class="progress my-3" role="progressbar">
                        <div class="progress-bar" style="width: {{ $meta->percentual }}%"></div>
                    </div>
                    <div class="small text-secondary mb-3">
                        R$ {{ number_format($meta->valor_atual, 2, ',', '.') }} de
                        R$ {{ number_format($meta->valor_meta, 2, ',', '.') }}
                    </div>
                    <div class="small mb-3">Prazo: {{ optional($meta->data_limite)->format('d/m/Y') }}</div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('metas.edit', $meta) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                        <form action="{{ route('metas.destroy', $meta) }}" method="POST" onsubmit="return confirm('Deseja excluir esta meta?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-light border">Nenhuma meta cadastrada.</div></div>
    @endforelse
</div>
@endsection
