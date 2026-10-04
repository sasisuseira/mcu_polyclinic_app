# Laporan Perbaikan Hak Akses Laporan

**Tanggal:** 5 Oktober 2026  
**Sistem:** Artha Medical Centre

## Ringkasan

Pemeriksaan dilakukan pada menu dan hak akses laporan, khususnya menu **Transaksi** dan **Rekapitulasi** untuk role `admin_mcu`. Ditemukan ketidaksesuaian antara permission pada route, data permission yang tersedia, dan kondisi yang digunakan untuk menampilkan menu.

## Temuan

- Sepuluh route rekap personal menggunakan nama permission yang tidak sesuai dengan permission rekap yang tersedia. Akibatnya, pengguna yang sudah memiliki hak rekap tetap berisiko mendapat akses ditolak.
- Menu **Rekapitulasi** dan judul bagian **Laporan AMC** bergantung pada permission Transaksi, bukan pada permission Rekapitulasi. Menu rekap dapat tersembunyi meskipun role telah memiliki hak akses rekap.
- Permission laporan penjualan dan insentif belum tersedia pada daftar hak akses, sehingga belum dapat diberikan melalui pengaturan Role.
- Route Transaksi Kuitansi menggunakan nama permission yang berbeda dari permission kuitansi yang telah tersedia.
- Route rekap personal dan perusahaan menggunakan nama route yang sama.

## Perbaikan

- Menyamakan permission pada seluruh route rekap personal dengan permission rekap yang tersedia.
- Menampilkan menu **Rekapitulasi** dan bagian **Laporan AMC** berdasarkan permission laporan/rekap yang relevan.
- Menambahkan permission `akses_laporan_penjualan` dan `akses_laporan_insentif` ke daftar permission sistem. Permission ini dapat dipilih pada role mana pun melalui pengaturan Role.
- Memberikan permission Penjualan, Insentif, dan Kuitansi kepada role `admin_mcu` agar menu **Transaksi** beserta halamannya dapat diakses.
- Menyamakan pemeriksaan permission pada route Transaksi Kuitansi dengan permission kuitansi yang tersedia.
- Membuat nama route rekap perusahaan unik agar tidak bertabrakan dengan route rekap personal.

## Hasil

- Seluruh route rekap personal dan perusahaan terdaftar dengan permission masing-masing.
- Menu **Rekapitulasi** tampil berdasarkan hak akses rekap, tidak lagi bergantung pada hak Transaksi.
- Menu **Transaksi** dapat diakses oleh `admin_mcu` untuk Penjualan, Insentif, dan Kuitansi.
- Permission Penjualan dan Insentif dapat dikelola secara dinamis dan diberikan kepada role lain sesuai kebutuhan.

## Verifikasi

- Migration permission berhasil dijalankan.
- Route laporan rekap dan transaksi diperiksa beserta middleware permission-nya.
- Template Blade berhasil dikompilasi dengan `php artisan view:cache`.
- Pemeriksaan diagnostik pada file yang diubah tidak menemukan error.

**Catatan operasional:** pengguna `admin_mcu` yang sedang login perlu keluar dan masuk kembali agar hak akses terbaru dimuat ke sesi.
