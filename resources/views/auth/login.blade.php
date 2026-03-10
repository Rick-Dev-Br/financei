@extends('layouts.app')

@section('content')
<div class="row justify-content-center min-vh-100 align-items-center">
    <div class="col-md-5 col-xl-4">
        <div class="card card-soft">
            <div class="card-body p-4 p-lg-5">
                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                        <i class="bi bi-shield-lock fs-2"></i>
                    </div>
                    <h2 class="h3 mb-1">Entrar no sistema</h2>
                    <p class="text-secondary mb-0">Use seu e-mail e senha para acessar.</p>
                </div>
                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" value="{{ old('email', 'demo@financeiro.test') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <input type="password" name="password" value="12345678" class="form-control" required>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Lembrar de mim</label>
                    </div>
                    <button class="btn btn-primary w-100">Entrar</button>
                </form>
                <div class="text-center mt-4">
                    <span class="text-secondary">Ainda nao tem conta?</span>
                    <a href="{{ route('register') }}" class="text-decoration-none">Criar conta</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
