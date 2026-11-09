# PRODUCT REQUIREMENTS DOCUMENT (PRD)

# IT Helpdesk Management System

| Informasi           | Detail                              |
| ------------------- | ----------------------------------- |
| Nama Produk         | IT Helpdesk Management System       |
| Versi Dokumen       | 1.0                                 |
| Status              | Draft untuk pengembangan            |
| Jenis Produk        | Aplikasi web internal perusahaan    |
| Durasi Pengembangan | 1 bulan (20 hari kerja)             |
| Tim Pengembang      | 1–2 peserta magang                  |
| Product Owner       | Pembimbing / Perwakilan IT          |
| Target Pengguna     | Employee, IT Support, Administrator |

---

## 1. Ringkasan Produk

IT Helpdesk Management System adalah aplikasi web internal untuk mencatat, mengelola, menugaskan, memantau, dan menyelesaikan laporan kendala teknologi informasi yang disampaikan karyawan perusahaan.

Aplikasi memusatkan proses pelaporan kendala ke dalam sistem tiket sehingga setiap laporan memiliki nomor tiket, kategori, prioritas, penanggung jawab, status, komunikasi, dan riwayat penanganan.

## 2. Latar Belakang dan Permasalahan

Penanganan kendala IT yang dilakukan melalui komunikasi informal berisiko menyebabkan laporan tercecer, status pekerjaan tidak transparan, penugasan tidak terdokumentasi, serta kesulitan menyusun laporan kinerja penanganan.

Sistem ini dibangun untuk menyediakan satu tempat pencatatan dan pemantauan kendala IT secara terstruktur.

## 3. Tujuan Produk

1. Menyediakan sarana pelaporan kendala IT bagi karyawan.
2. Memastikan setiap laporan tercatat sebagai tiket dengan nomor unik.
3. Memudahkan IT Support mengelola dan menyelesaikan tiket.
4. Menyediakan transparansi status dan riwayat penanganan.
5. Menyediakan ringkasan dan laporan tiket untuk Administrator.

## 4. Ruang Lingkup

### 4.1 In Scope — Wajib

- Autentikasi login dan logout.
- Role dan pembatasan hak akses.
- Dashboard berdasarkan role.
- Manajemen kategori masalah.
- Pembuatan dan pengelolaan tiket.
- Nomor tiket otomatis dan unik.
- Prioritas tiket.
- Penugasan tiket kepada IT Support.
- Perubahan status tiket.
- Komentar pada tiket.
- Upload lampiran.
- Catatan penyelesaian dan konfirmasi penutupan.
- Riwayat perubahan status dan penugasan.
- Pencarian dan filter tiket.
- Laporan dan export Excel.
- Validasi input dan pengujian fungsional.

### 4.2 Out of Scope — Tidak Dikerjakan pada Versi 1

- Integrasi WhatsApp, email, atau Telegram.
- Notifikasi push atau real-time eksternal.
- SLA otomatis dan eskalasi otomatis.
- Integrasi inventaris aset perusahaan.
- Integrasi Active Directory/LDAP/SSO.
- Aplikasi mobile native.
- AI untuk klasifikasi atau penyelesaian tiket.
- Survei kepuasan dan analitik lanjutan.
- Multi-company atau multi-tenant.

## 5. Pengguna dan Hak Akses

Sistem memiliki tiga role utama.

### 5.1 Employee

Karyawan yang melaporkan kendala IT.

Hak akses:

- Login dan logout.
- Melihat dashboard miliknya.
- Membuat tiket.
- Melihat daftar dan detail tiket yang dibuat sendiri.
- Menambahkan komentar pada tiket miliknya selama tiket belum ditutup.
- Mengunggah lampiran.
- Melihat status dan riwayat tiket miliknya.
- Mengonfirmasi tiket yang sudah berstatus Resolved menjadi Closed.
- Mengajukan pembukaan ulang tiket yang sudah Resolved apabila masalah belum selesai.

### 5.2 IT Support

