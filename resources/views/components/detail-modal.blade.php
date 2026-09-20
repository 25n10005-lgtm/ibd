{{-- Modal preview read-only. Pemicu: data-modal-open="<id>" + data-* lain
     yang disalin ke [data-modal-field="<nama>"]. Tombol tutup: data-modal-close. --}}
@props(['id' => 'detailModal', 'title' => 'Detail'])
<div id="{{ $id }}" data-modal class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-5 shadow-2xl" role="dialog" aria-modal="true">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-[15px] font-extrabold">{{ $title }}</h2>
            <button type="button" data-modal-close class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100"><x-hi name="x" width="16" height="16" /></button>
        </div>
        <div class="mt-3">{{ $slot }}</div>
        <button type="button" data-modal-close class="mt-4 w-full rounded-xl bg-slate-100 py-2.5 text-[13px] font-bold text-slate-600 transition hover:bg-slate-200">Tutup</button>
    </div>
</div>
<script>
    (() => {
        if (window.__detailModalWired) return;
        window.__detailModalWired = true;
        const open = (modal) => { modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow = 'hidden'; };
        const close = (modal) => { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = ''; };
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-modal-open]');
            if (trigger) {
                const modal = document.getElementById(trigger.getAttribute('data-modal-open'));
                if (!modal) return;
                Object.entries(trigger.dataset).forEach(([key, value]) => {
                    if (key === 'modalOpen') return;
                    modal.querySelectorAll('[data-modal-field="' + key + '"]').forEach((el) => { el.textContent = value; });
                });
                open(modal);
                return;
            }
            if (e.target.closest('[data-modal-close]')) {
                const modal = e.target.closest('[data-modal]');
                if (modal) close(modal);
                return;
            }
            const backdrop = e.target.closest('[data-modal]');
            if (backdrop && e.target === backdrop) close(backdrop);
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') document.querySelectorAll('[data-modal].flex').forEach(close);
        });
    })();
</script>