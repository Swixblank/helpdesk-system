# PANDUAN PENGGUNA (USER MANUAL)
# IT HELPDESK MANAGEMENT SYSTEM — VERSI 1.0

Buku panduan ini disusun sebagai petunjuk operasional penggunaan aplikasi **IT Helpdesk Management System** untuk seluruh role pengguna: **Employee**, **IT Support**, dan **Administrator**.

---

## 1. Kredensial Akun Uji Coba (Demo Accounts)

Aplikasi telah dilengkapi akun bawaan untuk masing-masing role dengan kata sandi seragam:

| Role | Alamat Email | Kata Sandi | Ruang Lingkup Akses |
| :--- | :--- | :---: | :--- |
| **Administrator** | `admin@helpdesk.test` | `password` | Dashboard organisasi, Master Kategori, Penugasan Teknisi, Laporan & Export Excel |
| **IT Support** | `support@helpdesk.test` | `password` | Antrean Tiket, Pengambilan Tiket Mandiri, Perubahan Status Pengerjaan, Resolusi |
| **Employee** | `employee@helpdesk.test` | `password` | Pembuatan Tiket, Pelacakan Status, Komentar, Konfirmasi Tutup (*Close*), *Reopen* |

---

## 2. Panduan Peran: Employee (Karyawan Pelapor)

### 2.1 Masuk ke Sistem (Login)
1. Buka browser dan arahkan ke alamat aplikasi (contoh: `http://localhost:8000`).
2. Masukkan email karyawan (misal: `employee@helpdesk.test`) dan kata sandi.
3. Centang opsi **Ingat Saya** bila diperlukan, lalu klik tombol **Masuk**.

### 2.2 Membuat Tiket Kendala Baru
1. Pada menu navigasi atas atau tombol di Dashboard, klik **Buat Tiket Baru**.
2. Lengkapi formulir pelaporan:
   - **Subjek Masalah:** Ringkasan kendala (maksimal 150 karakter).
   - **Kategori Masalah:** Pilih kategori yang sesuai (misal: *Hardware*, *Network*, *Printer*).
   - **Tingkat Prioritas:** Tentukan dampak (*Low*, *Medium*, *High*, atau *Critical*).
   - **Deskripsi Detail:** Ceritakan kronologi kejadian dan pesan error yang tampil.
   - **Lampiran Berkas (Opsional):** Pilih tangkapan layar error atau berkas PDF (maksimal 5 MB).
3. Klik tombol **Kirim Tiket Kendala**.
4. Sistem akan otomatis menerbitkan **Nomor Tiket Unik** (contoh: `TICK-202611-0001`) dan mengarahkan ke halaman detail tiket.

### 2.3 Berkomunikasi via Komentar & Unggah Lampiran
1. Buka tiket Anda melalui menu **Daftar Tiket**.
2. Gulir ke bagian **Aktivitas & Diskusi Tiket**.
3. Ketik pesan atau pertanyaan tambahan pada kolom komentar.
4. Klik **Lampirkan File** bila ingin menyertakan gambar/dokumen tambahan.
5. Klik **Kirim Komentar**.

### 2.4 Mengonfirmasi Tiket Selesai (`CLOSED`) atau Membuka Kembali (`REOPENED`)
1. Ketika staf IT Support telah menyelesaikan perbaikan, tiket Anda akan berstatus **`RESOLVED`** dengan catatan solusi teknis terlampir.
2. Jika kendala telah **tuntas teratasi**:
   - Klik tombol hijau **Konfirmasi Tutup (Closed)** di pojok kanan atas.
   - Tiket akan ditutup permanen dan fitur komentar dinonaktifkan.
3. Jika kendala **masih berlanjut / kambuh**:
   - Klik tombol merah **Buka Kembali (Reopen)**.
   - Masukkan alasan mengapa masalah belum tuntas.
   - Klik **Kirim Permintaan Reopen**; tiket otomatis berstatus `REOPENED` untuk ditindaklanjuti ulang oleh teknisi.

---

## 3. Panduan Peran: IT Support (Petugas Teknis)

