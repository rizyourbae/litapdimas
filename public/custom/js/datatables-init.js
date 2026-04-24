/**
 * DataTables Init Helpers
 * =======================
 * Wrapper untuk inisialisasi DataTables dengan Bootstrap 5 dan bahasa Indonesia.
 *
 * Menyediakan:
 * - DtManager  : manajemen instance (lazy init, avoid double-init)
 * - Skeleton   : animasi skeleton sebelum DT siap
 *
 * Syarat: jQuery + DataTables + DataTables Bootstrap 5 plugin sudah di-load.
 *
 * Pola penggunaan:
 *   // Inisialisasi tabel langsung
 *   DtManager.init('dt-profesi', { columnDefs: [...] });
 *
 *   // Inisialisasi lazy (untuk tabel di dalam tabs)
 *   DtManager.initLazy('dt-profesi', {}, 'sk-profesi', 'rw-profesi');
 *
 *   // Adjust kolom setelah tab ditampilkan
 *   DtManager.adjust('dt-profesi');
 */

(function () {
  "use strict";

  if (typeof $.fn === "undefined" || !$.fn.DataTable) {
    console.warn("[datatables-init.js] jQuery atau DataTables belum ter-load.");
    return;
  }

  // =========================================================
  // Bahasa Indonesia
  // =========================================================
  const ID_LANG = {
    decimal: ",",
    thousands: ".",
    lengthMenu: "Tampilkan _MENU_ entri",
    zeroRecords: "Data tidak ditemukan",
    emptyTable: "Tidak ada data yang tersedia",
    info: "_START_–_END_ dari _TOTAL_ data",
    infoEmpty: "Tidak ada data",
    infoFiltered: "(difilter dari _MAX_ total data)",
    loadingRecords: "Memuat...",
    processing: "Memproses...",
    search: "",
    searchPlaceholder: "Cari...",
    paginate: {
      first: "«",
      last: "»",
      next: "›",
      previous: "‹",
    },
  };

  // =========================================================
  // Default options
  // =========================================================
  const DEFAULT_OPTIONS = {
    language: ID_LANG,
    autoWidth: false, // wajib false untuk tabel di dalam tab/card
    pageLength: 10,
    order: [], // jangan sort otomatis
    columnDefs: [
      { orderable: false, targets: -1 }, // kolom terakhir (Aksi) tidak sortable
    ],
  };

  // =========================================================
  // Instance registry (untuk mencegah double-init)
  // =========================================================
  const _instances = {};

  // =========================================================
  // Skeleton helpers
  // =========================================================

  /**
   * Sembunyikan skeleton overlay dan tampilkan tabel asli.
   * @param {string} skeletonId  - ID elemen skeleton overlay
   * @param {string} realWrapId  - ID elemen wrapper tabel asli
   */
  function revealTable(skeletonId, realWrapId) {
    const sk = document.getElementById(skeletonId);
    const rw = document.getElementById(realWrapId);

    if (sk) {
      sk.classList.add("done");
      setTimeout(function () {
        sk.remove();
      }, 350);
    }
    if (rw) {
      rw.classList.add("ready");
    }
  }

  /**
   * Buat baris skeleton HTML untuk overlay tabel.
   * @param {number}   rows     - Jumlah baris skeleton
   * @param {number[]} widths   - Array persentase lebar teks tiap kolom data
   * @param {boolean}  [hasBadge=false] - Apakah ada kolom badge (kolom kedua berisi badge)
   * @returns {string} HTML string
   */
  function buildSkeletonRows(rows, widths, hasBadge) {
    let html = "";
    for (let i = 0; i < rows; i++) {
      html += "<tr>";
      // Kolom nomor
      html += `<td class="text-center"><span class="skeleton-line mx-auto" style="width:24px"></span></td>`;
      // Kolom data utama
      widths.forEach(function (w, idx) {
        if (hasBadge && idx > 0) {
          // badge-like skeleton
          html += `<td><span class="skeleton-line" style="width:${w}%;border-radius:20px;height:20px"></span></td>`;
        } else {
          html += `<td><span class="skeleton-line" style="width:${w}%"></span></td>`;
        }
      });
      // Kolom aksi
      html += `<td class="text-center"><span class="skeleton-btn me-1"></span><span class="skeleton-btn"></span></td>`;
      html += "</tr>";
    }
    return html;
  }

  // =========================================================
  // Public API
  // =========================================================
  window.DtManager = {
    /**
     * Inisialisasi DataTable.
     * @param {string} tableId   - ID elemen <table>
     * @param {Object} [options] - Override default options
     * @returns {DataTable|null}
     */
    init: function (tableId, options) {
      if (_instances[tableId]) return _instances[tableId];

      const el = document.getElementById(tableId);
      if (!el) return null;

      const opts = Object.assign({}, DEFAULT_OPTIONS, options || {});
      // Merge columnDefs jika ada override
      if (options && options.columnDefs) {
        opts.columnDefs = options.columnDefs;
      }

      _instances[tableId] = new DataTable("#" + tableId, opts);
      return _instances[tableId];
    },

    /**
     * Inisialisasi DataTable + dismiss skeleton setelah siap.
     * @param {string} tableId
     * @param {Object} [options]
     * @param {string} [skeletonId]  - ID elemen skeleton overlay
     * @param {string} [realWrapId]  - ID elemen wrapper tabel asli
     * @returns {DataTable|null}
     */
    initLazy: function (tableId, options, skeletonId, realWrapId) {
      if (_instances[tableId]) {
        revealTable(skeletonId, realWrapId);
        return _instances[tableId];
      }

      const userInitComplete = options && options.initComplete;

      const opts = Object.assign({}, DEFAULT_OPTIONS, options || {}, {
        initComplete: function () {
          revealTable(skeletonId, realWrapId);
          if (userInitComplete) userInitComplete.call(this);
        },
      });
      if (options && options.columnDefs) {
        opts.columnDefs = options.columnDefs;
      }

      const el = document.getElementById(tableId);
      if (!el) {
        revealTable(skeletonId, realWrapId);
        return null;
      }

      _instances[tableId] = new DataTable("#" + tableId, opts);
      return _instances[tableId];
    },

    /**
     * Adjust kolom (perlu dipanggil setelah tab ditampilkan).
     * @param {string} tableId
     */
    adjust: function (tableId) {
      if (_instances[tableId]) {
        _instances[tableId].columns.adjust();
      }
    },

    /**
     * Dapatkan instance yang sudah dibuat.
     * @param {string} tableId
     * @returns {DataTable|undefined}
     */
    get: function (tableId) {
      return _instances[tableId];
    },

    /**
     * Bantu build HTML skeleton tabel (untuk dirender server-side via PHP).
     * Versi JS ini bisa digunakan untuk fallback.
     */
    buildSkeletonRows: buildSkeletonRows,
  };
})();