Petugas yang menangani kendala IT.

Hak akses:

- Login dan logout.
- Melihat dashboard penanganan.
- Melihat tiket yang ditugaskan kepadanya.
- Melihat tiket baru yang belum ditugaskan.
- Menerima atau mengambil tiket yang belum memiliki petugas.
- Memperbarui status tiket yang menjadi tanggung jawabnya.
- Menambahkan komentar dan lampiran.
- Mengisi catatan penyelesaian.
- Melihat riwayat penanganan tiket yang dapat diaksesnya.

### 5.3 Administrator

Pengelola sistem.

Hak akses:

- Mengakses dashboard keseluruhan.
- Mengelola data pengguna.
- Mengelola kategori masalah.
- Melihat seluruh tiket.
- Menugaskan atau mengubah petugas tiket.
- Memantau status dan riwayat tiket.
- Mengakses laporan dan export.
- Mengelola konfigurasi dasar yang termasuk dalam versi 1.

### 5.4 Matriks Hak Akses

| Fitur              |            Employee |                 IT Support |          Administrator |
| ------------------ | ------------------: | -------------------------: | ---------------------: |
| Dashboard          |       Milik sendiri |                 Penanganan |            Keseluruhan |
| Buat tiket         |                  Ya |                      Tidak | Opsional untuk bantuan |
| Lihat tiket        |       Milik sendiri | Tiket yang dapat ditangani |                  Semua |
| Kelola kategori    |               Tidak |                      Tidak |                     Ya |
| Assign tiket       |               Tidak |       Ambil tiket tersedia |                     Ya |
| Ubah status        | Konfirmasi / reopen |            Ya, sesuai alur |                     Ya |
| Komentar           |       Tiket sendiri |       Tiket yang ditangani |                  Semua |
| Lampiran           |       Tiket sendiri |       Tiket yang ditangani |                  Semua |
| Kelola pengguna    |               Tidak |                      Tidak |                     Ya |
| Laporan dan export |               Tidak |     Terbatas pada tiketnya |                  Semua |

## 6. Kebutuhan Fungsional

### FR-01 — Autentikasi

- Pengguna dapat login menggunakan kredensial yang terdaftar.
- Pengguna dapat logout.
- Halaman dan aksi harus dilindungi autentikasi.
- Hak akses ditentukan berdasarkan role.
- Pengguna tidak boleh mengakses tiket milik pengguna lain tanpa otorisasi.

### FR-02 — Dashboard

Dashboard menampilkan informasi sesuai role.

**Employee:**

- Jumlah tiket milik sendiri.
- Jumlah tiket Open.
- Jumlah tiket In Progress.
- Jumlah tiket Resolved.
- Daftar tiket terbaru.

**IT Support:**

- Jumlah tiket yang ditugaskan.
- Jumlah tiket belum dikerjakan.
- Jumlah tiket In Progress.
- Jumlah tiket Resolved.
- Daftar tiket yang perlu ditangani.

**Administrator:**

- Total tiket.
- Tiket Open.
- Tiket Assigned.
- Tiket In Progress.
- Tiket Resolved.
- Tiket Closed.
- Ringkasan tiket berdasarkan kategori dan periode.
- Daftar tiket terbaru.

### FR-03 — Manajemen Kategori

Administrator dapat:

- Melihat daftar kategori.
- Membuat kategori.
- Mengubah kategori.
- Mengaktifkan atau menonaktifkan kategori.

Ketentuan:

- Nama kategori wajib diisi dan unik.
- Kategori nonaktif tidak dapat dipilih untuk tiket baru.
- Kategori yang sudah digunakan tidak dihapus secara permanen; gunakan status aktif/nonaktif.

Kategori awal:

- Hardware.
- Software.
- Network.
- Printer.
- Account & Access.
- Other.

### FR-04 — Pembuatan Tiket

Employee dapat membuat tiket dengan field berikut:

