/**
 * Universal Datepicker Handler dengan Flatpickr
 * Inisialisasi otomatis datepicker berdasarkan data attributes
 *
 * Penggunaan:
 * <input type="text"
 *   class="datepicker"
 *   data-locale="id"
 *   data-date-format="Y-m-d"
 *   data-alt-format="d F Y"
 *   data-max-date="today"
 *   placeholder="Pilih tanggal">
 */

(function () {
  "use strict";

  // Tunggu Flatpickr ter-load
  if (typeof flatpickr === "undefined") {
    console.error("Flatpickr belum ter-load. Pastikan Flatpickr script sudah dimuat sebelum file ini.");
    return;
  }

  // Inisialisasi semua datepicker saat DOM ready
  document.addEventListener("DOMContentLoaded", function () {
    initializeDatepickers();
  });

  /**
   * Inisialisasi semua elemen dengan class 'datepicker'
   */
  function initializeDatepickers() {
    const pickers = document.querySelectorAll(".datepicker");

    pickers.forEach(function (element) {
      // Skip jika sudah ada instance flatpickr
      if (element._flatpickr) {
        return;
      }

      const config = getPickerConfig(element);
      flatpickr(element, config);
    });
  }

  /**
   * Parse konfigurasi dari data attributes
   * @param {HTMLElement} element
   * @returns {Object} Flatpickr config
   */
  function getPickerConfig(element) {
    const dataset = element.dataset;
    const config = {
      allowInput: true,
      closeOnSelect: true,
    };

    // Locale
    if (dataset.locale) {
      config.locale = dataset.locale;
    }

    // Date format untuk disimpan
    if (dataset.dateFormat) {
      config.dateFormat = dataset.dateFormat;
    } else {
      config.dateFormat = "Y-m-d"; // Default
    }

    // Alternative format untuk tampilan
    if (dataset.altFormat) {
      config.altInput = true;
      config.altFormat = dataset.altFormat;
    }

    // Max date (opsional: 'today', date string, atau null)
    if (dataset.maxDate) {
      config.maxDate = dataset.maxDate === "today" ? "today" : dataset.maxDate;
    }

    // Min date (opsional)
    if (dataset.minDate) {
      config.minDate = dataset.minDate === "today" ? "today" : dataset.minDate;
    }

    // Default value dari input value
    if (element.value) {
      config.defaultDate = element.value;
    }

    return config;
  }

  // Export untuk testing atau penggunaan manual
  window.DatepickerManager = {
    init: initializeDatepickers,
    getConfig: getPickerConfig,
  };
})();
