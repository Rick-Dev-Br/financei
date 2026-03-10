<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lancamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'conta_id',
        'categoria_id',
        'tipo',
        'descricao',
        'valor',
        'data_competencia',
        'data_vencimento',
        'data_pagamento',
        'status',
        'observacoes',
        'recorrente',
        'frequencia',
        'parcelas',
        'parcela_atual',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_competencia' => 'date',
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
        'recorrente' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conta(): BelongsTo
    {
        return $this->belongsTo(Conta::class);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function scopeDoMes($query, ?string $mes)
    {
        if (!$mes) {
            return $query;
        }

        return $query->whereYear('data_vencimento', substr($mes, 0, 4))
            ->whereMonth('data_vencimento', substr($mes, 5, 2));
    }

    public function getEhVencidoAttribute(): bool
    {
        return $this->status !== 'pago' && optional($this->data_vencimento)->isPast();
    }
}
