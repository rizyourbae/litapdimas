(function () {
  "use strict";

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

  document.addEventListener("DOMContentLoaded", initReviewerFilter);
  document.addEventListener("DOMContentLoaded", activateTabFromHash);
  window.addEventListener("hashchange", activateTabFromHash);
})();
