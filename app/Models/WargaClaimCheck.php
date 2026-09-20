<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['claim_id', 'check_type', 'passed', 'detail'])]
class WargaClaimCheck extends Model
{
    public const NIK_FOUND = 'nik_found';

    public const NOT_LINKED = 'not_linked';

    public const SAME_RT = 'same_rt';

    public const BASIC_MATCH = 'basic_match';

    protected function casts(): array
    {
        return [
            'passed' => 'boolean',
        ];
    }

    /** @return BelongsTo<WargaClaim, $this> */
    public function claim(): BelongsTo
    {
        return $this->belongsTo(WargaClaim::class, 'claim_id');
    }
}
