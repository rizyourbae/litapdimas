/**
 * Enhanced Select Helpers
 * =======================
 * Wrapper kompatibilitas untuk field `data-select2`, tetapi backend plugin-nya memakai Tom Select.
 *
 * API `Select2Init` dipertahankan agar view lama tidak perlu diubah besar-besaran.
 */

(function () {
  "use strict";

  if (typeof TomSelect === "undefined") {
    console.warn("[select2-init.js] Tom Select belum ter-load.");
    return;
  }

  function toElement(context) {
    if (!context) return document;
    if (typeof context === "string") return document.querySelector(context);
    return context;
  }

  function findSelects(context, selector) {
    const root = toElement(context) || document;
    const query = selector || "select[data-select2]";

    if (root.matches && root.matches(query)) {
      return [root];
    }

    return Array.from(root.querySelectorAll(query));
  }

  function buildOptions(selectEl, extraOptions) {
    const placeholderOption = Array.from(selectEl.options).find(function (option) {
      return option.value === "";
    });

    return Object.assign(
      {
        maxOptions: null,
        copyClassesToDropdown: true,
        hidePlaceholder: false,
        allowEmptyOption: true,
        placeholder: placeholderOption ? placeholderOption.textContent.trim() : "Pilih opsi",
        render: {
          no_results: function () {
            return '<div class="no-results">Data tidak ditemukan</div>';
          },
        },
      },
      extraOptions || {},
    );
  }

  function initializeOne(selectEl, extraOptions, force) {
    if (!selectEl) return null;

    if (selectEl.tomselect) {
      if (!force) return selectEl.tomselect;
      selectEl.tomselect.destroy();
    }

    return new TomSelect(selectEl, buildOptions(selectEl, extraOptions));
  }

  function visibleOptionExists(selectEl, value) {
    return Array.from(selectEl.options).some(function (option) {
      return option.value == value && option.style.display !== "none";
    });
  }

  window.Select2Init = {
    init: function (context, force) {
      findSelects(context, "select[data-select2]").forEach(function (selectEl) {
        initializeOne(selectEl, null, !!force);
      });
    },

    refresh: function (context) {
      this.init(context, true);
    },

    initModal: function (modalEl) {
      findSelects(modalEl, "select[data-select2]").forEach(function (selectEl) {
        initializeOne(selectEl, { dropdownParent: modalEl }, true);
      });
    },

    setValue: function (selector, value) {
      const selectEl = toElement(selector);
      if (!selectEl) return;

      if (selectEl.tomselect) {
        selectEl.tomselect.setValue(value);
        return;
      }

      selectEl.value = value;
      selectEl.dispatchEvent(new Event("change", { bubbles: true }));
    },

    sync: function (selector) {
      const selectEl = toElement(selector);
      if (!selectEl) return;

      const selectedValue = selectEl.value;
      const modalEl = selectEl.closest(".modal");

      initializeOne(selectEl, modalEl ? { dropdownParent: modalEl } : null, true);

      if (selectedValue !== "" && visibleOptionExists(selectEl, selectedValue) && selectEl.tomselect) {
        selectEl.tomselect.setValue(selectedValue, true);
      }
    },

    destroy: function (context) {
      findSelects(context || document, "select").forEach(function (selectEl) {
        if (selectEl.tomselect) {
          selectEl.tomselect.destroy();
        }
      });
    },
  };

  document.addEventListener("DOMContentLoaded", function () {
    Select2Init.init();

    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function (tabBtn) {
      tabBtn.addEventListener("show.bs.tab", function () {
        const targetPane = document.querySelector(this.getAttribute("data-bs-target"));
        if (!targetPane) return;

        setTimeout(function () {
          Select2Init.refresh(targetPane);
        }, 50);
      });
    });
  });
})();
