<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['keluarga_id', 'nik', 'nama', 'jenis_kelamin', 'tanggal_lahir', 'status_tinggal', 'aktif', 'no_hp', 'alamat'])]
class Warga extends Model
{
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'aktif' => 'boolean',
        ];
    }

    /** @return BelongsTo<Keluarga, $this> */
    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(Keluarga::class);
    }

    /** @return HasMany<IuranPembayaran> */
    public function iurans(): HasMany
    {
        return $this->hasMany(IuranPembayaran::class);
    }

    /** @return HasOne<UserWargaLink> */
    public function userLink(): HasOne
    {
        return $this->hasOne(UserWargaLink::class);
    }

    /** @return HasMany<WargaClaim> */
    public function claims(): HasMany
    {
        return $this->hasMany(WargaClaim::class);
    }

    public function getUsiaAttribute(): ?int
    {
        return $this->tanggal_lahir?->age;
    }
}