| Field         | Tipe        | Ketentuan                            |
| ------------- | ----------- | ------------------------------------ |
| Ticket Number | String      | Dibuat otomatis dan unik             |
| Subject       | String      | Wajib, maksimal 150 karakter         |
| Category      | Foreign key | Wajib, kategori aktif                |
| Priority      | Enum        | Low, Medium, High, Critical          |
| Description   | Text        | Wajib                                |
| Attachment    | File        | Opsional, format dan ukuran dibatasi |
| Created By    | Foreign key | Otomatis dari pengguna login         |
| Created At    | Timestamp   | Otomatis                             |

Ketentuan:

- Tiket baru memiliki status `OPEN`.
- Nomor tiket dibuat otomatis dan tidak boleh duplikat.
- Pengguna tidak dapat mengubah pembuat tiket.
- Validasi dilakukan di sisi server.

### FR-05 — Daftar dan Detail Tiket

Daftar tiket menyediakan:

- Nomor tiket.
- Subjek.
- Kategori.
- Prioritas.
- Pembuat.
- Petugas yang ditugaskan.
- Status.
- Tanggal dibuat.
- Pencarian nomor tiket dan subjek.
- Filter status, kategori, prioritas, dan rentang tanggal.
- Pagination dan pengaturan jumlah data per halaman.

Halaman detail tiket menampilkan:

- Informasi utama tiket.
- Informasi pembuat.
- Informasi petugas.
- Status dan prioritas.
- Lampiran.
- Komentar.
- Riwayat penugasan.
- Riwayat perubahan status.
- Catatan penyelesaian.

### FR-06 — Penugasan Tiket

- Administrator dapat menugaskan tiket kepada IT Support.
- IT Support dapat mengambil tiket yang belum ditugaskan.
- Setiap penugasan dicatat dalam riwayat.
- Sistem mencatat petugas, pemberi penugasan, waktu penugasan, dan catatan opsional.
- Penugasan baru harus tercatat tanpa menghilangkan riwayat penugasan sebelumnya.
- Tiket yang sudah ditugaskan berubah ke status `ASSIGNED`.

### FR-07 — Pengelolaan Status Tiket

Status yang digunakan:

| Status      | Deskripsi                                         |
| ----------- | ------------------------------------------------- |
| OPEN        | Tiket baru dibuat                                 |
| ASSIGNED    | Tiket sudah memiliki petugas                      |
| IN_PROGRESS | Tiket sedang ditangani                            |
| RESOLVED    | Petugas menyatakan masalah selesai                |
| CLOSED      | Employee mengonfirmasi penyelesaian               |
| REOPENED    | Tiket dibuka kembali karena masalah belum selesai |

Alur utama:

`OPEN → ASSIGNED → IN_PROGRESS → RESOLVED → CLOSED`

Alur pembukaan ulang:

`RESOLVED → REOPENED → IN_PROGRESS → RESOLVED → CLOSED`

Ketentuan:

- Perubahan status harus mengikuti transisi yang diizinkan.
- Perubahan status dicatat pada `ticket_status_logs`.
- Sistem menyimpan status sebelumnya, status baru, pelaku, waktu, dan catatan.
- Employee hanya dapat mengonfirmasi penutupan atau mengajukan pembukaan ulang sesuai kondisi.
- IT Support hanya dapat mengubah status tiket yang menjadi tanggung jawabnya.
- Administrator dapat melakukan tindakan administratif dengan pencatatan riwayat.

### FR-08 — Komentar Tiket

- Employee dan IT Support dapat berkomunikasi melalui komentar pada tiket yang dapat mereka akses.
- Komentar memiliki isi, penulis, dan waktu pembuatan.
- Komentar tidak dapat diubah oleh pengguna lain.
- Komentar tidak dapat ditambahkan pada tiket Closed.
- Riwayat komentar ditampilkan berdasarkan urutan waktu.

### FR-09 — Lampiran

