<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatorio de lancamentos</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f3f6fb;
            --card: #ffffff;
            --line: #d7e0ea;
            --text: #172033;
            --muted: #5b667d;
            --primary: #1d4ed8;
            --success: #0f766e;
            --danger: #b91c1c;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
        }

        .toolbar {
            position: sticky;
            top: 0;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 24px;
            background: rgba(23, 32, 51, 0.92);
        }

        .toolbar-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .toolbar a,
        .toolbar button {
            border: 0;
            border-radius: 999px;
            padding: 10px 18px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }

        .toolbar button {
            background: #fff;
            color: #111827;
        }

        .toolbar a {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.28);
        }

        .page {
            max-width: 1120px;
            margin: 24px auto;
            padding: 32px;
            background: var(--card);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
        }

        .header {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            align-items: flex-start;
            margin-bottom: 24px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .header p {
            margin: 0;
            color: var(--muted);
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px 0 28px;
        }

        .chip {
            border-radius: 999px;
            background: #e9eefc;
            color: var(--primary);
            padding: 8px 14px;
            font-size: 13px;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 28px;
        }

        .card {
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 18px;
            background: #fbfdff;
        }

        .card span {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
        }

        .card strong {
            font-size: 24px;
        }

        .card.success strong { color: var(--success); }
        .card.danger strong { color: var(--danger); }
        .card.primary strong { color: var(--primary); }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 14px;
            border: 1px solid var(--line);
            text-align: left;
            font-size: 14px;
        }

        thead th {
            background: #edf3ff;
        }

        .right { text-align: right; }
        .empty {
            text-align: center;
            color: var(--muted);
            padding: 28px;
        }

        @media (max-width: 900px) {
            .summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .header {
                flex-direction: column;
            }
        }

        @media print {
            body {
                background: #fff;
            }

            .toolbar {
                display: none;
            }

            .page {
                margin: 0;
                max-width: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <div class="toolbar-actions">
            <button type="button" onclick="window.print()">Imprimir / Salvar PDF</button>
            <a href="{{ route('lancamentos.index', request()->only(['mes', 'tipo', 'status', 'q'])) }}">Voltar para lancamentos</a>
        </div>
        <a href="{{ route('relatorios.lancamentos.excel', request()->query()) }}">Baixar Excel</a>
    </div>

    <main class="page">
        <section class="header">
            <div>
                <h1>Relatorio de lancamentos</h1>
                <p>Gerado em {{ $geradoEm->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p>Total de registros: {{ $resumo['quantidade'] }}</p>
            </div>
        </section>

        @if($filtros !== [])
            <section class="filters">
                @foreach($filtros as $label => $value)
                    <span class="chip">{{ $label }}: {{ $value }}</span>
                @endforeach
            </section>
        @endif

        <section class="summary">
            <article class="card primary">
                <span>Quantidade</span>
                <strong>{{ $resumo['quantidade'] }}</strong>
            </article>
            <article class="card success">
                <span>Receitas</span>
                <strong>R$ {{ number_format($resumo['receitas'], 2, ',', '.') }}</strong>
            </article>
            <article class="card danger">
                <span>Despesas</span>
                <strong>R$ {{ number_format($resumo['despesas'], 2, ',', '.') }}</strong>
            </article>
            <article class="card primary">
                <span>Saldo</span>
                <strong>R$ {{ number_format($resumo['saldo'], 2, ',', '.') }}</strong>
            </article>
        </section>

        <table>
            <thead>
                <tr>
                    <th>Descricao</th>
                    <th>Tipo</th>
                    <th>Conta</th>
                    <th>Categoria</th>
                    <th>Competencia</th>
                    <th>Vencimento</th>
                    <th>Pagamento</th>
                    <th>Status</th>
                    <th class="right">Valor</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    <tr>
                        <td>{{ $row['descricao'] }}</td>
                        <td>{{ $row['tipo'] }}</td>
                        <td>{{ $row['conta'] }}</td>
                        <td>{{ $row['categoria'] }}</td>
                        <td>{{ $row['data_competencia'] }}</td>
                        <td>{{ $row['data_vencimento'] }}</td>
                        <td>{{ $row['data_pagamento'] }}</td>
                        <td>{{ $row['status'] }}</td>
                        <td class="right">R$ {{ number_format($row['valor'], 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="empty">Nenhum lancamento encontrado para os filtros informados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>

    @if($autoPrint)
        <script>
            window.addEventListener('load', () => {
                window.print();
            }, { once: true });
        </script>
    @endif
</body>
</html>
