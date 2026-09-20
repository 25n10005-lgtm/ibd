<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode', 'nama', 'rw', 'alamat', 'aktif'])]
class Rt extends Model
{
    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function label(): string
    {
        return trim("{$this->nama}".($this->rw ? " / {$this->rw}" : ''));
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
