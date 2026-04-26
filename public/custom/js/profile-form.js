/**
 * Profile Form Ergonomics
 * =======================
 * Mengelola progress per section, aktivasi tab error, dan preview foto.
 */

(function () {
  "use strict";

  function toArray(nodeList) {
    return Array.prototype.slice.call(nodeList || []);
  }

  function isFieldFilled(field) {
    if (!field) {
      return false;
    }

    if (field.type === "file") {
      return (field.files && field.files.length > 0) || field.dataset.progressInitialFilled === "1";
    }

    if (field.type === "checkbox" || field.type === "radio") {
      return field.checked;
    }

    return String(field.value || "").trim() !== "";
  }

  function updateSummaryCard(sectionId, percent) {
    var valueNode = document.querySelector('[data-profile-summary-value="' + sectionId + '"]');
    var barNode = document.querySelector('[data-profile-summary-bar="' + sectionId + '"]');

    if (valueNode) {
      valueNode.textContent = percent + "%";
    }

    if (barNode) {
      barNode.style.width = percent + "%";
      barNode.setAttribute("aria-valuenow", String(percent));
    }
  }

  function activateBootstrapTab(tabButton) {
    if (!tabButton) {
      return;
    }

    if (window.bootstrap && typeof window.bootstrap.Tab === "function") {
      window.bootstrap.Tab.getOrCreateInstance(tabButton).show();
      return;
    }

    tabButton.click();
  }

  function findErrorTab(form) {
    var panes = toArray(form.querySelectorAll(".tab-pane"));
    for (var index = 0; index < panes.length; index += 1) {
      var pane = panes[index];
      if (pane.querySelector(".is-invalid, .invalid-feedback, .small.text-danger")) {
        return pane.id;
      }
    }

    return form.dataset.profileActiveTab || "";
  }

  function findFirstErrorTarget(scope) {
    if (!scope) {
      return null;
    }

    var invalidField = scope.querySelector(".is-invalid");
    if (invalidField) {
      return invalidField;
    }

    var passwordError = scope.querySelector(".small.text-danger");
    if (passwordError && passwordError.previousElementSibling) {
      return passwordError.previousElementSibling;
    }

    var invalidFeedback = scope.querySelector(".invalid-feedback");
    if (invalidFeedback && invalidFeedback.previousElementSibling) {
      return invalidFeedback.previousElementSibling;
    }

    return null;
  }

  function revealFirstError(form, targetTabId) {
    var targetScope = targetTabId ? form.querySelector("#" + targetTabId) : form;
    var target = findFirstErrorTarget(targetScope) || findFirstErrorTarget(form);

    if (!target) {
      return;
    }

    window.requestAnimationFrame(function () {
      target.scrollIntoView({ block: "center", inline: "nearest" });

      if (typeof target.focus === "function") {
        target.focus({ preventScroll: true });
      }
    });
  }

  function updateProgress(form) {
    var sections = toArray(form.querySelectorAll("[data-profile-section]"));
    var tabTotals = {};
    var overallFilled = 0;
    var overallTotal = 0;

    sections.forEach(function (section) {
      var sectionId = section.dataset.profileSection;
      var tabId = section.dataset.profileTab || "profil";
      var fields = toArray(section.querySelectorAll("[data-progress-field]"));
      var filled = fields.filter(isFieldFilled).length;
      var total = fields.length;
      var percent = total === 0 ? 0 : Math.round((filled / total) * 100);

      updateSummaryCard(sectionId, percent);

      if (!tabTotals[tabId]) {
        tabTotals[tabId] = { filled: 0, total: 0 };
      }

      tabTotals[tabId].filled += filled;
      tabTotals[tabId].total += total;
      overallFilled += filled;
      overallTotal += total;
    });

    Object.keys(tabTotals).forEach(function (tabId) {
      var tabNode = form.querySelector('[data-profile-tab-value="' + tabId + '"]');
      var tabPercent = tabTotals[tabId].total === 0 ? 0 : Math.round((tabTotals[tabId].filled / tabTotals[tabId].total) * 100);
      if (tabNode) {
        tabNode.textContent = tabPercent + "%";
      }
    });

    var overallPercent = overallTotal === 0 ? 0 : Math.round((overallFilled / overallTotal) * 100);
    toArray(document.querySelectorAll("[data-profile-overall-value], [data-profile-overall-inline]")).forEach(function (node) {
      node.textContent = overallPercent + "%";
    });

    var overallBar = document.querySelector("[data-profile-overall-bar]");
    if (overallBar) {
      overallBar.style.width = overallPercent + "%";
      overallBar.setAttribute("aria-valuenow", String(overallPercent));
    }

    var overallText = document.querySelector("[data-profile-overall-text]");
    if (overallText) {
      overallText.textContent = overallFilled + " dari " + overallTotal + " field utama terisi";
    }
  }

  function initPhotoPreview(form) {
    var inputs = toArray(form.querySelectorAll("[data-photo-preview-input]"));

    inputs.forEach(function (input) {
      if (input.dataset.photoPreviewBound === "1") {
        return;
      }

      var targetSelector = input.getAttribute("data-photo-preview-input");
      var target = targetSelector ? document.querySelector(targetSelector) : null;
      if (!target) {
        return;
      }

      input.addEventListener("change", function () {
        var file = this.files && this.files[0] ? this.files[0] : null;
        if (!file || !file.type || file.type.indexOf("image/") !== 0) {
          return;
        }

        var reader = new FileReader();
        reader.onload = function (event) {
          if (event.target && event.target.result) {
            target.src = event.target.result;
          }
        };
        reader.readAsDataURL(file);
        input.dataset.progressInitialFilled = "1";
        updateProgress(form);
      });

      input.dataset.photoPreviewBound = "1";
    });
  }

  function initProfileForm(form) {
    if (!form || form.dataset.profileEnhanced === "1") {
      return;
    }

    var targetTabId = findErrorTab(form);
    if (targetTabId) {
      activateBootstrapTab(form.querySelector('[data-bs-target="#' + targetTabId + '"]'));
      revealFirstError(form, targetTabId);
    }

    form.addEventListener("input", function () {
      updateProgress(form);
    });

    form.addEventListener("change", function () {
      updateProgress(form);
    });

    initPhotoPreview(form);
    updateProgress(form);
    form.dataset.profileEnhanced = "1";
  }

  document.addEventListener("DOMContentLoaded", function () {
    toArray(document.querySelectorAll("[data-profile-form]")).forEach(initProfileForm);
  });
})();
