<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['rt_id', 'judul', 'ringkasan', 'gambar', 'kategori', 'status', 'views', 'target_warga', 'published_at'])]
class Pengumuman extends Model
{
    use Concerns\BelongsToRt;

    protected $table = 'pengumuman';

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    /**
     * URL gambar sampul; kosong bila pengumuman tanpa gambar.
     */
    public function gambarUrl(): string
    {
        if ($this->gambar === '') {
            return '';
        }

        return str_starts_with($this->gambar, 'http') ? $this->gambar : asset($this->gambar);
    }
}
