# Smart RT — Spesifikasi Backend (untuk Tim BE)

Dokumen ini mendefinisikan **fitur yang harus ada di backend** Smart RT.
Frontend (Blade + Tailwind) sudah memanggil kontrak di bawah — jangan ubah
nama route, shape validasi, atau nilai enum tanpa koordinasi dengan tim FE.

Stack: **Laravel 13 / PHP 8.3 / MySQL**, auth **Clerk (utama)** + Socialite Google + email/password native.
Referensi produk: `PRD.md`.

---

## 1. Autentikasi

### 1.1 Metode login (3 cara, 1 akun per email)
| Cara | Route | Keterangan |
|---|---|---|
| Email + password | `POST /login` (`login.attempt`, throttle 10/menit) | `Auth::attempt`, tolak akun `rejected` (403 + logout) |
| Google via Clerk JS | cookie `__clerk_db_jwt*` / Bearer ke `clerk.auth` | Verifikasi RS256 (`ClerkSessionVerifier`), provisioning JIT |
| Google via server | `GET /auth/google/redirect` lalu `/callback` | Socialite, fallback saat Clerk JS gagal |

Akun menyatu lewat **email unik**: user baru via Google default `role=user`, `status=active`.
Registrasi native (`POST /register`): `name, email unik, password min 8 + confirmed, role in user/warga/admin/pengelola, rt_id required exists:rts`.
Pendaftar `admin` menjadi `requested_role=admin` + `status=pending` (masuk antrean approval).
`GET /auth/me` mengembalikan: `id, name, email, avatar, role, role_label, rt_id, status, requested_role`.

### 1.2 Password dan sesi
- `POST /account/password`: `current_password` boleh kosong untuk akun Google, `password min 8 + confirmed`.
- `POST /logout`: invalidate sesi, redirect login `?signed_out=1`.
- Semua controller JSON-aware: bila `expectsJson()` / path `api/*`, kembalikan JSON (bukan redirect).

---

## 2. RBAC (4 peran berjenjang)

`user(1) < admin(2) < super_admin(3) < developer(4)` via `hasAtLeastRole()`.

| Fitur | Warga | Admin (Pengelola) | Super Admin (Ketua) | Developer |
|---|---|---|---|---|
| Dashboard | `/warga/dashboard` milik sendiri | dashboard admin + statistik RT | sama + semua RT bila tak dibatasi | semua |
| Warga/keluarga | baca + verifikasi NIK sendiri | kelola + verifikasi NIK | sama | sama |
| Surat | ajukan + lihat sendiri | proses/tolak se-RT | sama | sama |
| Pengaduan | buat + lihat (+ komunitas 1 RT) | kelola + hapus | sama | sama |
| Iuran | tagihan + riwayat sendiri | kelola via kas/laporan | sama | sama |
| Kas/laporan/pengumuman/kegiatan/pengaturan RT | baca (yang terbit) | kelola | sama | sama |
| Approvals peran (`admin.*`) | — | — | ya | ya |
| Manajemen RT (`dev.*`) | — | — | — | ya |

Middleware: `clerk.auth` (wajib login), `role:{min}`, `rt.access` untuk objek lintas-RT.

### 2.1 Permohonan peran (`POST /account/request-role`)
- Warga ke `admin/pengelola`; Admin ke `super_admin/ketua`. Peran yang sudah dimiliki (422).
- Akun aktif tetap aktif selama menunggu. Approval (`ApprovalController`):
  `GET /admin/approvals`, `POST .../approve|reject`.
  Aturan keras: **tidak boleh menyetujui peran setara/lebih tinggi dari diri sendiri** (kecuali Developer).
## 3. Isolasi multi-RT (wajib di semua query domain)

- Setiap tabel domain punya `rt_id` (FK `rts`).
- Baca: `->forRt(auth()->user()->scopeRtId())` (trait `BelongsToRt`; `null` = semua, hanya Developer).
- Tulis: selalu isi `rt_id`. Akses objek: `User::canAccessRt()` / middleware `rt.access` (403 bila beda RT).
- Key cache agregat **wajib mengandung rtId**; setiap aksi tulis `Cache::forget` key terkait (TTL agregat 60-120 dtk).

---

## 4. Modul domain

