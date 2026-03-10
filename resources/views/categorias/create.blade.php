@extends('layouts.app')

@php($title = 'Nova categoria')

@section('content')
<div class="card card-soft">
    <div class="card-body">
        @include('categorias._form')
    </div>
</div>
@endsection
