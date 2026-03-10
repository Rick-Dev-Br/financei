@extends('layouts.app')

@php($title = 'Editar categoria')

@section('content')
<div class="card card-soft">
    <div class="card-body">
        <form action="{{ route('categorias.update', $categoria) }}" method="POST">
            @method('PUT')
            @include('categorias._form')
        </form>
    </div>
</div>
@endsection
