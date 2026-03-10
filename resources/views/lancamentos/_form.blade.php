@csrf
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Conta</label>
        <select name="conta_id" class="form-select" required>
            @foreach($contas as $conta)
                <option value="{{ $conta->id }}" @selected(old('conta_id', $lancamento->conta_id ?? '') == $conta->id)>{{ $conta->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Categoria</label>
        <select name="categoria_id" class="form-select" required>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(old('categoria_id', $lancamento->categoria_id ?? '') == $categoria->id)>{{ $categoria->nome }} ({{ $categoria->tipo }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Tipo</label>
        <select name="tipo" class="form-select" required>
            <option value="pagar" @selected(old('tipo', $lancamento->tipo ?? '') === 'pagar')>Pagar</option>
            <option value="receber" @selected(old('tipo', $lancamento->tipo ?? '') === 'receber')>Receber</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Descricao</label>
        <input type="text" name="descricao" value="{{ old('descricao', $lancamento->descricao ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Valor</label>
        <input type="number" step="0.01" name="valor" value="{{ old('valor', $lancamento->valor ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-3" id="grupo-parcelas">
        <label class="form-label">Parcelas</label>
        <input type="number" min="1" max="48" name="parcelas" value="{{ old('parcelas', $lancamento->parcelas ?? 1) }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Data de competencia</label>
        <input type="date" name="data_competencia" value="{{ old('data_competencia', isset($lancamento) && $lancamento->data_competencia ? $lancamento->data_competencia->format('Y-m-d') : '') }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Data de vencimento</label>
        <input type="date" name="data_vencimento" value="{{ old('data_vencimento', isset($lancamento) && $lancamento->data_vencimento ? $lancamento->data_vencimento->format('Y-m-d') : '') }}" class="form-control" required>
    </div>
    <div class="col-md-4" id="grupo-frequencia">
        <label class="form-label">Frequencia</label>
        <select name="frequencia" class="form-select">
            <option value="">Nao recorrente</option>
            <option value="mensal" @selected(old('frequencia', $lancamento->frequencia ?? '') === 'mensal')>Mensal</option>
            <option value="bimestral" @selected(old('frequencia', $lancamento->frequencia ?? '') === 'bimestral')>Bimestral</option>
            <option value="trimestral" @selected(old('frequencia', $lancamento->frequencia ?? '') === 'trimestral')>Trimestral</option>
            <option value="anual" @selected(old('frequencia', $lancamento->frequencia ?? '') === 'anual')>Anual</option>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Observacoes</label>
        <textarea name="observacoes" class="form-control" rows="3">{{ old('observacoes', $lancamento->observacoes ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <div class="form-check">
            <input type="hidden" name="recorrente" value="0">
            <input type="checkbox" class="form-check-input" name="recorrente" value="1" id="recorrente" @checked(old('recorrente', $lancamento->recorrente ?? false))>
            <label class="form-check-label" for="recorrente">Gerar parcelas futuras automaticamente</label>
        </div>
    </div>
</div>
<div class="mt-4">
    <button class="btn btn-primary">Salvar</button>
    <a href="{{ route('lancamentos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>


@push('scripts')
<script>
(() => {
  const checkbox = document.getElementById('recorrente');
  const grupoParcelas = document.getElementById('grupo-parcelas');
  const grupoFrequencia = document.getElementById('grupo-frequencia');

  if (!checkbox || !grupoParcelas || !grupoFrequencia) {
    return;
  }

  const atualizar = () => {
    const ativa = checkbox.checked;
    grupoParcelas.style.display = ativa ? "block" : "none";
    grupoFrequencia.style.display = ativa ? "block" : "none";
  };

  checkbox.addEventListener('change', atualizar);
  atualizar();
})();
</script>
@endpush
