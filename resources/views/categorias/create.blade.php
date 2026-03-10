@extends('layouts.app')

@php($title = 'Nova categoria')

@section('content')
<div class="card card-soft">
    <div class="card-body">
        <form action="{{ route('categorias.store') }}" method="POST">
            @include('categorias._form')
        </form>
    </div>
</div>
@endsection
