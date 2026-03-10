@extends('layouts.app')

@php($title = 'Editar lancamento')

@section('content')
<div class="card card-soft">
    <div class="card-body">
        <form action="{{ route('lancamentos.update', $lancamento) }}" method="POST">
            @method('PUT')
            @include('lancamentos._form')
        </form>
    </div>
</div>
@endsection
