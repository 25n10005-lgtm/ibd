<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['rt_id', 'no_surat', 'jenis', 'pemohon', 'blok', 'lampiran', 'status', 'catatan'])]
class SuratPengajuan extends Model
{
    use Concerns\BelongsToRt;
    public function isArsip(): bool
    {
        return in_array($this->status, ['Selesai', 'Ditolak'], true);
    }
}
