@csrf
<div class="row g-3">
    <div class="col-md-5">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" value="{{ old('nome', $categoria->nome ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Tipo</label>
        <select name="tipo" class="form-select" required>
            <option value="pagar" @selected(old('tipo', $categoria->tipo ?? '') === 'pagar')>Pagar</option>
            <option value="receber" @selected(old('tipo', $categoria->tipo ?? '') === 'receber')>Receber</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Icone</label>
        <input type="text" name="icone" value="{{ old('icone', $categoria->icone ?? 'bi-tag') }}" class="form-control" placeholder="bi-tag">
    </div>
    <div class="col-md-2">
        <label class="form-label">Cor</label>
        <input type="text" name="cor" value="{{ old('cor', $categoria->cor ?? '#0d6efd') }}" class="form-control">
    </div>
    @isset($categoria)
        <div class="col-12">
            <div class="form-check">
                <input type="hidden" name="ativa" value="0">
                <input type="checkbox" class="form-check-input" name="ativa" value="1" id="cat_ativa" @checked(old('ativa', $categoria->ativa))>
                <label class="form-check-label" for="cat_ativa">Categoria ativa</label>
            </div>
        </div>
    @endisset
</div>
<div class="mt-4">
    <button class="btn btn-primary">Salvar</button>
    <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
