# Product Requirements Document (PRD)
# Smart RT

## 1. Overview

Smart RT adalah aplikasi web/PWA yang digunakan untuk membantu pengelolaan administrasi RT secara lebih terstruktur, transparan, dan mudah diakses oleh warga maupun pengurus RT.

Aplikasi menggantikan proses administrasi yang sebelumnya dapat dilakukan secara manual, seperti pencatatan warga, surat pengantar, iuran, inventaris, kegiatan, laporan keuangan, dan aspirasi warga.

Smart RT dirancang agar dapat digunakan melalui perangkat desktop maupun smartphone.

---

# 2. Product Goals

Tujuan utama Smart RT:

1. Mempermudah pengurus dalam mengelola administrasi RT.
2. Mempermudah warga dalam mengakses layanan administrasi.
3. Meningkatkan transparansi pengelolaan keuangan RT.
4. Mengurangi pencatatan manual dan duplikasi data.
5. Menyediakan informasi RT dalam satu sistem.
6. Menerapkan hak akses berdasarkan role pengguna.
7. Memastikan data antar-RT tidak tercampur.

---

# 3. Target Users

## 3.1 Warga

Warga menggunakan aplikasi untuk:

- Melihat informasi RT.
- Mengajukan surat pengantar.
- Melihat informasi iuran.
- Mengunggah bukti pembayaran.
- Melihat kegiatan RT.
- Melihat laporan keuangan yang diperbolehkan.
- Mengirim aspirasi.
- Melihat data pribadi mereka.

## 3.2 Ketua RT

Ketua RT memiliki akses untuk:

- Melihat dashboard RT.
- Mengelola data warga.
- Mengelola kegiatan.
- Menyetujui atau memproses surat.
- Melihat dan mengelola informasi keuangan.
- Melihat inventaris.
- Mengelola aspirasi.
- Melihat laporan RT.

## 3.3 Sekretaris

Sekretaris berfokus pada administrasi:

- Data warga.
- Surat pengantar.
- Kegiatan.
- Administrasi umum.
- Informasi RT.

## 3.4 Bendahara

Bendahara berfokus pada keuangan:

- Iuran.
- Pemasukan.
- Pengeluaran.
- Bukti transaksi.
- Laporan keuangan.

---

# 4. Scope MVP

Fitur utama MVP:

- Authentication
- Role-Based Access Control
- Data warga
- Data RT
- Surat pengantar
- Iuran
- Kegiatan
- Inventaris
- Laporan keuangan
- Aspirasi
- Dashboard

Fitur di luar scope MVP tidak perlu dibuat sebelum kebutuhan utama selesai.

---

# 5. Authentication

Pengguna dapat login ke dalam aplikasi.

Minimal informasi:

- Email/username
- Password
- Role
- RT yang terkait

Sistem harus memastikan pengguna hanya dapat mengakses resource sesuai role dan RT-nya.

---

# 6. Role-Based Access Control

Role yang digunakan:

```text
Warga
Ketua
Sekretaris
Bendahara

Hak akses berbeda berdasarkan role.

Contoh:

Fitur	Warga	Ketua	Sekretaris	Bendahara
Dashboard	✓	✓	✓	✓
Data warga	Terbatas	✓	✓	Terbatas
Surat	Ajukan	Kelola	Kelola	-
Kegiatan	Lihat	Kelola	Kelola	-
Iuran	Lihat/Upload	Lihat	-	Kelola
Keuangan	Lihat sesuai akses	✓	Terbatas	✓
Inventaris	Lihat	Kelola	Kelola	-
Aspirasi	Buat	Kelola	Kelola	-

Hak akses final dapat disesuaikan dengan kebutuhan RT.

7. Multi-RT Data Isolation

Smart RT harus mendukung pemisahan data antar-RT.

Setiap resource yang berkaitan dengan RT harus memiliki hubungan dengan RT tertentu.

Contoh:

RT 01
├── Warga
├── Surat
├── Iuran
├── Kegiatan
├── Inventaris
└── Keuangan

RT 02
├── Warga
├── Surat
├── Iuran
├── Kegiatan
├── Inventaris
└── Keuangan

Warga dan pengurus dari RT tertentu tidak boleh dapat mengakses data RT lain kecuali terdapat kebutuhan administratif yang secara eksplisit diizinkan.

Isolasi harus diterapkan pada backend/database, bukan hanya pada tampilan frontend.

8. Data Warga

Sistem menyimpan data warga yang diperlukan untuk administrasi RT.

Data dapat meliputi:

Nama lengkap
NIK
Nomor KK
Tempat/tanggal lahir
Jenis kelamin
Alamat
Nomor telepon
Status dalam keluarga
Status kependudukan
RT/RW
Status warga

Data sensitif tidak boleh ditampilkan kepada seluruh pengguna.

Pengurus hanya dapat melihat data sesuai kebutuhan dan hak akses.

9. Surat Pengantar

Warga dapat mengajukan surat pengantar melalui aplikasi.

Alur:

Warga
  ↓
Mengisi pengajuan
  ↓
Menunggu pemeriksaan pengurus
  ↓
Disetujui / Ditolak
  ↓
Surat selesai

Informasi pengajuan dapat meliputi:

Jenis surat
Keperluan
Keterangan
Tanggal pengajuan
Status
Catatan pengurus

Status minimal:

Menunggu
Diproses
Disetujui
Ditolak
Selesai
10. Iuran

Sistem digunakan untuk mencatat iuran warga.

Fungsi:

Melihat informasi iuran.
Mencatat pembayaran.
Mengunggah bukti pembayaran.
Memeriksa status pembayaran.
Mengelola data iuran oleh bendahara.

Status pembayaran dapat berupa:

Belum Bayar
Menunggu Verifikasi
Diverifikasi
Ditolak

Sistem tidak boleh secara otomatis membuat kewajiban pajak atau tagihan pemerintah kepada warga.

Iuran merupakan administrasi internal RT.

11. Laporan Keuangan

Sistem mencatat transaksi keuangan RT.

Jenis transaksi:

Pemasukan
Pengeluaran

Data transaksi minimal:

Tanggal
Jenis transaksi
Kategori
Nominal
Keterangan
Bukti transaksi
Pencatat

Contoh:

Pemasukan
Iuran warga       Rp500.000

Pengeluaran
Kegiatan RT       Rp200.000

Dashboard keuangan dapat menampilkan:

Total Pemasukan
Total Pengeluaran
Saldo

Laporan harus dapat dilihat berdasarkan hak akses.

Tujuan utama fitur ini adalah meningkatkan keterbukaan dan akuntabilitas keuangan RT.

12. Kegiatan RT

Pengurus dapat membuat dan mengelola kegiatan RT.

Informasi kegiatan:

Nama kegiatan
Tanggal
Waktu
Lokasi
Deskripsi
Penanggung jawab
Dokumentasi
Status

Warga dapat melihat kegiatan yang dipublikasikan.

13. Inventaris

Sistem mencatat aset/inventaris milik RT.

Informasi:

Nama barang
Jumlah
Tanggal pembelian
Harga satuan
Total harga
Supplier
Kondisi
Keterangan

Kondisi dapat berupa:

Baik
Rusak Ringan
Rusak Berat
Tidak Layak

Pengurus dapat melakukan CRUD inventaris sesuai hak akses.

14. Aspirasi Warga

Warga dapat mengirim aspirasi atau masukan kepada pengurus.

Data:

Judul
Isi
Kategori
Tanggal
Status
Tanggapan pengurus

Status:

Diterima
Diproses
Selesai
Ditolak

Warga dapat melihat status aspirasi miliknya.

Pengurus dapat mengelola aspirasi sesuai hak akses.

15. Dashboard

Dashboard memberikan ringkasan informasi sesuai role pengguna.

Dashboard Warga

Dapat menampilkan:

Informasi pribadi
Status surat
Status iuran
Kegiatan terbaru
Aspirasi
Pengumuman
Dashboard Pengurus

Dapat menampilkan:

Jumlah warga
Pengajuan surat
Status iuran
Saldo keuangan
Kegiatan
Inventaris
Aspirasi

Informasi dashboard harus disesuaikan dengan hak akses.

16. Notifications

Sistem dapat memberikan notifikasi untuk event penting.

Contoh:

Surat disetujui.
Surat ditolak.
Pembayaran iuran diverifikasi.
Pembayaran ditolak.
Aspirasi mendapat tanggapan.
Pengumuman kegiatan baru.

Untuk MVP, notification dapat dimulai dengan notification di dalam aplikasi.

17. Reporting

Sistem menyediakan laporan berdasarkan data yang tersedia.

Minimal:

Laporan keuangan
Laporan iuran
Laporan inventaris
Laporan warga

Laporan dapat difilter berdasarkan periode jika dibutuhkan.

18. Search & Filter

Data dengan jumlah besar harus memiliki pencarian/filter.

Minimal:

Warga → nama/NIK
Surat → status/periode
Iuran → warga/status/periode
Keuangan → jenis/periode/kategori
Inventaris → nama/kondisi
Aspirasi → status/kategori
19. Responsive & PWA

Smart RT harus dapat digunakan pada:

Mobile
Tablet
Desktop

Aplikasi dirancang sebagai PWA sehingga dapat memberikan pengalaman seperti aplikasi mobile.

Prioritas desain:

Mobile usability
↓
Tablet
↓
Desktop
20. UI/UX

Design direction:

Fresh Modern Civic UI

Karakter:

Modern
Bersih
Profesional
Ramah
Tidak terlalu formal
Mudah digunakan oleh pengguna non-teknis

Typography:

Plus Jakarta Sans

Color palette:

Cobalt   #2863D1
Forest   #20A774
Sand     #F6C66A
Offwhite #FAF9F5

UI harus konsisten antar halaman.

21. Data Privacy

Tidak semua data warga boleh ditampilkan secara publik.

Data harus dibedakan berdasarkan tingkat akses.

Contoh:

Public
↓
Informasi kegiatan / pengumuman

Warga sendiri
↓
Data pribadi sendiri

Pengurus
↓
Data administratif yang diperlukan

Bendahara
↓
Data keuangan

Ketua
↓
Data administratif sesuai kewenangan

Informasi sensitif harus dibatasi berdasarkan authorization.

22. Business Rules
Warga

Warga hanya dapat mengelola resource miliknya sendiri jika fitur tersebut bersifat personal.

Pengurus

Pengurus hanya dapat melakukan operasi sesuai role.

Data RT

Resource harus selalu terikat pada RT yang sesuai.

Keuangan

Setiap transaksi harus memiliki informasi yang cukup untuk ditelusuri.

Iuran

Pembayaran yang membutuhkan verifikasi tidak langsung dianggap lunas sebelum diverifikasi.

Surat

Pengajuan memiliki status yang jelas dan dapat ditelusuri.

23. Non-Functional Requirements
Performance

Aplikasi harus tetap responsif pada koneksi internet umum.

Hindari query database yang tidak diperlukan.

Security

Sistem harus menerapkan:

Authentication
Authorization
Input validation
CSRF protection
Secure file upload
Password hashing
Data isolation
Maintainability

Kode harus terstruktur sehingga fitur baru dapat ditambahkan tanpa mengubah banyak bagian sistem yang tidak berkaitan.

Scalability

Struktur database dan aplikasi harus memungkinkan penambahan RT baru tanpa perlu membuat database atau aplikasi terpisah untuk setiap RT.

24. MVP Acceptance Criteria

MVP dianggap berhasil apabila:

User dapat login.
Role user dikenali sistem.
User hanya dapat mengakses fitur sesuai role.
Data antar-RT terisolasi.
Warga dapat mengajukan surat.
Pengurus dapat memproses surat.
Warga dapat melihat/mengirim data iuran.
Bendahara dapat mengelola pembayaran.
Pengurus dapat mencatat pemasukan dan pengeluaran.
Laporan keuangan dapat ditampilkan.
Pengurus dapat mengelola inventaris.
Pengurus dapat mengelola kegiatan.
Warga dapat mengirim aspirasi.
Pengurus dapat merespons aspirasi.
Dashboard menampilkan data yang relevan.
Aplikasi dapat digunakan pada mobile dan desktop.
25. Out of Scope MVP

Fitur berikut tidak menjadi prioritas MVP:

Pembayaran otomatis melalui payment gateway.
Integrasi pajak pemerintah.
Integrasi Dukcapil.
Integrasi WhatsApp API berbayar.
AI chatbot.
Prediksi keuangan.
Sistem voting kompleks.
Integrasi perangkat IoT.
Microservices architecture.

Fitur tersebut dapat dipertimbangkan pada fase berikutnya apabila terdapat kebutuhan nyata.

26. Future Development

Setelah MVP stabil, pengembangan dapat diarahkan ke:

Phase 1
Core Administration

Phase 2
Digital Services

Phase 3
Automation & Notifications

Phase 4
Analytics

Phase 5
Advanced Integration

Prioritas pengembangan harus berdasarkan kebutuhan pengguna, bukan sekadar penambahan fitur.

27. Product Success

Smart RT dianggap berhasil apabila proses administrasi RT menjadi:

Lebih mudah
Lebih terstruktur
Lebih transparan
Lebih mudah diakses
Lebih aman
Lebih mudah dipertanggungjawabkan

Fokus produk bukan membuat sistem yang kompleks, tetapi membuat administrasi RT menjadi lebih praktis bagi warga dan pengurus.
