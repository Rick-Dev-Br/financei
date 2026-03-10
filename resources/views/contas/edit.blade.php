@extends('layouts.app')

@php($title = 'Editar conta')

@section('content')
<div class="card card-soft">
    <div class="card-body">
        <form action="{{ route('contas.update', $conta) }}" method="POST">
            @method('PUT')
            @include('contas._form')
        </form>
    </div>
</div>
@endsection
