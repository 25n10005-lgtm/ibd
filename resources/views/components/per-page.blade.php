{{-- Pilihan jumlah data per halaman (5 / 10 / 25 / 50 / Semua).
Me-render form GET sendiri + membawa filter aktif via hidden input.
Taruh di footer tabel, di sebelah teks "Menampilkan...". --}}
@props(['value' => 10])
<form method="GET" action="{{ url()->current() }}" class="contents">
    @foreach (request()->except(['per_page', 'page']) as $k => $v)
        @if (is_scalar($v) && $v !== null && $v !== '')
            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
        @endif
    @endforeach
    <label class="inline-flex items-center gap-2 text-[12px] text-slate-500">
        <select name="per_page" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-[12px] font-bold text-slate-600 outline-none">
            @foreach ([5 => '5', 10 => '10', 25 => '25', 50 => '50', 'all' => 'Semua'] as $v => $l)
                <option value="{{ $v }}" @selected((string) $value === (string) $v)>{{ $l }} / halaman</option>
            @endforeach
        </select>
    </label>
</form>
