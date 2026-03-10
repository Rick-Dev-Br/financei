@extends('layouts.app')

@php($title = 'Editar meta')

@section('content')
<div class="card card-soft">
    <div class="card-body">
        <form action="{{ route('metas.update', $meta) }}" method="POST">
            @method('PUT')
            @include('metas._form')
        </form>
    </div>
</div>
@endsection
