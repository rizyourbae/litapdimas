(function () {
  "use strict";

  function normalizeEditorHtml(value) {
    var html = String(value || "").trim();

    return html === "<p><br></p>" ? "" : html;
  }

  function initReviewerEditors() {
    if (typeof Quill === "undefined") {
      return;
    }

    document.querySelectorAll("[data-reviewer-quill]").forEach(function (editorElement) {
      if (editorElement.dataset.reviewerQuillBound === "1") {
        return;
      }

      var hiddenSelector = editorElement.getAttribute("data-reviewer-hidden-input");
      var hiddenInput = hiddenSelector ? document.querySelector(hiddenSelector) : null;
      var placeholder = editorElement.getAttribute("data-reviewer-placeholder") || "Mulai ketik...";

      var quill = new Quill(editorElement, {
        theme: "snow",
        placeholder: placeholder,
        modules: {
          toolbar: [["bold", "italic", "underline", "strike"], [{ header: 2 }, { header: 3 }], [{ list: "ordered" }, { list: "bullet" }], ["blockquote", "link"], ["clean"]],
        },
      });

      if (hiddenInput) {
        hiddenInput.value = normalizeEditorHtml(quill.root.innerHTML);
      }

      quill.on("text-change", function () {
        if (!hiddenInput) {
          return;
        }

        hiddenInput.value = normalizeEditorHtml(quill.root.innerHTML);
      });

      editorElement.dataset.reviewerQuillBound = "1";
    });
  }

  document.addEventListener("DOMContentLoaded", initReviewerEditors);
})();