### 3.1 Mengambil Tiket yang Tersedia (*Self-Claim*)
1. Login menggunakan akun IT Support (`support@helpdesk.test`).
2. Buka menu **Daftar Tiket** atau periksa kartu **Tiket Open (Bisa Diambil)** pada Dashboard.
3. Buka tiket berstatus `OPEN` yang belum memiliki petugas.
4. Klik tombol **Ambil Tiket Ini** di pojok kanan atas. Status tiket otomatis berganti menjadi **`ASSIGNED`** dan penugasan tercatat atas nama Anda.

### 3.2 Menjalankan Pengerjaan (*In Progress*)
1. Pada tiket yang telah ditugaskan kepada Anda, klik tombol biru **Mulai Pengerjaan**.
2. Status tiket berubah menjadi **`IN_PROGRESS`** dan tercatat di riwayat audit linimasa.

### 3.3 Menyelesaikan Kendala (*Resolve*)
1. Setelah perbaikan perangkat atau konfigurasi sistem selesai, buka tiket terkait.
2. Klik tombol hijau **Tandai Selesai (Resolve)**.
3. Jendela pop-up akan meminta **Catatan Solusi Perbaikan**:
   - Jelaskan tindakan teknis yang telah diambil (misal: penggantian komponen, reset setting, update software).
4. Klik tombol **Tandai Resolved**. Status tiket berubah menjadi `RESOLVED` dan sistem mencatat waktu penyelesaian (`resolved_at`).

---

## 4. Panduan Peran: Administrator (Pengelola Sistem)

### 4.1 Manajemen Master Kategori Kendala
1. Login menggunakan akun Administrator (`admin@helpdesk.test`).
2. Klik menu **Kategori** pada navigasi atas.
3. **Menambah Kategori Baru:** Klik **Tambah Kategori** &rarr; masukkan nama dan deskripsi &rarr; simpan.
4. **Mengubah / Menonaktifkan Kategori:**
   - Klik tombol **Edit** untuk memperbarui nama/deskripsi.
   - Klik tautan **Nonaktifkan** untuk mematikan kategori agar tidak dapat dipilih pada tiket baru tanpa merusak histori data tiket lama.

### 4.2 Menugaskan Teknisi (*Assignment / Reassignment*)
1. Administrator dapat membuka tiket mana pun di organisasi.
2. Klik tombol **Tugaskan Teknisi** atau **Ubah Petugas** pada kanan atas tiket.
3. Pilih staf IT Support dari daftar dropdown, tambahkan instruksi/catatan khusus bila perlu, lalu klik **Simpan Penugasan**.
4. Riwayat penugasan lama otomatis diarsipkan dan penugasan baru aktif.

### 4.3 Rekapitulasi Laporan & Ekspor Excel (CSV)
1. Klik menu **Laporan** pada navigasi atas.
2. Gunakan saringan multi-filter yang fleksibel:
   - Filter Status (*Open, In Progress, Resolved, Closed, Reopened*).
   - Filter Kategori Masalah.
   - Filter Prioritas.
   - Filter Petugas IT Support.
   - Filter Rentang Tanggal Pembuatan (*Dari Tanggal* & *Sampai Tanggal*).
3. Tabel akan otomatis menampilkan data yang sesuai dengan ringkasan jumlah data.
4. Klik tombol hijau **Export ke Excel (.CSV)** di pojok kanan atas.
5. Berkas spreadsheet berformat UTF-8 BOM akan otomatis terunduh dan dapat langsung dibuka di Microsoft Excel dengan kolom-kolom yang tertata rapi.

---

## 5. Pertanyaan Umum (FAQ) & Pemecahan Masalah

* **Tanya:** Mengapa saya tidak bisa mengetik komentar lagi pada tiket tertentu?  
  **Jawab:** Tiket tersebut telah berstatus **`CLOSED`**. Tiket yang telah ditutup bersifat final untuk menjaga integritas arsip audit.

* **Tanya:** Bagaimana cara melihat lampiran gambar atau PDF pada tiket?  
  **Jawab:** Klik tombol **Unduh** di sebelah nama berkas pada kotak lampiran. Berkas akan dialirkan secara aman melalui server terproteksi otorisasi.

* **Tanya:** Mengapa muncul error koneksi database saat pertama kali membuka web?  
  **Jawab:** Pastikan service MySQL di XAMPP / Laragon Anda sudah dalam status **Start** / aktif di port 3306.
