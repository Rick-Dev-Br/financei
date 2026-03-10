@extends('layouts.app')

@php($title = 'Nova meta')

@section('content')
<div class="card card-soft">
    <div class="card-body">
        @include('metas._form')
    </div>
</div>
@endsection
