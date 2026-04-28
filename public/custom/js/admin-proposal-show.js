(function () {
  "use strict";

  var PRESENTASI_TOAST_KEY = "adminProposalPresentasiToast";

  function toArray(nodeList) {
    return Array.prototype.slice.call(nodeList || []);
  }

  function normalize(value) {
    return String(value || "")
      .trim()
      .toLowerCase();
  }

  function initReviewerFilter() {
    toArray(document.querySelectorAll("[data-reviewer-filter-input]")).forEach(function (input) {
      if (input.dataset.reviewerFilterBound === "1") {
        return;
      }

      var listSelector = input.getAttribute("data-reviewer-filter-input");
      var list = listSelector ? document.querySelector(listSelector) : null;
      if (!list) {
        return;
      }

      var emptySelector = input.getAttribute("data-reviewer-empty-target");
      var emptyState = emptySelector ? document.querySelector(emptySelector) : null;
      var countSelector = input.getAttribute("data-reviewer-count-target");
      var countNode = countSelector ? document.querySelector(countSelector) : null;
      var items = toArray(list.querySelectorAll("[data-reviewer-card-filter-item]"));

      function applyFilter() {
        var keyword = normalize(input.value);
        var visibleCount = 0;

        items.forEach(function (item) {
          var haystack = normalize(item.getAttribute("data-reviewer-search"));
          var matched = keyword === "" || haystack.indexOf(keyword) !== -1;

          item.classList.toggle("d-none", !matched);
          if (matched) {
            visibleCount += 1;
          }
        });

        if (countNode) {
          countNode.textContent = String(visibleCount);
        }

        if (emptyState) {
          emptyState.classList.toggle("d-none", visibleCount !== 0);
        }
      }

      input.addEventListener("input", applyFilter);
      input.dataset.reviewerFilterBound = "1";
    });
  }

  function activateTabFromHash() {
    var hash = window.location.hash ? window.location.hash.substring(1) : "";
    if (!hash) {
      return;
    }

    var target = document.getElementById(hash);
    if (!target || !window.bootstrap || !bootstrap.Tab) {
      return;
    }

    if (target.matches('[data-bs-toggle="tab"]')) {
      bootstrap.Tab.getOrCreateInstance(target).show();
      return;
    }

    var tabSelector = '[data-bs-toggle="tab"][data-bs-target="#' + hash + '"]';
    var tabTrigger = document.querySelector(tabSelector);
    if (tabTrigger) {
      bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
      return;
    }

    var labelledBy = target.getAttribute("aria-labelledby");
    if (labelledBy) {
      var labelledTab = document.getElementById(labelledBy);
      if (labelledTab && labelledTab.matches('[data-bs-toggle="tab"]')) {
        bootstrap.Tab.getOrCreateInstance(labelledTab).show();
      }
    }
  }

  function isPresentasiTargetActive() {
    var hash = window.location.hash ? window.location.hash.substring(1) : "";

    return hash === "proposalReviewerPresentasiTab" || hash === "proposalReviewerPresentasiPane";
  }

  function markPresentasiToastPending() {
    try {
      window.sessionStorage.setItem(PRESENTASI_TOAST_KEY, "1");
    } catch (error) {
      return;
    }
  }

  function consumePresentasiToastPending() {
    try {
      if (window.sessionStorage.getItem(PRESENTASI_TOAST_KEY) !== "1") {
        return false;
      }

      window.sessionStorage.removeItem(PRESENTASI_TOAST_KEY);
      return true;
    } catch (error) {
      return false;
    }
  }

  function maybeShowPresentasiSuccessToast() {
    if (!isPresentasiTargetActive() || !consumePresentasiToastPending()) {
      return;
    }

    if (typeof window.SwalToast === "function") {
      window.SwalToast("success", "Tahap penilaian presentasi berhasil dibuka.");
    }
  }

  function initPresentasiTrigger() {
    var trigger = document.querySelector("[data-presentasi-trigger]");
    if (!trigger || trigger.dataset.presentasiBound === "1") {
      return;
    }

    trigger.addEventListener("click", function (event) {
      var href = trigger.getAttribute("href");
      if (!href) {
        return;
      }

      if (typeof Swal === "undefined") {
        return;
      }

      event.preventDefault();

      Swal.fire({
        title: trigger.dataset.presentasiTitle || "Buka penilaian presentasi?",
        html: trigger.dataset.presentasiMessage || "Tahap penilaian presentasi akan dibuka.",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: trigger.dataset.presentasiConfirmText || "Lanjutkan",
        cancelButtonText: "Batal",
        confirmButtonColor: "#198754",
        cancelButtonColor: "#6c757d",
        reverseButtons: true,
        focusCancel: true,
      }).then(function (result) {
        if (result.isConfirmed) {
          var targetUrl = new URL(href, window.location.href);
          var currentUrl = new URL(window.location.href);

          markPresentasiToastPending();

          if (targetUrl.pathname === currentUrl.pathname && targetUrl.search === currentUrl.search && targetUrl.hash === currentUrl.hash) {
            activateTabFromHash();
            maybeShowPresentasiSuccessToast();
            return;
          }

          window.location.href = targetUrl.toString();
        }
      });
    });

    trigger.dataset.presentasiBound = "1";
  }

  document.addEventListener("DOMContentLoaded", initReviewerFilter);
  document.addEventListener("DOMContentLoaded", activateTabFromHash);
  document.addEventListener("DOMContentLoaded", maybeShowPresentasiSuccessToast);
  document.addEventListener("DOMContentLoaded", initPresentasiTrigger);
  window.addEventListener("hashchange", activateTabFromHash);
  window.addEventListener("hashchange", maybeShowPresentasiSuccessToast);
})();
