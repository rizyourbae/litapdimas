/**
 * public/custom/js/proposal-quill.js
 * Initialize Quill editors on target elements
 */

const quillEditors = {};

/**
 * Initialize all Quill editors on page
 */
function initializeQuillEditors() {
  const editors = document.querySelectorAll("[data-quill]");

  editors.forEach((editorEl) => {
    const quillId = editorEl.getAttribute("data-quill");

    // Skip if already initialized
    if (quillEditors[quillId]) {
      return;
    }

    // Initialize Quill
    const quill = new Quill(editorEl, {
      theme: "snow",
      placeholder: "Mulai ketik...",
      modules: {
        toolbar: [["bold", "italic", "underline", "strike"], ["blockquote", "code-block"], [{ header: 1 }, { header: 2 }], [{ list: "ordered" }, { list: "bullet" }], [{ align: [] }], ["link"], ["clean"]],
      },
    });

    // Store reference
    quillEditors[quillId] = quill;

    // Sync content to hidden input on change
    quill.on("text-change", function () {
      const content = quill.root.innerHTML;

      // Update hidden input
      if (quillId === "abstrak") {
        document.getElementById("abstrakInput").value = content;
      } else if (quillId === "profil_jurnal") {
        document.getElementById("profilJurnalInput").value = content;
      } else if (quillId.startsWith("substansi[")) {
        // Extract index from substansi[0] format
        const match = quillId.match(/substansi\[(\d+)\]/);
        if (match) {
          const idx = match[1];
          const hiddenInput = document.querySelector(`input[name="substansi_bagian[${idx}][isi_bagian]"]`);
          if (hiddenInput) {
            hiddenInput.value = content;
          }
        }
      }
    });
  });
}

/**
 * Get Quill editor content
 */
function getQuillContent(quillId) {
  return quillEditors[quillId]?.root.innerHTML || "";
}

/**
 * Set Quill editor content
 */
function setQuillContent(quillId, content) {
  if (quillEditors[quillId]) {
    quillEditors[quillId].root.innerHTML = content;
  }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
  initializeQuillEditors();
});

// Also initialize on dynamic content load
const observer = new MutationObserver(function (mutations) {
  let needsInit = false;
  mutations.forEach(function (mutation) {
    if (mutation.type === "childList") {
      needsInit = mutation.addedNodes.length > 0;
    }
  });
  if (needsInit) {
    initializeQuillEditors();
  }
});

observer.observe(document.body, {
  childList: true,
  subtree: true,
});
