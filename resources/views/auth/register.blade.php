@extends('layouts.app')

@section('content')
<div class="row justify-content-center min-vh-100 align-items-center">
    <div class="col-md-6 col-xl-5">
        <div class="card card-soft">
            <div class="card-body p-4 p-lg-5">
                <div class="text-center mb-4">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                        <i class="bi bi-person-plus fs-2"></i>
                    </div>
                    <h2 class="h3 mb-1">Criar conta</h2>
                    <p class="text-secondary mb-0">Cadastre um usuario e comece a usar o sistema.</p>
                </div>
                <form method="POST" action="{{ route('register.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Senha</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmacao da senha</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <button class="btn btn-success w-100 mt-4">Cadastrar</button>
                </form>
                <div class="text-center mt-4">
                    <span class="text-secondary">Ja possui conta?</span>
                    <a href="{{ route('login') }}" class="text-decoration-none">Entrar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
