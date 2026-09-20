<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['rt_id', 'arah', 'deskripsi', 'jumlah', 'kategori', 'tanggal'])]
class KasTransaksi extends Model
{
    use Concerns\BelongsToRt;
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function isMasuk(): bool
    {
        return $this->arah === 'masuk';
    }
}