- Pengguna dapat mengunggah lampiran saat membuat tiket atau menambahkan komentar.
- Sistem menyimpan metadata file pada database dan file pada storage.
- Jenis file yang diperbolehkan: JPG, JPEG, PNG, dan PDF.
- Ukuran maksimum file: 5 MB per file.
- Nama file penyimpanan dibuat aman dan tidak menggunakan nama asli sebagai path.
- Akses unduhan harus melalui pemeriksaan otorisasi.
- File yang tidak valid harus ditolak.

### FR-10 — Penyelesaian Tiket

IT Support dapat menyelesaikan tiket dengan mengisi:

- Catatan penyelesaian.
- Waktu penyelesaian.
- Petugas yang menyelesaikan.

Setelah diselesaikan:

- Status menjadi `RESOLVED`.
- Employee dapat melihat catatan penyelesaian.
- Employee dapat mengonfirmasi menjadi `CLOSED` atau mengajukan pembukaan ulang menjadi `REOPENED`.
- Semua perubahan dicatat dalam riwayat status.

### FR-11 — Laporan dan Export

Administrator dapat melihat laporan tiket dengan filter:

- Rentang tanggal.
- Status.
- Kategori.
- Prioritas.
- IT Support.

Kolom laporan minimal:

- Nomor tiket.
- Subjek.
- Kategori.
- Prioritas.
- Pembuat.
- IT Support.
- Status.
- Tanggal dibuat.
- Tanggal selesai.

Sistem menyediakan export Excel berdasarkan filter yang sedang diterapkan.

### FR-12 — Riwayat Aktivitas

Sistem menyimpan riwayat perubahan status dan penugasan. Setiap catatan memuat aktor, waktu, dan detail perubahan yang relevan. Riwayat ditampilkan pada halaman detail tiket dan tidak dapat diubah melalui antarmuka pengguna biasa.

## 7. Alur Pengguna Utama

### 7.1 Alur Employee

1. Employee login.
2. Employee membuka menu tiket.
3. Employee memilih Buat Tiket.
4. Employee mengisi subjek, kategori, prioritas, deskripsi, dan lampiran opsional.
5. Employee mengirim tiket.
6. Sistem membuat nomor tiket dan menyimpan status `OPEN`.
7. Employee melihat detail dan perkembangan tiket.
8. Employee berkomunikasi dengan IT Support melalui komentar.
9. Employee menerima informasi penyelesaian.
10. Employee mengonfirmasi `CLOSED` atau mengajukan `REOPENED`.

### 7.2 Alur IT Support

1. IT Support login.
2. IT Support membuka daftar tiket.
3. IT Support melihat tiket yang tersedia atau ditugaskan.
4. IT Support mengambil tiket atau menerima penugasan.
5. Sistem mencatat penugasan dan status `ASSIGNED`.
6. IT Support memulai pengerjaan dan mengubah status menjadi `IN_PROGRESS`.
7. IT Support berkomunikasi dengan Employee melalui komentar.
8. IT Support mengisi catatan penyelesaian.
9. Sistem mengubah status menjadi `RESOLVED`.
10. Employee mengonfirmasi atau membuka kembali tiket.

### 7.3 Alur Administrator

1. Administrator login.
2. Administrator memantau dashboard.
3. Administrator mengelola pengguna dan kategori.
4. Administrator melihat seluruh tiket.
5. Administrator menugaskan atau mengubah petugas tiket.
6. Administrator memantau riwayat dan status.
7. Administrator memfilter laporan.
8. Administrator mengunduh laporan Excel.

## 8. Model Data dan Database

Versi 1 menggunakan delapan tabel domain utama. Tabel autentikasi dan permission mengikuti struktur Laravel serta Spatie Laravel Permission.

### 8.1 `users`

Menyimpan pengguna aplikasi.

Kolom minimum:

- `id`
- `name`
- `email` (unique)
- `password`
- `is_active`
- `email_verified_at` (nullable jika verifikasi email digunakan)
- `remember_token`
- `created_at`
- `updated_at`

