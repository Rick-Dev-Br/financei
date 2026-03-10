@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Titulo</label>
        <input type="text" name="titulo" value="{{ old('titulo', $meta->titulo ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Valor da meta</label>
        <input type="number" step="0.01" name="valor_meta" value="{{ old('valor_meta', $meta->valor_meta ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Valor atual</label>
        <input type="number" step="0.01" name="valor_atual" value="{{ old('valor_atual', $meta->valor_atual ?? 0) }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Data limite</label>
        <input type="date" name="data_limite" value="{{ old('data_limite', isset($meta) && $meta->data_limite ? $meta->data_limite->format('Y-m-d') : '') }}" class="form-control" required>
    </div>
    <div class="col-12">
        <label class="form-label">Descricao</label>
        <textarea name="descricao" rows="3" class="form-control">{{ old('descricao', $meta->descricao ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4">
    <button class="btn btn-primary">Salvar</button>
    <a href="{{ route('metas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