### 4.1 RT dan pengguna (`dev.*`, khusus Developer)
CRUD `rts(kode unik, nama, rw, alamat, aktif)` + `assign-admin / unassign-admin / attach-superadmin / detach-superadmin`.
`User::accessibleRtIds()`: Developer = semua; SuperAdmin = semua bila tak dibatasi else utama+tambahan (pivot `rt_user`); Admin = `rt_id` utama saja.

### 4.2 Warga dan Keluarga + verifikasi NIK mandiri
Identitas area `/warga` diutamakan dari **`user_warga_links`** (1 akun ke 1 NIK, UNIQUE di kedua sisi); cocok-nama hanya fallback transisi.
Alur `WargaVerificationController` + `NikVerificationService::check()` — 4 gate berurutan, gagal di satu gate = stop + pesan spesifik (tanpa admin):
1. `nik_found` — NIK 16 digit terdaftar di `wargas`.
2. `not_linked` — NIK belum tertaut akun lain.
3. `same_rt` — `keluarga.rt_id` sama dengan `user.scopeRtId()`.
4. `basic_match` — No KK (16 digit) + nama (case-insensitive) + tanggal lahir cocok. Anggota keluarga tanpa NIK **tidak** diverifikasi satu per satu (cukup NIK pendaftar).
- Lolos: layar konfirmasi tersensor (`B*** S******`, `3174********0012`), lalu `POST .../ajukan` (gate dihitung ulang dari sesi; transaksi: buat `warga_claims[diajukan]` + 4 baris `warga_claim_checks`).
- Ganti NIK: NIK sama ditolak, NIK lain yang valid lolos; approval **menggantikan** tautan lama (hapus + buat dalam 1 transaksi).
- Admin (`role:admin`): `GET /verifikasi-nik` (hanya `diajukan` + lolos gate, scope RT), `POST .../setujui` (buat link + isi `rt_id` akun bila kosong + baris `warga_claim_decisions`), `POST .../tolak` (`reason required min 10`). Warga bisa `POST .../batal` untuk klaim miliknya.
- Tabel (semua kolom NOT NULL, 3NF): `user_warga_links(user_id UQ, warga_id UQ, verified_by, verified_at)`, `warga_claims(user_id, warga_id, status: diajukan|disetujui|ditolak|dibatalkan, UQ(user_id,warga_id))`, `warga_claim_checks(claim_id, check_type: nik_found|not_linked|same_rt|basic_match, passed, detail, UQ(claim_id,check_type))`, `warga_claim_decisions(claim_id PK, decided_by, decision, reason, decided_at)` (terpisah agar klaim pending tetap zero-null).
- Rate-limit cek/ajukan: `throttle:10,1`.

### 4.3 Surat pengantar
- Warga: `GET /warga/pengajuan-surat` (paginasi + count menunggu/selesai), `GET .../baru` (form), `POST ...` (`jenis required max 100`, `keperluan required min 10 max 1000`). `no_surat` format `SUR-tgl-counter`, `status=Menunggu`, `pemohon=user.name`, `blok=warga.alamat`, `rt_id` pengaju.
- Status: `Menunggu > Diproses > Selesai | Ditolak`. Admin: antrean prioritas (`surat.pengajuan`), semua + filter tanggal (`surat.semua`), detail lintas-RT 403, tolak hanya dari `Menunggu` + `alasan required min 10` (sanitasi tag).

### 4.4 Pengaduan
- Warga: `GET /warga/pengaduan` (`?status=` Semua|Belum|Proses|Selesai|Ditolak, paginasi 6) + seksi komunitas (laporan warga lain 1 RT, terbaru 4). `GET .../baru`, `POST ...` (`judul, lokasi required max 255`, `deskripsi nullable max 2000`, default `Belum`).
- Admin: `GET /pengaduan-warga` (count per status, cache 60 dtk), `DELETE .../{pengaduan}` (+ forget cache).

### 4.5 Iuran (`iuran_pembayarans`: rt_id, warga_id nullable, nama_pembayar, periode `YYYY-MM`, jumlah, tanggal_bayar nullable, status Lunas|Menunggak)
- Warga: `GET /warga/iuran` (kartu tunggakan + stat menunggak/lunas/total), `GET /warga/iuran/riwayat` (paginasi 10 + total lunas). Tanpa filter periode hardcode.
- Pembayaran bersifat instruksional (transfer/tunai + bukti via WA pengurus); tanpa payment gateway (out of scope PRD).