Role pengguna dikelola melalui tabel Spatie Permission, bukan kolom role duplikat pada `users`.

### 8.2 `categories`

Menyimpan kategori kendala.

Kolom:

- `id`
- `name` (unique)
- `description` (nullable)
- `is_active` (boolean, default true)
- `created_at`
- `updated_at`

### 8.3 `tickets`

Menyimpan data utama tiket.

Kolom:

- `id`
- `ticket_number` (unique)
- `created_by` (FK ke users)
- `category_id` (FK ke categories)
- `subject`
- `description`
- `priority` (low, medium, high, critical)
- `status` (open, assigned, in_progress, resolved, closed, reopened)
- `resolved_at` (nullable)
- `closed_at` (nullable)
- `created_at`
- `updated_at`

### 8.4 `ticket_assignments`

Menyimpan riwayat penugasan tiket.

Kolom:

- `id`
- `ticket_id` (FK ke tickets)
- `assigned_to` (FK ke users)
- `assigned_by` (FK ke users)
- `assigned_at`
- `unassigned_at` (nullable)
- `notes` (nullable)
- `created_at`
- `updated_at`

Penugasan aktif ditentukan oleh record yang belum memiliki `unassigned_at`. Aturan aplikasi harus mencegah lebih dari satu penugasan aktif untuk tiket yang sama.

### 8.5 `ticket_comments`

Menyimpan komentar pada tiket.

Kolom:

- `id`
- `ticket_id` (FK ke tickets)
- `user_id` (FK ke users)
- `body`
- `created_at`
- `updated_at`

### 8.6 `ticket_attachments`

Menyimpan metadata lampiran.

Kolom:

- `id`
- `ticket_id` (FK ke tickets)
- `comment_id` (nullable FK ke ticket_comments)
- `uploaded_by` (FK ke users)
- `original_name`
- `file_path`
- `mime_type`
- `file_size`
- `created_at`
- `updated_at`

Lampiran yang terkait langsung dengan tiket memiliki `comment_id` null. Lampiran komentar menggunakan `comment_id`.

### 8.7 `ticket_status_logs`

Menyimpan riwayat status tiket.

Kolom:

- `id`
- `ticket_id` (FK ke tickets)
- `changed_by` (FK ke users)
- `old_status` (nullable)
- `new_status`
- `notes` (nullable)
- `created_at`

### 8.8 Tabel Permission Spatie

Gunakan migration standar Spatie Laravel Permission untuk:

- `roles`
- `permissions`
- `model_has_roles`
- `model_has_permissions`
- `role_has_permissions`

Tabel bawaan Laravel untuk cache, session, queue, dan autentikasi dapat digunakan sesuai konfigurasi proyek.

### 8.9 Relasi Eloquent

- User hasMany tickets melalui `created_by`.
- Category hasMany tickets.
- Ticket belongsTo creator (User).
- Ticket belongsTo category.
- Ticket hasMany assignments.
- Ticket hasMany comments.
- Ticket hasMany attachments.
- Ticket hasMany status logs.
- TicketAssignment belongsTo ticket.
- TicketAssignment belongsTo assigned user.
- TicketAssignment belongsTo assigning user.
- TicketComment belongsTo ticket.
- TicketComment belongsTo user.
- TicketAttachment belongsTo ticket.
- TicketAttachment belongsTo comment (nullable).
- TicketAttachment belongsTo uploader (User).
- TicketStatusLog belongsTo ticket.
- TicketStatusLog belongsTo actor (User).

Seluruh foreign key wajib memiliki constraint. Penghapusan data historis harus mempertimbangkan integritas audit; tiket yang memiliki riwayat tidak boleh dihapus permanen melalui antarmuka biasa.

## 9. Aturan Bisnis

