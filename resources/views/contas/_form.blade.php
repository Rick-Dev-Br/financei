@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" value="{{ old('nome', $conta->nome ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Tipo</label>
        <select name="tipo" class="form-select" required>
            @foreach(['conta' => 'Conta', 'cash' => 'Caixa', 'cartao' => 'Cartao', 'poupanca' => 'Poupanca'] as $valor => $texto)
                <option value="{{ $valor }}" @selected(old('tipo', $conta->tipo ?? 'conta') === $valor)>{{ $texto }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Saldo inicial</label>
        <input type="number" step="0.01" name="saldo_inicial" value="{{ old('saldo_inicial', $conta->saldo_inicial ?? 0) }}" class="form-control" {{ isset($conta) ? 'disabled' : '' }} required>
        @isset($conta)<small class="text-secondary">O saldo inicial fica bloqueado na edicao para evitar distorcoes historicas.</small>@endisset
    </div>
    <div class="col-md-6">
        <label class="form-label">Cor</label>
        <input type="text" name="cor" value="{{ old('cor', $conta->cor ?? '#0d6efd') }}" class="form-control" placeholder="#0d6efd">
    </div>
    <div class="col-md-6">
        <label class="form-label">Icone Bootstrap</label>
        <input type="text" name="icone" value="{{ old('icone', $conta->icone ?? 'bi-wallet2') }}" class="form-control" placeholder="bi-wallet2">
    </div>
    @isset($conta)
        <div class="col-12">
            <div class="form-check">
                <input type="hidden" name="ativa" value="0">
                <input type="checkbox" class="form-check-input" name="ativa" value="1" id="ativa" @checked(old('ativa', $conta->ativa))>
                <label class="form-check-label" for="ativa">Conta ativa</label>
            </div>
        </div>
    @endisset
</div>
<div class="mt-4">
    <button class="btn btn-primary">Salvar</button>
    <a href="{{ route('contas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
