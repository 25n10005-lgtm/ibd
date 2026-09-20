{{-- Bungkus konten + skeleton: skeleton tampil saat form[data-loading] di-submit. --}}
@props(['rows' => 5, 'cols' => 4])
<div data-loading-wrap>
    <div data-loading-content>{{ $slot }}</div>
    <div data-loading-skeleton class="hidden"><x-skeleton-table :rows="$rows" :cols="$cols" /></div>
</div>
<script>
    (() => {
        if (window.__loadingWrapInit) return;
        window.__loadingWrapInit = true;
        document.addEventListener('submit', (e) => {
            const form = e.target.closest('form[data-loading]');
            if (!form) return;
            document.querySelectorAll('[data-loading-wrap]').forEach((w) => {
                w.querySelector('[data-loading-content]')?.classList.add('hidden');
                w.querySelector('[data-loading-skeleton]')?.classList.remove('hidden');
            });
        });
    })();
</script>
