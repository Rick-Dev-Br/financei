@extends('layouts.app')

@php($title = 'Novo lancamento')

@section('content')
<div class="card card-soft">
    <div class="card-body">
        @include('lancamentos._form')
    </div>
</div>
@endsection
