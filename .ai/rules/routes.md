---
paths:
  - 'routes/*.php'
  - routes/web.php
---

# Routes

## Cache agregat dengan key unik + invalidasi saat tulis
Agregat count/sum di route admin wajib via Cache::remember dengan key unik per bentuk data (cth. kas:sums vs lap:sums — key sama isi beda pernah menimpa dan menjatuhkan test). TTL 60-120 dtk. Setiap route tulis (store/reject/import) wajib Cache::forget key yang relevan.

## Scope semua query domain per RT
Multi-tenant per RT: setiap query data domain wajib di-scope via ->forRt(auth()->user()->scopeRtId()) (trait BelongsToRt; null = semua/Developer). Data baru wajib isi rt_id. Cache key agregat harus menyertakan RT (:$rtId). Akses object lintas RT dicek via User::canAccessRt() / middleware rt.access.

## Resolusi identitas warga via user_warga_links
Identitas warga area /saya wajib via user_warga_links (User::linkedWarga()), cocok nama hanya fallback transisi. Klaim NIK: 1 akun <-> 1 NIK, gate otomatis di NikVerificationService, keputusan admin di warga_claim_decisions.
