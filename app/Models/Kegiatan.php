<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['rt_id', 'judul', 'kategori', 'tanggal', 'waktu', 'lokasi', 'status', 'deskripsi'])]
class Kegiatan extends Model
{
    use Concerns\BelongsToRt;

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    /** @return HasMany<KegiatanHadir> */
    public function hadirs(): HasMany
    {
        return $this->hasMany(KegiatanHadir::class);
    }

    /**
     * Status efektif: pakai kolom status bila diisi (mis. dibatalkan),
     * selain itu diturunkan dari tanggal.
     */
    public function statusEfektif(): string
    {
        if ($this->status) {
            return $this->status;
        }

        return $this->tanggal && $this->tanggal->isFuture() ? 'akan_datang' : 'selesai';
    }
}