1. Setiap tiket memiliki nomor unik yang dibuat sistem.
2. Setiap tiket dibuat oleh satu Employee.
3. Setiap tiket harus memiliki satu kategori aktif saat dibuat.
4. Setiap tiket memiliki satu prioritas.
5. Tiket baru selalu berstatus `OPEN`.
6. Tiket hanya boleh memiliki satu penugasan aktif.
7. Perubahan penugasan harus mempertahankan riwayat penugasan sebelumnya.
8. Hanya petugas yang berwenang atau Administrator yang dapat mengubah penanganan tiket.
9. Employee hanya dapat melihat tiket miliknya.
10. IT Support hanya dapat mengakses tiket sesuai hak akses penanganannya dan tiket yang tersedia untuk diambil.
11. Tiket `CLOSED` tidak menerima komentar atau perubahan penanganan normal.
12. Pembukaan ulang hanya dapat dilakukan pada tiket `RESOLVED` sesuai otorisasi.
13. Semua perubahan status harus dicatat.
14. Semua waktu disimpan dan ditampilkan secara konsisten menggunakan timezone aplikasi.
15. Penghapusan kategori yang telah digunakan dilakukan dengan menonaktifkan kategori.

## 10. Kebutuhan Non-Fungsional

### NFR-01 — Keamanan

- Password disimpan menggunakan hashing Laravel.
- Semua halaman internal memerlukan autentikasi.
- Authorization diterapkan pada setiap aksi dan akses data, bukan hanya menyembunyikan menu.
- Validasi file dilakukan berdasarkan MIME/type dan ukuran.
- File privat tidak boleh diakses melalui URL publik tanpa otorisasi.
- CSRF protection dan proteksi bawaan Laravel tetap aktif.
- Data sensitif tidak ditampilkan pada log aplikasi.

### NFR-02 — Kinerja

- Daftar tiket menggunakan pagination.
- Query relasi menggunakan eager loading bila diperlukan.
- Filter dan pencarian dilakukan melalui query database.
- Kolom pencarian dan foreign key penting diberi index yang sesuai.
- Dashboard menggunakan agregasi database, bukan memuat seluruh tiket ke memori aplikasi.

### NFR-03 — Usability

- Antarmuka responsif untuk desktop dan perangkat mobile.
- Navigasi konsisten berdasarkan role.
- Form menampilkan validasi yang jelas.
- Status dan prioritas ditampilkan secara konsisten.
- Pengguna memperoleh umpan balik ketika aksi berhasil atau gagal.

### NFR-04 — Maintainability

- Mengikuti konvensi Laravel.
- Menggunakan migration, model, relationship, dan validation.
- Logika bisnis tidak ditempatkan seluruhnya di Blade.
- Penamaan class, method, route, dan database konsisten.
- Source code dikelola melalui Git.

## 11. Teknologi dan Standar Implementasi

| Komponen        | Teknologi                                                |
| --------------- | -------------------------------------------------------- |
| Backend         | Laravel                                                  |
| UI reaktif      | Livewire 3, class-based components                       |
| Frontend        | Blade biasa (server-rendered), Tailwind CSS              |
| Database        | MySQL / MariaDB                                          |
| Authentication  | Laravel Authentication                                   |
| Authorization   | Spatie Laravel Permission                                |
| Export          | Laravel Excel atau mekanisme export XLSX yang disepakati |
| Version Control | Git                                                      |
| Testing         | PHPUnit atau Pest                                        |

Ketentuan implementasi:

- Gunakan Blade untuk view.
- Gunakan Form Request atau validasi Livewire sesuai kebutuhan.
- Gunakan Eloquent Relationship untuk akses relasi.
- Gunakan database transaction untuk operasi yang mengubah tiket, penugasan, dan riwayat.
- Gunakan policy/gate atau pemeriksaan authorization yang konsisten.
- Hindari penggunaan query mentah jika Eloquent atau Query Builder memadai.
- Jangan menyimpan file upload di direktori publik apabila lampiran bersifat internal.

## 12. Rencana Pengerjaan

Target pengembangan adalah 20 hari kerja.