### 4.6 Kas dan laporan (`kas_transaksis`: arah masuk|keluar, deskripsi, jumlah, kategori nullable, tanggal)
- `GET /kas-rt` (sum + deret 6 bulan, cache 120 dtk), `GET /laporan-keuangan` (filter q/arah + paginasi), `POST ...` (validasi tanggal/deskripsi/arah/jumlah min 1 + forget 4 cache key), `GET .../export` (CSV), `POST .../import` (CSV max 2MB, skip baris invalid, hitung import).

### 4.7 Kegiatan + kehadiran
- Warga: `GET /warga/kegiatan` (mendatang 3 + semua filter kategori + paginasi; sertakan `hadir_count` dan status kehadiran sendiri).
- `POST /warga/kegiatan/{kegiatan}/kehadiran` (`status in hadir|tidak`, `updateOrCreate` per akun; 422 bila selesai/dibatalkan, 403 beda RT).
- `DELETE .../kehadiran` (batalkan milik sendiri). Tabel `kegiatan_hadirs(kegiatan_id, user_id, status, UQ(kegiatan_id,user_id))`, semua NOT NULL.

### 4.8 Pengumuman (`pengumuman`: judul, ringkasan, gambar string kosong = tanpa gambar, kategori, status draf|terjadwal|terbit, views, target_warga, published_at)
- Warga: `GET /warga/pengumuman` (hanya `terbit`, paginasi 6), `GET .../{pengumuman}` (404 bila bukan terbit, 403 beda RT, `increment(views)`, + 3 terkait). `gambarUrl()`: path lokal via `asset()` atau URL http apa adanya.
- Admin: `GET /pengumuman-admin` (filter q/kategori/status + sort + count). Form upload gambar belum ada — kolom sudah siap.

### 4.9 Pengaturan akun (admin) dan profil (warga)
- Info profil (NIK/telp/alamat di kartu admin saat ini masih dummy — BE wajib ganti dari `user_warga_links > wargas`).
- Kartu **Permohonan ke Admin**: status `requested_role`, tombol ajukan Ketua RT, atau link antrean bila sudah SuperAdmin.
- Tab lain (Bahasa/Zona/Tema, aktivitas login, hapus akun) saat ini non-fungsional — eksplisit out of scope tahap ini.

## 5. Konvensi backend (jangan dilanggar)

1. **Scope RT dulu** di setiap query domain; tulis selalu bawa `rt_id`.
2. **Cache agregat** (`Cache::remember`, key unik per bentuk data + rtId) + `Cache::forget` di semua aksi tulis.
3. **Paginasi** via `TablePage::perPage($request, $query)` (dukung `per_page`, termasuk `all`).
4. **Dual response**: redirect + flash untuk web, JSON untuk `expectsJson()`/`api/*`.
5. **Validasi berbahasa Indonesia** (pesan custom id) + throttle auth dan verifikasi NIK.
6. **File upload aman**: tipe/ukuran eksplisit, simpan path (bukan blob), kolom gambar default string kosong.
7. **Password hashing** via cast `hashed`; jangan kembalikan password/secret ke klien.
8. **Zero-null untuk tabel baru** (opsional = tabel anak / string kosong / baris lookup); FK + UNIQUE menegakkan aturan bisnis di DB, bukan cuma di kode.

---

## 6. Checklist serah terima (mapping PRD)

Login > role dikenali > akses sesuai role > isolasi RT > surat ajukan+proses > iuran lihat+riwayat > kas catat+ekspor/impor > laporan tampil > verifikasi NIK > kehadiran kegiatan > dashboard relevan > mobile+desktop.

**Belum ada / tahap berikut**: lupa password (tanpa reset flow), upload avatar dan gambar pengumuman (kolom siap, form belum), inventaris, aspirasi, notifikasi push, payment gateway, export PDF, audit log admin.

---

## 7. Cara menjalankan (BE)

```powershell
$env:DB_HOST="127.0.0.1"; $env:DB_PORT="3307"   # MySQL Docker dari host (jangan ubah .env!)
rtk php artisan migrate --force
rtk php artisan test --compact                    # 54 tes hijau = baseline
rtk php artisan view:cache; rtk php artisan view:clear
rtk npm run build                                 # wajib tiap ubah Blade agar class Tailwind baru ikut
```