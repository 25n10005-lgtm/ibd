<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\AccountStatus;
use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'clerk_id', 'google_id', 'avatar', 'role', 'requested_role', 'status', 'rt_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'requested_role' => Role::class,
            'status' => AccountStatus::class,
        ];
    }

    /**
     * RT utama pengguna (untuk Admin: satu-satunya RT yang boleh diakses).
     */
    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class);
    }

    /**
     * RT tambahan (untuk Super Admin yang dibatasi ke beberapa RT).
     */
    public function rts(): BelongsToMany
    {
        return $this->belongsToMany(Rt::class)->withTimestamps();
    }

    /**
     * Daftar ID RT yang boleh diakses pengguna.
     * Developer: semua RT. Super Admin: semua bila tak dibatasi, atau
     * gabungan RT utama + tambahan. Admin: hanya RT utama.
     */
    public function accessibleRtIds(): array
    {
        if ($this->hasRole(Role::Developer)) {
            return Rt::pluck('id')->all();
        }

        $ids = array_filter([$this->rt_id]);
        $extra = $this->relationLoaded('rts') ? $this->rts->pluck('id')->all() : $this->rts()->pluck('rts.id')->all();

        if ($this->hasAtLeastRole(Role::SuperAdmin) && $ids === [] && $extra === []) {
            return Rt::pluck('id')->all();
        }

        return array_values(array_unique(array_merge($ids, $extra)));
    }

    public function canAccessRt(int $rtId): bool
    {
        return in_array($rtId, $this->accessibleRtIds(), true);
    }

    /**
     * RT aktif untuk scoping data: RT utama, atau pertama dari daftar akses.
     */
    public function currentRtId(): ?int
    {
        return $this->rt_id ?? $this->accessibleRtIds()[0] ?? null;
    }

    /**
     * ID RT untuk scope query: null = semua RT (Developer),
     * selain itu RT pengguna (fallback RT pertama bila belum ditetapkan).
     */
    public function scopeRtId(): ?int
    {
        if ($this->hasRole(Role::Developer)) {
            return null;
        }

        return $this->currentRtId() ?? Rt::query()->value('id');
    }

    /**
     * Tautan verifikasi 1 akun <-> 1 NIK (pengganti cocok nama).
     */
    public function wargaLink(): HasOne
    {
        return $this->hasOne(UserWargaLink::class);
    }

    /** @return HasMany<WargaClaim> */
    public function wargaClaims(): HasMany
    {
        return $this->hasMany(WargaClaim::class);
    }

    public function linkedWarga(): ?Warga
    {
        return $this->relationLoaded('wargaLink')
            ? $this->wargaLink?->warga
            : $this->wargaLink()->with('warga')->first()?->warga;
    }

    public function hasRole(Role $role): bool
    {
        return $this->role === $role;
    }

    public function hasAtLeastRole(Role $minimum): bool
    {
        return $this->role !== null && $this->role->atLeast($minimum);
    }

    public function isActive(): bool
    {
        return $this->status === AccountStatus::Active;
    }
}
