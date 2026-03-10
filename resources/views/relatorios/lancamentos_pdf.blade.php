<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
        h1 { font-size: 22px; margin-bottom: 4px; }
        p { margin-top: 0; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; }
        th { background: #eef2ff; text-align: left; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h1>Relatorio de lancamentos</h1>
    <p>Sistema Financeiro V3 - exportacao PDF</p>
    <table>
        <thead>
            <tr>
                <th>Descricao</th>
                <th>Tipo</th>
                <th>Categoria</th>
                <th>Conta</th>
                <th>Vencimento</th>
                <th>Status</th>
                <th class="right">Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lancamentos as $item)
                <tr>
                    <td>{{ $item->descricao }}</td>
                    <td>{{ ucfirst($item->tipo) }}</td>
                    <td>{{ $item->categoria?->nome }}</td>
                    <td>{{ $item->conta?->nome }}</td>
                    <td>{{ optional($item->data_vencimento)->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td class="right">R$ {{ number_format($item->valor, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
