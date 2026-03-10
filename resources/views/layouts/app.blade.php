<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Financeiro V3' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f4f7fb; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, #0d6efd 0%, #082b6f 100%); }
        .sidebar .nav-link { color: rgba(255,255,255,.88); border-radius: .75rem; margin-bottom: .25rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,.14); color: #fff; }
        .card-soft { border: 0; border-radius: 1rem; box-shadow: 0 12px 30px rgba(16, 24, 40, .06); }
        .metric { font-size: 1.55rem; font-weight: 700; }
        .topbar-search { max-width: 400px; }
        .table thead th { background: #eef3fb; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @auth
            <aside class="col-lg-2 sidebar text-white p-3">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-graph-up-arrow fs-3"></i>
                    <div>
                        <div class="fw-bold">Financeiro V3</div>
                        <small class="opacity-75">Laravel + Bootstrap</small>
                    </div>
                </div>

                <nav class="nav flex-column">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('lancamentos.index') }}" class="nav-link {{ request()->routeIs('lancamentos.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt me-2"></i>Lancamentos
                    </a>
                    <a href="{{ route('contas.index') }}" class="nav-link {{ request()->routeIs('contas.*') ? 'active' : '' }}">
                        <i class="bi bi-wallet2 me-2"></i>Contas
                    </a>
                    <a href="{{ route('categorias.index') }}" class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                        <i class="bi bi-tags me-2"></i>Categorias
                    </a>
                    <a href="{{ route('metas.index') }}" class="nav-link {{ request()->routeIs('metas.*') ? 'active' : '' }}">
                        <i class="bi bi-bullseye me-2"></i>Metas
                    </a>
                    <a href="{{ route('busca.index') }}" class="nav-link {{ request()->routeIs('busca.*') ? 'active' : '' }}">
                        <i class="bi bi-search me-2"></i>Busca global
                    </a>
                </nav>

                <div class="mt-4 pt-4 border-top border-light border-opacity-25">
                    <div class="small opacity-75 mb-2">Logado como</div>
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <div class="small opacity-75">{{ auth()->user()->email }}</div>
                    <form action="{{ route('logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button class="btn btn-light btn-sm w-100">Sair</button>
                    </form>
                </div>
            </aside>
        @endauth

        <main class="@auth col-lg-10 @else col-12 @endauth p-4">
            @auth
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div>
                        <h1 class="h3 mb-1">{{ $title ?? 'Financeiro V3' }}</h1>
                        <p class="text-secondary mb-0">Controle completo de entradas, saidas, metas e previsoes.</p>
                    </div>
                    <form action="{{ route('busca.index') }}" method="GET" class="topbar-search">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar lancamentos, contas ou categorias">
                        </div>
                    </form>
                </div>
            @endauth

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm">
                    <strong>Revise os campos:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
