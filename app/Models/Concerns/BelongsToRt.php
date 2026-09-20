<?php

namespace App\Models\Concerns;

use App\Models\Rt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToRt
{
    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class);
    }

    /**
     * Batasi query ke satu RT (multi-tenant). Null = tanpa batas (Developer).
     */
    public function scopeForRt(Builder $query, ?int $rtId): Builder
    {
        return $rtId ? $query->where($query->getModel()->getTable().'.rt_id', $rtId) : $query;
    }
}
