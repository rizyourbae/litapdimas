/**
 * Litapdimas Admin UI Behaviors
 * =============================
 * Menangani interaksi umum area admin tanpa inline script pada view.
 */

(function () {
  "use strict";

  function toArray(nodeList) {
    return Array.prototype.slice.call(nodeList || []);
  }

  function initAdminTables() {
    if (typeof window.DtManager === "undefined") {
      return;
    }

    toArray(document.querySelectorAll("table[data-admin-datatable]")).forEach(function (table) {
      var tabPane = table.closest(".tab-pane");
      if (!table.id || table.dataset.adminDatatableReady === "1") {
        return;
      }

      if (tabPane && !tabPane.classList.contains("show") && !tabPane.classList.contains("active")) {
        return;
      }

      var options = {};
      var rawOptions = table.getAttribute("data-admin-datatable-options");
      if (rawOptions) {
        try {
          options = JSON.parse(rawOptions);
        } catch (error) {
          options = {};
        }
      }

      var skeletonId = table.getAttribute("data-skeleton-id") || "";
      var realWrapId = table.getAttribute("data-real-wrap-id") || "";

      if (typeof window.DtManager.initLazy === "function" && skeletonId && realWrapId) {
        window.DtManager.initLazy(table.id, options, skeletonId, realWrapId);
      } else {
        window.DtManager.init(table.id, options);
      }

      table.dataset.adminDatatableReady = "1";
    });
  }

  function initTabTableAdjustments() {
    document.addEventListener("shown.bs.tab", function (event) {
      var tabButton = event.target.closest('[data-bs-toggle="tab"]');
      if (!tabButton) {
        return;
      }

      var targetSelector = tabButton.getAttribute("data-bs-target") || tabButton.getAttribute("href");
      var pane = targetSelector ? document.querySelector(targetSelector) : null;
      if (!pane) {
        return;
      }

      initAdminTables();

      toArray(pane.querySelectorAll("table[data-admin-datatable]")).forEach(function (table) {
        if (table.id && typeof window.DtManager !== "undefined" && typeof window.DtManager.adjust === "function") {
          window.DtManager.adjust(table.id);
        }
      });
    });
  }

  function initFilterForms() {
    toArray(document.querySelectorAll("[data-admin-filter-form]")).forEach(function (form) {
      if (form.dataset.filterBound === "1") {
        return;
      }

      var submitButton = form.querySelector("[data-filter-submit]");
      var searchInput = form.querySelector('[data-filter-param="search"]');
      var baseUrl = form.getAttribute("data-filter-base-url") || window.location.pathname;

      function submitFilter() {
        var params = new URLSearchParams();

        toArray(form.querySelectorAll("[data-filter-param]")).forEach(function (field) {
          var key = field.getAttribute("data-filter-param");
          var value = field.value || "";
          if (key && value) {
            params.append(key, value);
          }
        });

        window.location.href = baseUrl + (params.toString() ? "?" + params.toString() : "");
      }

      if (submitButton) {
        submitButton.addEventListener("click", submitFilter);
      }

      if (searchInput) {
        searchInput.addEventListener("keyup", function (event) {
          if (event.key === "Enter") {
            submitFilter();
          }
        });
      }

      form.dataset.filterBound = "1";
    });
  }

  function initRestoreButtons() {
    document.addEventListener("click", function (event) {
      var button = event.target.closest(".btn-admin-restore[data-href]");
      if (!button) {
        return;
      }

      event.preventDefault();

      var href = button.getAttribute("data-href");
      if (!href) {
        return;
      }

      var title = button.getAttribute("data-confirm-title") || "Pulihkan data ini?";
      var html = button.getAttribute("data-confirm-html") || "Data akan diaktifkan kembali.";
      var confirmButton = button.getAttribute("data-confirm-button") || "Ya, pulihkan";

      if (typeof window.SwalConfirm === "function") {
        window.SwalConfirm(href, title, html, confirmButton);
        return;
      }

      if (window.confirm(title)) {
        window.location.href = href;
      }
    });
  }

  function initModalSelect2() {
    toArray(document.querySelectorAll(".modal")).forEach(function (modal) {
      if (modal.dataset.adminSelect2Bound === "1") {
        return;
      }

      modal.addEventListener("shown.bs.modal", function () {
        if (window.Select2Init && typeof window.Select2Init.initModal === "function") {
          // initialize when modal is fully shown to avoid issues initializing hidden selects
          window.Select2Init.initModal(modal);
        }
      });

      modal.dataset.adminSelect2Bound = "1";
    });
  }

  function setFieldValue(field, value) {
    if (!field) {
      return;
    }

    var normalizedValue = value == null ? "" : value;

    // Simpan nilai yang di-queue supaya bisa diterapkan ketika TomSelect di-inisialisasi
    try {
      field.setAttribute("data-admin-queued-value", normalizedValue);
    } catch (e) {}

    field.value = normalizedValue;

    if (field.matches("[data-select2]")) {
      if (window.Select2Init && typeof window.Select2Init.setValue === "function" && field.tomselect) {
        try {
          window.Select2Init.setValue(field, normalizedValue);
        } catch (e) {
          field.dispatchEvent(new Event("change", { bubbles: true }));
        }
      } else {
        field.dispatchEvent(new Event("change", { bubbles: true }));
      }
    }
  }

  function initModalTriggers() {
    document.addEventListener("click", function (event) {
      var trigger = event.target.closest("[data-admin-modal-target]");
      if (!trigger) {
        return;
      }

      if (trigger.hasAttribute("data-admin-modal-add-trigger") || trigger.hasAttribute("data-admin-fetch-url")) {
        return;
      }

      event.preventDefault();

      var modalSelector = trigger.getAttribute("data-admin-modal-target");
      var modal = modalSelector ? document.querySelector(modalSelector) : null;
      if (!modal) {
        return;
      }

      var formAction = trigger.getAttribute("data-admin-form-action");
      var form = modal.querySelector("form");
      if (form && formAction) {
        form.setAttribute("action", formAction);
      }

      var titleText = trigger.getAttribute("data-admin-modal-title-text");
      var titleNode = modal.querySelector("[data-admin-modal-title]");
      if (titleNode && titleText) {
        titleNode.innerHTML = titleText;
      }

      toArray(modal.querySelectorAll("[data-admin-field]")).forEach(function (field) {
        var fieldName = field.getAttribute("data-admin-field");
        var attrName = "data-admin-value-" + String(fieldName || "").replace(/_/g, "-");
        setFieldValue(field, trigger.getAttribute(attrName));
      });

      if (window.bootstrap && typeof window.bootstrap.Modal === "function") {
        window.bootstrap.Modal.getOrCreateInstance(modal).show();
      }
    });
  }

  function initAutoOpenState() {
    toArray(document.querySelectorAll("[data-admin-auto-open-tab], [data-admin-auto-open-modal]")).forEach(function (node) {
      var tabId = node.getAttribute("data-admin-auto-open-tab");
      var modalId = node.getAttribute("data-admin-auto-open-modal");

      if (tabId) {
        activateTab(document.getElementById(tabId));
      }

      if (modalId && window.bootstrap && typeof window.bootstrap.Modal === "function") {
        var modal = document.getElementById(modalId);
        if (modal) {
          window.bootstrap.Modal.getOrCreateInstance(modal).show();
        }
      }
    });
  }

  function populateFormFromData(form, data) {
    if (!form || !data) {
      return;
    }

    toArray(form.querySelectorAll("[name]")).forEach(function (field) {
      var name = field.getAttribute("name");
      if (!name || !(name in data)) {
        return;
      }

      setFieldValue(field, data[name]);
    });
  }

  function initRemoteModalCrud() {
    document.addEventListener("click", function (event) {
      var addTrigger = event.target.closest("[data-admin-modal-add-trigger]");
      if (addTrigger) {
        event.preventDefault();

        var addModalSelector = addTrigger.getAttribute("data-admin-modal-target");
        var addModal = addModalSelector ? document.querySelector(addModalSelector) : null;
        if (!addModal) {
          return;
        }

        var addForm = addModal.querySelector("form");
        if (addForm) {
          addForm.reset();

          var addAction = addTrigger.getAttribute("data-admin-form-action");
          if (addAction) {
            addForm.setAttribute("action", addAction);
          }

          var methodField = addForm.querySelector('input[name="_method"]');
          if (methodField) {
            methodField.value = addTrigger.getAttribute("data-admin-form-method") || "POST";
          }
        }

        var addTitle = addTrigger.getAttribute("data-admin-modal-title-text");
        var addTitleNode = addModal.querySelector("[data-admin-modal-title]");
        if (addTitle && addTitleNode) {
          addTitleNode.textContent = addTitle;
        }

        if (window.bootstrap && typeof window.bootstrap.Modal === "function") {
          window.bootstrap.Modal.getOrCreateInstance(addModal).show();
        }
        return;
      }

      var editTrigger = event.target.closest("[data-admin-fetch-url][data-admin-modal-target]");
      if (!editTrigger) {
        return;
      }

      event.preventDefault();

      var editModalSelector = editTrigger.getAttribute("data-admin-modal-target");
      var editModal = editModalSelector ? document.querySelector(editModalSelector) : null;
      if (!editModal) {
        return;
      }

      var fetchUrl = editTrigger.getAttribute("data-admin-fetch-url");
      if (!fetchUrl) {
        return;
      }

      fetch(fetchUrl)
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          if (data.error) {
            window.alert("Data tidak ditemukan");
            return;
          }

          var editForm = editModal.querySelector("form");
          if (editForm) {
            var editAction = editTrigger.getAttribute("data-admin-form-action");
            if (editAction) {
              editForm.setAttribute("action", editAction);
            }

            var editMethodField = editForm.querySelector('input[name="_method"]');
            if (editMethodField) {
              editMethodField.value = editTrigger.getAttribute("data-admin-form-method") || "PUT";
            }

            populateFormFromData(editForm, data);
          }

          var editTitle = editTrigger.getAttribute("data-admin-modal-title-text");
          var editTitleNode = editModal.querySelector("[data-admin-modal-title]");
          if (editTitle && editTitleNode) {
            editTitleNode.textContent = editTitle;
          }

          if (window.bootstrap && typeof window.bootstrap.Modal === "function") {
            window.bootstrap.Modal.getOrCreateInstance(editModal).show();
          }
        });
    });
  }

  function activateTab(tabButton) {
    if (!tabButton) {
      return;
    }

    if (window.bootstrap && typeof window.bootstrap.Tab === "function") {
      window.bootstrap.Tab.getOrCreateInstance(tabButton).show();
      return;
    }

    tabButton.click();
  }

  function initTabForms() {
    toArray(document.querySelectorAll("form[data-admin-tab-form]")).forEach(function (form) {
      if (form.dataset.adminTabReady === "1") {
        return;
      }

      var targetTabId = form.getAttribute("data-admin-active-tab");
      if (targetTabId) {
        activateTab(form.querySelector('[data-bs-target="#' + targetTabId + '"]'));
      }

      var firstInvalid = form.querySelector(".is-invalid");
      if (firstInvalid && typeof firstInvalid.focus === "function") {
        window.requestAnimationFrame(function () {
          firstInvalid.focus({ preventScroll: true });
        });
      }

      form.dataset.adminTabReady = "1";
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
      });

      form.dataset.submitStateBound = "1";
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    initAdminTables();
    initTabTableAdjustments();
    initFilterForms();
    initRestoreButtons();
    initModalSelect2();
    initModalTriggers();
    initTabForms();
    initAutoOpenState();
    initRemoteModalCrud();
    initSubmitStateForms();
  });
})();
