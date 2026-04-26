# Catatan Lanjutan Implementasi - Proposal Dosen Wizard

Tanggal: 2026-04-27
Status: Siap eksekusi implementasi

## Ringkasan Tujuan
Membangun fitur Pengajuan Proposal untuk role Dosen dalam bentuk wizard 5 langkah (stepper) sesuai screenshot:
1. Pernyataan Peneliti
2. Data Peneliti
3. Substansi Usulan
4. Unggah Berkas
5. Data Jurnal

Target kualitas:
- SOLID
- DRY
- Zero Logic In View (tanpa logic bisnis PHP/JS di view)
- Scalable
- Maintainable

## Keputusan Teknis yang Sudah Disepakati
- Dropdown baru dibuat sebagai master table (bukan hardcoded):
  - Pengelola Bantuan
  - Jenis Penelitian
  - Kontribusi Prodi
- Penyimpanan draft per step saat klik Selanjutnya
- Rich text editor menggunakan Quill
- Ada halaman Review ringkasan sebelum submit final
- Fokus scope untuk role Dosen terlebih dahulu

## Mapping Kebutuhan Per Step

### Step 1 - Pernyataan Peneliti
Field utama:
- Judul usulan
- Kata kunci (minimal 3 kata, dipisah koma)
- Pengelola bantuan
- Klaster bantuan
- Bidang ilmu
- Tema
- Jenis penelitian
- Kontribusi keilmuan prodi
- Checklist pernyataan wajib (semua harus dicentang)

Validasi:
- Semua field bertanda wajib harus terisi
- Pernyataan wajib harus lengkap

### Step 2 - Data Peneliti
Ketentuan:
- Peneliti Utama (Ketua) terisi otomatis dari akun pengusul (users + user_profiles)
- Data Peneliti internal: repeatable, minimal 1 item wajib
- Data Mahasiswa: repeatable, opsional
- Anggota PTU/Profesional: repeatable, opsional

Validasi:
- Minimal 1 data peneliti internal
- Data ketua tidak boleh kosong

### Step 3 - Substansi Usulan
Komponen:
- Abstrak (rich text Quill)
- Isi substansi proposal berupa section repeatable:
  - Judul bagian
  - Isi bagian (rich text Quill)
- Tombol tambah/hapus section substansi

Validasi:
- Abstrak wajib
- Minimal 1 section substansi lengkap (judul + isi)

### Step 4 - Unggah Berkas
Dokumen wajib:
- File Proposal
- File RAB
- Hasil Cek Similarity

Dokumen fleksibel:
- Dokumen pendukung (dapat ditambah lebih dari satu)
- Ada keterangan dokumen

Validasi:
- File PDF
- Maksimal 2MB per file
- 3 dokumen wajib harus ada

### Step 5 - Data Jurnal
Field:
- ISSN Jurnal
- Nama/Profil Jurnal (rich text)
- URL Website Jurnal
- URL Jurnal di Scopus/WoS/Scimago
- URL Surat Rekomendasi Institusi
- Total Pengajuan Dana (maks Rp 100.000.000)

Validasi:
- Semua field wajib terisi
- URL valid
- Dana <= 100000000

## Rancangan Arsitektur Implementasi

### Routing
Tambahkan route group dosen proposal untuk:
- index
- create (init draft)
- step 1..5 (GET form + POST save)
- review
- submit final
- show detail

### Controller (Thin Controller)
Controller hanya:
- Ambil request
- Delegasi ke service
- Redirect + flash message

Semua business logic dipindah ke service.

### Service Layer
Service utama:
- ProposalWizardService (orkestrasi step, save draft, gating, submit final)

Service pendukung:
- ProposalMasterOptionService (opsi dropdown)
- ProposalStepValidationService (validasi per step)
- ProposalUploadService (upload file, validasi MIME/size, cleanup)

### Model & Database
Master baru:
- proposal_pengelola_bantuan
- proposal_jenis_penelitian
- proposal_kontribusi_prodi

Tabel transaksi wizard:
- proposal_pengajuan (header + status + current_step)
- proposal_peneliti
- proposal_mahasiswa
- proposal_anggota_eksternal
- proposal_substansi_bagian
- proposal_dokumen
- proposal_jurnal

### View
Struktur modular per step:
- layout wizard
- partial step 1..5
- review page
- index page

Catatan penting:
- Tidak ada JS inline di view
- Tidak ada logic bisnis di view

### Frontend Assets
JS terpisah:
- proposal-wizard.js (state stepper)
- proposal-repeatable.js (add/remove row)
- proposal-quill.js (init editor)
- proposal-upload.js (file upload handling)

CSS terpisah:
- proposal-wizard.css
- proposal-form.css

## Aturan Bisnis Penting
- Tidak boleh loncat step yang belum valid
- Draft tersimpan per step
- Submit final melakukan revalidasi semua step secara atomik
- Setelah submit final, status menjadi submitted
- Default: data dikunci setelah submit (bisa dibuka lagi jika ada kebijakan revisi)

## Checklist Eksekusi (Saat Lanjut di Kantor)
1. Buat migration master baru (3 tabel)
2. Buat migration tabel transaksi wizard
3. Buat model untuk seluruh tabel baru
4. Buat service validasi + orchestrator + upload
5. Buat controller dosen proposal
6. Tambahkan routes dosen proposal
7. Buat view modular step 1..5 + review
8. Tambahkan JS/CSS modular proposal
9. Integrasi Quill
10. Uji end-to-end (draft, repeatable, upload, review, submit)
11. Cek error/lint dan bereskan

## Catatan Referensi Internal Proyek
Komponen yang sudah ada dan bisa dijadikan pola:
- Pola thin controller dosen publikasi
- Pola service-centric business logic
- Master data proposal yang sudah ada: bidang ilmu, klaster bantuan, tema penelitian
- Pola JS modular di custom js
- Pola route group dosen dengan auth filter

## Penutup
Semua requirement dari screenshot sudah terpetakan ke desain implementasi.
Tahap berikutnya adalah coding bertahap mulai dari migration + model + service, lalu controller, view, dan assets JS/CSS.
