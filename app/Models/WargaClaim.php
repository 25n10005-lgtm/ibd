<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['user_id', 'warga_id', 'status'])]
class WargaClaim extends Model
{
    public const STATUS_DIAJUKAN = 'diajukan';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS_DIBATALKAN = 'dibatalkan';

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Warga, $this> */
    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class);
    }

    /** @return HasMany<WargaClaimCheck> */
    public function checks(): HasMany
    {
        return $this->hasMany(WargaClaimCheck::class, 'claim_id');
    }

    /** @return HasOne<WargaClaimDecision> */
    public function decision(): HasOne
    {
        return $this->hasOne(WargaClaimDecision::class, 'claim_id');
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_DIAJUKAN;
    }
}
