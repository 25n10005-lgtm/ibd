{{-- Dropdown filter konsisten, auto-submit saat berubah. Options: ['value' => 'Label']. --}}
@props(['name' => '', 'value' => '', 'options' => [], 'all' => 'Semua'])
<select name="{{ $name }}" onchange="this.form.submit()" {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[12.5px] font-bold text-slate-600 outline-none']) }}>
    <option value="">{{ $all }}</option>
    @foreach ($options as $v => $l)
        <option value="{{ $v }}" @selected((string) $value === (string) $v)>{{ $l }}</option>
    @endforeach
</select>
