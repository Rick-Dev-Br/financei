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
        <div class="input-group">
            <span class="input-group-text" id="icone_preview"><i class="bi {{ old('icone', $categoria->icone ?? 'bi-tag') ?: 'bi-tag' }}"></i></span>
            <input type="text" name="icone" id="icone_input" value="{{ old('icone', $categoria->icone ?? 'bi-tag') }}" class="form-control" placeholder="bi-tag">
        </div>
    </div>
    <div class="col-md-2">
        <label class="form-label">Cor</label>
        @php($corPadrao = old('cor', $categoria->cor ?? '#0d6efd'))
        <div class="input-group">
            <input type="color" id="cor_picker" value="{{ str_starts_with($corPadrao, '#') ? $corPadrao : '#'.$corPadrao }}" class="form-control form-control-color" title="Escolha uma cor">
            <input type="text" name="cor" id="cor_hex" value="{{ str_starts_with($corPadrao, '#') ? $corPadrao : '#'.$corPadrao }}" class="form-control" placeholder="#0d6efd">
        </div>
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

@push('scripts')
<script>
(() => {
    const iconeInput = document.getElementById('icone_input');
    const iconePreview = document.querySelector('#icone_preview i');
    const corPicker = document.getElementById('cor_picker');
    const corHex = document.getElementById('cor_hex');

    if (iconeInput && iconePreview) {
        const atualizarIcone = () => {
            const valor = (iconeInput.value || 'bi-tag').trim();
            iconePreview.className = `bi ${valor}`;
        };

        iconeInput.addEventListener('input', atualizarIcone);
        atualizarIcone();
    }

    if (corPicker && corHex) {
        const normalizarCor = (valor) => {
            const v = (valor || '').trim();
            if (!v) return '#0d6efd';
            return v.startsWith('#') ? v : `#${v}`;
        };

        corPicker.addEventListener('input', () => {
            corHex.value = corPicker.value;
        });

        corHex.addEventListener('blur', () => {
            const cor = normalizarCor(corHex.value);
            corHex.value = cor;
            if (/^#[0-9a-fA-F]{6}$/.test(cor)) {
                corPicker.value = cor;
            }
        });

        corHex.value = normalizarCor(corHex.value);
    }
})();
</script>
@endpush
