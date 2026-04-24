# Custom JavaScript Utilities

Folder ini berisi file-file JavaScript universal yang dapat digunakan di berbagai halaman untuk fungsi-fungsi umum.

## File-file yang tersedia

### 1. **datepicker.js** - Universal Datepicker Handler

Library untuk inisialisasi datepicker menggunakan **Flatpickr** secara otomatis.

#### Persyaratan:

- Flatpickr library harus sudah di-load sebelum file ini
- Locale: `id.js` (untuk bahasa Indonesia)

#### Cara Penggunaan:

Tambahkan class `datepicker` dan data attributes ke input:

```html
<input type="text" name="tanggal_lahir" class="form-control datepicker" data-locale="id" data-date-format="Y-m-d" data-alt-format="d F Y" data-max-date="today" placeholder="Pilih tanggal" />
```

#### Data Attributes (opsional):

| Atribut            | Nilai                    | Keterangan                                |
| ------------------ | ------------------------ | ----------------------------------------- |
| `data-locale`      | `id`, `en`, dll          | Bahasa/locale. Default: tidak ada         |
| `data-date-format` | `Y-m-d`, `d/m/Y`, dll    | Format disimpan ke form. Default: `Y-m-d` |
| `data-alt-format`  | `d F Y`, `D, d M Y`, dll | Format ditampilkan ke user (alt input)    |
| `data-max-date`    | `today`, `2025-12-31`    | Tanggal maksimal yang bisa dipilih        |
| `data-min-date`    | `today`, `2020-01-01`    | Tanggal minimal yang bisa dipilih         |

#### Contoh Penggunaan:

```html
<!-- Tanggal lahir (max hari ini) -->
<input type="text" name="birth_date" class="datepicker" data-locale="id" data-date-format="Y-m-d" data-alt-format="d F Y" data-max-date="today" />

<!-- Tanggal event (custom range) -->
<input type="text" name="event_date" class="datepicker" data-locale="id" data-min-date="2025-01-01" data-max-date="2025-12-31" />

<!-- Tanggal resume (format kustom) -->
<input type="text" name="resume_date" class="datepicker" data-date-format="d/m/Y" data-alt-format="D, d MMM Y" />
```

#### Format Kode:

- `Y` = 4-digit year (2025)
- `y` = 2-digit year (25)
- `m` = 2-digit month (01)
- `d` = 2-digit day (15)
- `F` = Full month name (Januari, Februari)
- `M` = Short month name (Jan, Feb)
- `D` = Day name (Senin, Selasa)

**Dokumentasi lengkap Flatpickr:** https://flatpickr.js.org/

---

### 2. **cascade-select.js** - Cascading Select Handler

Library untuk membuat select yang saling bergantung (cascading). Ketika parent select berubah, child select akan di-filter.

#### Cara Penggunaan:

**Parent Select:**

```html
<select id="parentSelect" class="cascade-parent" data-cascade-target="#childSelect">
  <option value="">-- Pilih --</option>
  <option value="10">Kategori A</option>
  <option value="20">Kategori B</option>
</select>
```

**Child Select:**

```html
<select id="childSelect" class="cascade-child">
  <option value="">-- Pilih --</option>
  <option value="1" data-parent="10">Item 1.1 (Parent 10)</option>
  <option value="2" data-parent="10">Item 1.2 (Parent 10)</option>
  <option value="3" data-parent="20">Item 2.1 (Parent 20)</option>
</select>
```

#### Atribut yang digunakan:

| Atribut                  | Elemen          | Keterangan                                                |
| ------------------------ | --------------- | --------------------------------------------------------- |
| `class="cascade-parent"` | Parent Select   | Menandai ini sebagai parent trigger                       |
| `data-cascade-target`    | Parent Select   | CSS selector dari child select yang akan di-filter        |
| `class="cascade-child"`  | Child Select    | Menandai ini sebagai child yang akan di-filter (opsional) |
| `data-parent`            | Option di Child | Nilai parent yang akan menampilkan option ini             |

#### Contoh Penggunaan:

```html
<!-- Cascading: Fakultas → Program Studi -->
<select name="fakultas_id" class="cascade-parent" data-cascade-target="#prodiSelect">
  <option value="">-- Pilih Fakultas --</option>
  <option value="1">Fakultas Teknik</option>
  <option value="2">Fakultas Ilmu Komputer</option>
</select>

<select id="prodiSelect" name="program_studi_id" class="cascade-child">
  <option value="">-- Pilih Program Studi --</option>
  <option value="101" data-parent="1">Teknik Sipil (Teknik)</option>
  <option value="102" data-parent="1">Teknik Mesin (Teknik)</option>
  <option value="201" data-parent="2">Informatika (Ilmu Komputer)</option>
  <option value="202" data-parent="2">Sistem Informasi (Ilmu Komputer)</option>
</select>
```

#### Behavior:

1. Ketika user memilih parent value, child select akan menampilkan hanya option yang `data-parent` sesuai
2. Option dengan `value=""` selalu tampil (-- Pilih --)
3. Jika child select memiliki nilai yang tidak sesuai parent, nilai child akan direset

#### Akses Manual:

```javascript
// Inisialisasi ulang (jika ada select baru yang ditambah dinamis)
CascadeManager.init();

// Filter manual untuk parent dan child tertentu
const parentEl = document.getElementById("parentSelect");
const childEl = document.getElementById("childSelect");
CascadeManager.filter(parentEl, childEl);
```

---

## Load di View

### Untuk Flatpickr + Datepicker:

```php
<?= $this->section('scripts') ?>
<!-- Load Flatpickr Library -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/id.js"></script>

<!-- Load Custom Datepicker Manager -->
<script src="<?= base_url('custom/js/datepicker.js') ?>"></script>
<?= $this->endSection() ?>
```

### Untuk Cascade Select:

```php
<?= $this->section('scripts') ?>
<script src="<?= base_url('custom/js/cascade-select.js') ?>"></script>
<?= $this->endSection() ?>
```

### Untuk Keduanya:

```php
<?= $this->section('scripts') ?>
<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/id.js"></script>

<!-- Custom JS -->
<script src="<?= base_url('custom/js/datepicker.js') ?>"></script>
<script src="<?= base_url('custom/js/cascade-select.js') ?>"></script>
<?= $this->endSection() ?>
```

---

## Tips

- **Inisialisasi Otomatis**: Kedua library akan otomatis mencari elemen saat DOM loaded
- **Multiple Cascades**: Bisa menggunakan multiple cascade pairs di satu halaman
- **Multiple Datepickers**: Bisa menggunakan multiple datepicker di satu halaman
- **Dynamic Elements**: Jika menambah elemen baru via AJAX, panggil:
  - `DatepickerManager.init()` untuk datepicker baru
  - `CascadeManager.init()` untuk cascade baru
