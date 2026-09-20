<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TablePage
{
    /**
     * Pilihan jumlah baris yang diizinkan: 5, 10, 25, 50, atau 'all'.
     */
    public static function perPage(Request $request, Builder $query, int $default = 10): int
    {
        $input = $request->input('per_page', $default);

        if ($input === 'all') {
            return max((clone $query)->count(), 1);
        }

        $allowed = [5, 10, 25, 50];

        return in_array((int) $input, $allowed, true) ? (int) $input : $default;
    }
}
