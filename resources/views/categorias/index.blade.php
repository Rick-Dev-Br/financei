@extends('layouts.app')

@php($title = 'Categorias')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <p class="text-secondary mb-0">Use icones Bootstrap para deixar o painel mais visual.</p>
    <a href="{{ route('categorias.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nova categoria</a>
</div>
<div class="card card-soft">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Icone</th>
                        <th>Status</th>
                        <th class="text-end">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                        <tr>
                            <td class="fw-semibold">{{ $categoria->nome }}</td>
                            <td><span class="badge {{ $categoria->tipo === 'receber' ? 'text-bg-success' : 'text-bg-danger' }}">{{ ucfirst($categoria->tipo) }}</span></td>
                            <td><i class="bi {{ $categoria->icone ?: 'bi-tag' }}"></i> {{ $categoria->icone }}</td>
                            <td>{{ $categoria->ativa ? 'Ativa' : 'Inativa' }}</td>
                            <td class="text-end">
                                <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                                <form class="d-inline" action="{{ route('categorias.destroy', $categoria) }}" method="POST" onsubmit="return confirm('Deseja excluir esta categoria?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary">Nenhuma categoria cadastrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