| Minggu | Hari | Pekerjaan                        | Output                |
| ------ | ---: | -------------------------------- | --------------------- |
| 1      |    1 | Analisis kebutuhan dan user flow | Dokumen kebutuhan     |
| 1      |    2 | ERD dan rancangan database       | ERD                   |
| 1      |    3 | Setup proyek dan autentikasi     | Fondasi aplikasi      |
| 1      |    4 | Migration, model, relationship   | Struktur database     |
| 1      |    5 | Role dan permission              | Hak akses             |
| 2      |    6 | CRUD kategori                    | Modul kategori        |
| 2      |    7 | Form pembuatan tiket             | Create ticket         |
| 2      |    8 | Daftar dan detail tiket          | Ticket listing/detail |
| 2      |    9 | Penugasan IT Support             | Assignment            |
| 2      |   10 | Perubahan status tiket           | Status workflow       |
| 3      |   11 | Komentar tiket                   | Ticket comments       |
| 3      |   12 | Upload lampiran                  | Attachment            |
| 3      |   13 | Penyelesaian dan reopen          | Resolution workflow   |
| 3      |   14 | Dashboard statistik              | Dashboard             |
| 3      |   15 | Pencarian dan filter             | Ticket filtering      |
| 4      |   16 | Laporan dan export Excel         | Report                |
| 4      |   17 | Pengujian semua role             | Test result           |
| 4      |   18 | Perbaikan bug                    | Stabilization         |
| 4      |   19 | Dokumentasi dan user manual      | Dokumen               |
| 4      |   20 | Demo dan presentasi              | Final delivery        |

## 13. Pembagian Tugas Tim

### Jika terdiri dari 1 peserta

Peserta mengerjakan seluruh modul berdasarkan urutan rencana pengerjaan 20 hari. Pembimbing melakukan review berkala pada akhir setiap minggu.

## 14. Acceptance Criteria dan Definition of Done

Fitur dinyatakan selesai jika:

1. Fungsi berjalan sesuai kebutuhan.
2. Validasi input diterapkan.
3. Authorization diterapkan pada aksi dan data.
4. Tidak terdapat error yang menghambat alur utama.
5. Tampilan dapat digunakan pada desktop dan mobile.
6. Perubahan kode telah tersimpan di Git.
7. Fitur telah diuji dan hasilnya dicatat.

### Checklist Penerimaan Produk

| ID    | Kriteria          | Hasil yang Diharapkan                             |
| ----- | ----------------- | ------------------------------------------------- |
| AC-01 | Login dan logout  | Pengguna dapat masuk dan keluar                   |
| AC-02 | Role access       | Akses sesuai role                                 |
| AC-03 | Kategori          | Admin dapat mengelola kategori                    |
| AC-04 | Create ticket     | Employee dapat membuat tiket                      |
| AC-05 | Ticket number     | Nomor tiket unik dan otomatis                     |
| AC-06 | Ticket visibility | Employee hanya melihat tiket miliknya             |
| AC-07 | Assignment        | Tiket dapat ditugaskan kepada IT Support          |
| AC-08 | Status workflow   | Transisi status sesuai aturan                     |
| AC-09 | Comments          | Komentar tersimpan dan tampil                     |
| AC-10 | Attachments       | File valid dapat diunggah dan diakses sesuai izin |
| AC-11 | Resolution        | Petugas dapat menyelesaikan tiket                 |
| AC-12 | Reopen            | Tiket Resolved dapat dibuka kembali sesuai izin   |
| AC-13 | Dashboard         | Statistik sesuai role dan data                    |
| AC-14 | Search/filter     | Hasil sesuai kriteria filter                      |
| AC-15 | Export            | File Excel sesuai filter                          |
| AC-16 | Audit history     | Perubahan status dan penugasan tercatat           |
| AC-17 | Responsive UI     | Halaman utama dapat digunakan di mobile           |
| AC-18 | Documentation     | Dokumen teknis dan user manual tersedia           |

Produk dinyatakan memenuhi target magang apabila seluruh kriteria wajib di atas lulus pengujian dan alur Employee → IT Support → Employee dapat didemonstrasikan tanpa kendala kritis.

