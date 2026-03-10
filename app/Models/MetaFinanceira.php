<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MetaFinanceira extends Model
{
    use HasFactory;

    protected $table = 'metas_financeiras';

    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'valor_meta',
        'valor_atual',
        'data_limite',
        'status',
    ];

    protected $casts = [
        'valor_meta' => 'decimal:2',
        'valor_atual' => 'decimal:2',
        'data_limite' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPercentualAttribute(): float
    {
        if ((float) $this->valor_meta <= 0) {
            return 0;
        }

        return min(100, round(($this->valor_atual / $this->valor_meta) * 100, 2));
    }
}
