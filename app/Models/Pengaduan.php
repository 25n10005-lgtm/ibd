<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['rt_id', 'judul', 'lokasi', 'pelapor', 'deskripsi', 'status'])]
class Pengaduan extends Model
{
    use Concerns\BelongsToRt;
    //
}
