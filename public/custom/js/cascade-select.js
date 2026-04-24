/**
 * Universal Cascading Select Handler
 * Menampilkan/menyembunyikan option berdasarkan nilai parent select
 *
 * Penggunaan:
 * Parent select:
 *   <select id="parentSelect" class="cascade-parent" data-cascade-target="#childSelect">
 *
 * Child select:
 *   <select id="childSelect" class="cascade-child">
 *     <option value="1" data-parent="10">Option 1</option>
 *     <option value="2" data-parent="10">Option 2</option>
 *     <option value="3" data-parent="20">Option 3</option>
 *   </select>
 *
 * Atribut:
 * - data-cascade-target: selector dari child select yang akan di-filter
 * - data-parent di child option: parent value yang akan menampilkan option ini
 */

(function () {
  "use strict";

  // Inisialisasi semua cascade parent saat DOM ready
  document.addEventListener("DOMContentLoaded", function () {
    initializeCascades();
  });

  /**
   * Inisialisasi semua parent select yang memiliki cascade
   */
  function initializeCascades() {
    const parents = document.querySelectorAll("[data-cascade-target]");

    parents.forEach(function (parentEl) {
      const childSelector = parentEl.dataset.cascadeTarget;
      const childEl = document.querySelector(childSelector);

      if (!childEl) {
        console.warn("Cascade child not found: " + childSelector);
        return;
      }

      // Attach event listener ke parent
      parentEl.addEventListener("change", function () {
        filterChildOptions(this, childEl);
      });

      // Trigger initial filter
      filterChildOptions(parentEl, childEl);
    });
  }

  /**
   * Filter child options berdasarkan parent value
   * @param {HTMLElement} parentEl
   * @param {HTMLElement} childEl
   */
  function filterChildOptions(parentEl, childEl) {
    const parentValue = parentEl.value;
    const options = childEl.querySelectorAll("option");

    options.forEach(function (option) {
      const parentDataValue = option.dataset.parent;

      // Option dengan value kosong (-- Pilih --) selalu tampil
      if (option.value === "") {
        option.style.display = "";
      }
      // Option yang sesuai parent value tampil
      else if (parentDataValue && parentDataValue == parentValue) {
        option.style.display = "";
      }
      // Option lain disembunyikan
      else {
        option.style.display = "none";
      }
    });

    // Reset nilai child jika selected option tidak sesuai parent value
    const selectedOption = childEl.options[childEl.selectedIndex];
    if (selectedOption && selectedOption.value !== "" && selectedOption.style.display === "none") {
      childEl.value = "";
    }
  }

  // Export untuk testing atau penggunaan manual
  window.CascadeManager = {
    init: initializeCascades,
    filter: filterChildOptions,
  };
})();
