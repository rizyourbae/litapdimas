/**
 * Select2 Init Helpers
 * ====================
 * Wrapper untuk inisialisasi Select2 dengan tema Bootstrap 5.
 *
 * Syarat: jQuery + Select2 + select2-bootstrap-5-theme sudah di-load.
 *
 * Pola penggunaan:
 *   // Inisialisasi semua <select data-select2> di halaman
 *   Select2Init.init();
 *
 *   // Inisialisasi semua select di dalam modal (panggil saat modal 'show.bs.modal')
 *   Select2Init.initModal(modalElement);
 *
 *   // Set nilai Select2 secara programatik
 *   Select2Init.setValue('#mySelect', 42);
 */

(function () {
  "use strict";

  if (typeof $ === "undefined" || !$.fn.select2) {
    console.warn("[select2-init.js] jQuery atau Select2 belum ter-load.");
    return;
  }

  // =========================================================
  // Default config
  // =========================================================
  const DEFAULT_OPTIONS = {
    theme: "bootstrap-5",
    width: "100%",
    allowClear: false,
    language: {
      noResults: function () {
        return "Data tidak ditemukan";
      },
      searching: function () {
        return "Mencari...";
      },
    },
  };

  // =========================================================
  // Public API
  // =========================================================
  window.Select2Init = {
    /**
     * Inisialisasi semua `<select data-select2>` di dalam kontainer.
     * @param {HTMLElement|string} [context=document] - Kontainer pencarian
     */
    init: function (context) {
      const $ctx = context ? $(context) : $(document);
      $ctx.find("select[data-select2]").each(function () {
        if ($(this).data("select2")) return; // sudah di-init
        const opts = Object.assign({}, DEFAULT_OPTIONS);
        $(this).select2(opts);
      });
    },

    /**
     * Inisialisasi semua `<select>` (tanpa atribut khusus) di dalam modal.
     * dropdownParent diset ke modal agar dropdown muncul di depan overlay.
     * @param {HTMLElement} modalEl - Elemen modal DOM
     */
    initModal: function (modalEl) {
      $(modalEl)
        .find("select")
        .each(function () {
          if ($(this).data("select2")) return; // sudah di-init
          const opts = Object.assign({}, DEFAULT_OPTIONS, {
            dropdownParent: $(modalEl),
          });
          $(this).select2(opts);
        });
    },

    /**
     * Set nilai select2 secara programatik dan trigger change.
     * @param {string|HTMLElement} selector
     * @param {string|number}      value
     */
    setValue: function (selector, value) {
      const $el = $(selector);
      if ($el.data("select2")) {
        $el.val(value).trigger("change");
      } else {
        $el.val(value);
      }
    },

    /**
     * Destroy semua select2 di dalam kontainer (berguna saat modal di-close).
     * @param {HTMLElement} context
     */
    destroy: function (context) {
      $(context)
        .find("select")
        .each(function () {
          if ($(this).data("select2")) {
            $(this).select2("destroy");
          }
        });
    },
  };

  // =========================================================
  // Auto-init elemen global ([data-select2] tanpa modal) saat DOM ready
  // =========================================================
  document.addEventListener("DOMContentLoaded", function () {
    console.log("[select2-init.js] Initializing Select2 on DOMContentLoaded");
    const selectElements = document.querySelectorAll("select[data-select2]");
    console.log("[select2-init.js] Found " + selectElements.length + " select[data-select2] elements");

    Select2Init.init();

    selectElements.forEach(function (el, idx) {
      console.log("[select2-init.js] Select2 #" + idx + ":", el.name, "initialized:", $(el).data("select2") ? "YES" : "NO");
    });

    // Handle Bootstrap tabs - reinit Select2 ketika tab ditampilkan
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabButtons.forEach(function (tabBtn) {
      tabBtn.addEventListener("show.bs.tab", function (e) {
        const targetPane = document.querySelector(this.getAttribute("data-bs-target"));
        if (targetPane) {
          console.log("[select2-init.js] Tab shown, reinitializing Select2 in:", this.getAttribute("data-bs-target"));
          setTimeout(function () {
            Select2Init.init(targetPane);
          }, 50);
        }
      });
    });

    console.log("[select2-init.js] Initialization complete");
  });
})();
