---
paths:
  - 'resources/views/**/*.blade.php'
---

# Views

## Gunakan komponen Blade x-* untuk pola UI berulang
Pakai komponen Blade terpusat, jangan duplikasi markup: x-hi (Heroicons, daftar nama di components/hi.blade.php), x-page-header, x-search-bar, x-filter-select, x-stat-card, x-icon-btn (tooltip), x-empty-state. Skeleton pakai Tailwind animate-pulse via x-skeleton-table/cards; bungkus area hasil filter dengan x-loading-wrap + tambahkan data-loading pada form agar skeleton muncul saat submit.
