/**
 * Litapdimas Dosen UI Behaviors
 * =============================
 * Menangani interaksi umum di halaman dosen tanpa inline script di view.
 */

(function () {
  "use strict";

  function toArray(nodeList) {
    return Array.prototype.slice.call(nodeList || []);
  }

  function initTables() {
    if (typeof window.DtManager === "undefined") {
      return;
    }

    toArray(document.querySelectorAll("table[data-dosen-datatable]")).forEach(function (table) {
      if (!table.id || table.dataset.dosenDatatableReady === "1") {
        return;
      }

      window.DtManager.init(table.id, {});
      table.dataset.dosenDatatableReady = "1";
    });
  }

  function initDeleteButtons() {
    document.addEventListener("click", function (event) {
      var deleteButton = event.target.closest(".btn-delete[data-href]");
      if (!deleteButton) {
        return;
      }

      event.preventDefault();

      var href = deleteButton.getAttribute("data-href");
      if (!href) {
        return;
      }

      var label = deleteButton.getAttribute("data-delete-label") || "data ini";
      var description = deleteButton.getAttribute("data-delete-desc") || "Data yang dihapus tidak dapat dikembalikan.";

      if (typeof window.SwalDelete === "function") {
        window.SwalDelete(href, label, description);
        return;
      }

      if (window.confirm("Hapus " + label + "?")) {
        window.location.href = href;
      }
    });
  }

  function initNumericInputs() {
    toArray(document.querySelectorAll("[data-numeric-only]")).forEach(function (input) {
      if (input.dataset.numericBound === "1") {
        return;
      }

      input.addEventListener("input", function () {
        this.value = this.value.replace(/\D+/g, "");
      });

      input.dataset.numericBound = "1";
    });
  }

  function initMarkdownToolbars() {
    toArray(document.querySelectorAll("[data-resume-toolbar]")).forEach(function (toolbar) {
      if (toolbar.dataset.toolbarBound === "1") {
        return;
      }

      var targetSelector = toolbar.getAttribute("data-resume-target");
      var target = targetSelector ? document.querySelector(targetSelector) : null;
      if (!target) {
        return;
      }

      toolbar.addEventListener("click", function (event) {
        var button = event.target.closest("button[data-wrap], button[data-prefix]");
        if (!button) {
          return;
        }

        event.preventDefault();

        var start = target.selectionStart;
        var end = target.selectionEnd;
        var selected = target.value.substring(start, end);
        var wrap = button.getAttribute("data-wrap") || "";
        var prefix = button.getAttribute("data-prefix") || "";
        var suffix = button.getAttribute("data-suffix") || "";

        var replacement = selected;
        if (wrap !== "") {
          replacement = wrap + selected + wrap;
        }
        replacement = prefix + replacement + suffix;

        target.setRangeText(replacement, start, end, "end");
        target.focus();
      });

      toolbar.dataset.toolbarBound = "1";
    });
  }

  function initFilePreviews() {
    toArray(document.querySelectorAll("[data-file-preview-input]")).forEach(function (input) {
      if (input.dataset.filePreviewBound === "1") {
        return;
      }

      var form = input.closest("form") || document;
      var previewWrap = form.querySelector(input.getAttribute("data-file-preview-wrap"));
      var previewName = form.querySelector(input.getAttribute("data-file-preview-name"));
      var previewSize = form.querySelector(input.getAttribute("data-file-preview-size"));

      if (!previewWrap || !previewName || !previewSize) {
        return;
      }

      input.addEventListener("change", function () {
        if (this.files && this.files[0]) {
          var file = this.files[0];
          previewName.textContent = file.name;
          previewSize.textContent = (file.size / 1024 / 1024).toFixed(2) + " MB";
          previewWrap.classList.remove("d-none");
          return;
        }

        previewWrap.classList.add("d-none");
      });

      input.dataset.filePreviewBound = "1";
    });
  }

  function initDokumenToggleForms() {
    toArray(document.querySelectorAll("form[data-riwayat-form]")).forEach(function (form) {
      if (form.dataset.riwayatBound === "1") {
        return;
      }

      var toggles = toArray(form.querySelectorAll("[data-dokumen-toggle]"));
      var sections = toArray(form.querySelectorAll("[data-dokumen-section]"));
      var ipkInput = form.querySelector("[data-ipk-field]");

      function syncDokumenSection() {
        var checked = form.querySelector("[data-dokumen-toggle]:checked");
        var mode = checked ? checked.value : "url";

        sections.forEach(function (section) {
          section.classList.toggle("d-none", section.dataset.dokumenSection !== mode);
        });
      }

      toggles.forEach(function (radio) {
        radio.addEventListener("change", syncDokumenSection);
      });

      if (ipkInput && ipkInput.dataset.ipkBound !== "1") {
        ipkInput.addEventListener("blur", function () {
          var value = parseFloat(this.value);
          if (!isNaN(value) && value >= 0 && value <= 4) {
            this.value = value.toFixed(2);
          }
        });
        ipkInput.dataset.ipkBound = "1";
      }

      syncDokumenSection();
      form.dataset.riwayatBound = "1";
    });
  }

  function initPublicationForms() {
    toArray(document.querySelectorAll("form[data-publikasi-form]")).forEach(function (form) {
      if (form.dataset.publikasiBound === "1") {
        return;
      }

      var radios = toArray(form.querySelectorAll("[data-publikasi-trigger]"));
      var sections = toArray(form.querySelectorAll("[data-publikasi-section]"));
      var pembiayaanWrap = form.querySelector("[data-publikasi-pembiayaan-wrap]");
      var pembiayaanSelect = form.querySelector("[data-publikasi-pembiayaan-select]");
      var pembiayaanOther = form.querySelector("[data-publikasi-pembiayaan-other]");

      function syncOtherPembiayaan() {
        if (!pembiayaanSelect || !pembiayaanOther) {
          return;
        }

        var showOther = (pembiayaanSelect.value || "").toLowerCase() === "lainnya";
        pembiayaanOther.classList.toggle("d-none", !showOther);
        pembiayaanOther.required = showOther;

        if (!showOther) {
          pembiayaanOther.value = "";
        }
      }

      function syncSections() {
        var checked = form.querySelector("[data-publikasi-trigger]:checked");
        var selected = checked ? (checked.value || "").toLowerCase() : "";

        sections.forEach(function (section) {
          section.classList.toggle("d-none", section.dataset.publikasiSection !== selected);
        });

        if (pembiayaanWrap && pembiayaanSelect) {
          var hidePembiayaan = selected === "buku";
          pembiayaanWrap.classList.toggle("d-none", hidePembiayaan);
          pembiayaanSelect.required = !hidePembiayaan;

          if (hidePembiayaan && pembiayaanOther) {
            pembiayaanOther.classList.add("d-none");
            pembiayaanOther.required = false;
            pembiayaanOther.value = "";
          }

          syncOtherPembiayaan();
        }
      }

      radios.forEach(function (radio) {
        radio.addEventListener("change", syncSections);
      });

      if (pembiayaanSelect && pembiayaanOther && pembiayaanSelect.dataset.pembiayaanBound !== "1") {
        pembiayaanSelect.addEventListener("change", syncOtherPembiayaan);
        pembiayaanSelect.dataset.pembiayaanBound = "1";
      }

      syncSections();
      form.dataset.publikasiBound = "1";
    });
  }

  function initSubmitStateForms() {
    toArray(document.querySelectorAll("form[data-submit-state-form]")).forEach(function (form) {
      if (form.dataset.submitStateBound === "1") {
        return;
      }

      form.addEventListener("submit", function (event) {
        if (form.dataset.submitting === "1") {
          event.preventDefault();
          return;
        }

        form.dataset.submitting = "1";
        form.setAttribute("aria-busy", "true");

        toArray(form.querySelectorAll("[data-submit-trigger]")).forEach(function (button) {
          button.disabled = true;
          button.classList.add("is-loading");

          var defaultContent = button.querySelector("[data-submit-default-content]");
          var loadingContent = button.querySelector("[data-submit-loading-content]");

          if (defaultContent) {
            defaultContent.classList.add("d-none");
          }

          if (loadingContent) {
            loadingContent.classList.remove("d-none");
            loadingContent.classList.add("d-inline-flex");
          }
        });

        var feedback = form.querySelector("[data-submit-feedback]");
        if (feedback) {
          var loadingText = form.getAttribute("data-submit-loading-text");
          if (loadingText) {
            feedback.textContent = loadingText;
          }
          feedback.classList.remove("d-none");
          feedback.classList.add("d-inline-flex");
        }
      });

      form.dataset.submitStateBound = "1";
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    initTables();
    initDeleteButtons();
    initNumericInputs();
    initMarkdownToolbars();
    initFilePreviews();
    initDokumenToggleForms();
    initPublicationForms();
    initSubmitStateForms();
  });
})();
