<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['rt_id', 'warga_id', 'nama_pembayar', 'periode', 'jumlah', 'tanggal_bayar', 'status'])]
class IuranPembayaran extends Model
{
    use Concerns\BelongsToRt;
    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
        ];
    }

    /** @return BelongsTo<Warga, $this> */
    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class);
    }
}
