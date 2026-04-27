/**
 * public/custom/js/proposal-review.js
 * SweetAlert confirmation for proposal review submit.
 */

(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("proposalReviewForm");
    if (!form || typeof Swal === "undefined") {
      return;
    }

    let confirmed = false;

    form.addEventListener("submit", function (event) {
      if (confirmed) {
        return;
      }

      event.preventDefault();

      const title = form.dataset.confirmTitle || "Submit proposal ini?";
      const message = form.dataset.confirmMessage || "Setelah submit, proposal tidak bisa diubah lagi.";

      Swal.fire({
        title: title,
        html: message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, submit",
        cancelButtonText: "Batal",
        confirmButtonColor: "#16a34a",
        cancelButtonColor: "#6c757d",
        reverseButtons: true,
        focusCancel: true,
      }).then(function (result) {
        if (result.isConfirmed) {
          confirmed = true;
          form.submit();
        }
      });
    });
  });
})();
