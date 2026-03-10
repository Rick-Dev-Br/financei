@extends('layouts.app')

@php($title = 'Nova conta')

@section('content')
<div class="card card-soft">
    <div class="card-body">
        @include('contas._form')
    </div>
</div>
@endsection
