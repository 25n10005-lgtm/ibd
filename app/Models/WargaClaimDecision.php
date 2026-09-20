<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['claim_id', 'decided_by', 'decision', 'reason', 'decided_at'])]
class WargaClaimDecision extends Model
{
    public $incrementing = false;

    protected $primaryKey = 'claim_id';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'decided_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<WargaClaim, $this> */
    public function claim(): BelongsTo
    {
        return $this->belongsTo(WargaClaim::class, 'claim_id');
    }

    /** @return BelongsTo<User, $this> */
    public function decider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }
}
