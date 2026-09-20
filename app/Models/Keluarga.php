<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['rt_id', 'no_kk', 'kepala_keluarga', 'alamat'])]
class Keluarga extends Model
{
    use Concerns\BelongsToRt;
    /** @return HasMany<Warga> */
    public function wargas(): HasMany
    {
        return $this->hasMany(Warga::class);
    }

    public function getLengkapAttribute(): bool
    {
        return $this->wargas()->exists();
    }
}