## 15. Strategi Pengujian

Pengujian minimal mencakup:

### 15.1 Authentication dan Authorization

- Login dengan kredensial valid dan tidak valid.
- Employee mencoba membuka tiket milik pengguna lain.
- IT Support mencoba mengakses aksi yang tidak diizinkan.
- Pengguna tanpa role mencoba membuka halaman terproteksi.

### 15.2 Ticket Management

- Membuat tiket dengan data valid.
- Mengirim form kosong.
- Menggunakan kategori nonaktif.
- Memastikan nomor tiket tidak duplikat.
- Mencari dan memfilter tiket.

### 15.3 Assignment dan Workflow

- Assign tiket ke IT Support.
- Mencegah penugasan aktif ganda.
- Mengubah status sesuai transisi.
- Menolak transisi status yang tidak valid.
- Memastikan riwayat status dan penugasan tersimpan.

### 15.4 Comments dan Attachments

- Menambahkan komentar.
- Menolak komentar kosong.
- Mengunggah file yang valid.
- Menolak file dengan format atau ukuran tidak sesuai.
- Memastikan pengguna tidak dapat mengakses lampiran tanpa izin.

### 15.5 Resolution dan Reporting

- Menyelesaikan tiket.
- Mengonfirmasi penutupan.
- Membuka kembali tiket Resolved.
- Memeriksa hasil filter laporan.
- Mengunduh dan memeriksa file Excel.

## 16. Deliverables

Peserta wajib menyerahkan:

1. Source code aplikasi pada repository Git.
2. File migration dan seeder.
3. Dokumen PRD.
4. ERD.
5. Dokumentasi struktur database dan relasi.
6. Dokumentasi user flow.
7. Test case dan hasil pengujian.
8. User manual.
9. README berisi instalasi dan konfigurasi.
10. Laporan akhir magang.
11. Presentasi dan demonstrasi aplikasi.

## 17. Sistem Evaluasi Peserta

| Aspek Penilaian                        |    Bobot |
| -------------------------------------- | -------: |
| Analisis kebutuhan dan database design |      15% |
| Implementasi backend Laravel           |      25% |
| Implementasi frontend Livewire         |      20% |
| Business logic dan workflow            |      15% |
| Testing dan debugging                  |      15% |
| Dokumentasi dan presentasi             |      10% |
| **Total**                              | **100%** |

Penilaian dilakukan berdasarkan hasil yang dapat diperiksa, kualitas implementasi, pemahaman peserta terhadap kode, dan kemampuan menjelaskan alur aplikasi.

## 18. Risiko dan Batasan Proyek

- Durasi satu bulan membatasi pengembangan pada fitur wajib versi 1.
- Perubahan kebutuhan di luar ruang lingkup harus mendapat persetujuan pembimbing.
- Integrasi layanan eksternal tidak termasuk target.
- Data demonstrasi harus menggunakan data dummy, bukan data operasional atau informasi sensitif perusahaan.
- Pengujian dilakukan pada lingkungan development/staging.
- Fitur yang belum selesai harus dicatat secara transparan dalam laporan akhir.

## 19. Asumsi dan Keputusan yang Perlu Dikonfirmasi

Sebelum implementasi, pembimbing menetapkan:

- Versi Laravel dan PHP yang digunakan.
- Mekanisme pembuatan akun Employee.
- Apakah Employee dapat membuat tiket atas nama karyawan lain.
- Apakah IT Support dapat mengambil tiket sendiri atau hanya menerima assignment.
- Apakah Administrator boleh mengubah seluruh status tiket.
- Kebijakan penyimpanan dan retensi lampiran.
- Format dan library export Excel yang digunakan.
- Lingkungan deployment untuk demonstrasi.

Keputusan tersebut harus dicatat sebelum fitur terkait dikembangkan.

---

**Akhir Dokumen PRD — IT Helpdesk Management System v1.0**
