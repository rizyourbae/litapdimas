/**
 * public/custom/js/proposal-wizard.js
 * Wizard stepper and navigation logic
 */

/**
 * Initialize stepper on page load
 */
document.addEventListener("DOMContentLoaded", function () {
  initializeStepper();
  setupFormValidation();
});

/**
 * Initialize stepper UI
 */
function initializeStepper() {
  const steps = document.querySelectorAll(".stepper-step");

  steps.forEach((step, index) => {
    if (index < steps.length - 1) {
      const connector = document.createElement("div");
      connector.className = "stepper-connector";
      step.appendChild(connector);
    }
  });
}

/**
 * Update stepper based on current step
 */
function updateStepper(currentStep) {
  const steps = document.querySelectorAll(".stepper-step");

  steps.forEach((step, index) => {
    const stepNumber = index + 1;

    step.classList.remove("active", "completed");

    if (stepNumber < currentStep) {
      step.classList.add("completed");
    } else if (stepNumber === currentStep) {
      step.classList.add("active");
    }
  });
}

/**
 * Setup form validation
 */
function setupFormValidation() {
  const form = document.getElementById("proposalForm");
  if (!form) return;

  form.addEventListener(
    "submit",
    function (e) {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }

      form.classList.add("was-validated");
    },
    false,
  );
}

/**
 * Go to previous step
 */
function goToPreviousStep(currentStep, proposalId) {
  if (currentStep > 1) {
    window.location.href = `/dosen/proposals/step/${currentStep - 1}/${proposalId}`;
  }
}

/**
 * Go to next step
 */
function goToNextStep(currentStep, proposalId) {
  const form = document.getElementById("proposalForm");

  if (form && form.checkValidity() === false) {
    form.classList.add("was-validated");
    return false;
  }

  // Submit form to save current step
  form?.submit();
}

/**
 * Skip to specific step (only if gated correctly)
 */
function skipToStep(step, proposalId) {
  // This is prevented by backend gating
  // UI shouldn't allow clicking non-current/completed steps
  const form = document.getElementById("proposalForm");
  if (form && form.checkValidity() === false) {
    alert("Silakan isi semua field yang wajib terlebih dahulu");
    return false;
  }
}

/**
 * Format keywords input
 */
function formatKeywordsInput() {
  const input = document.getElementById("kata_kunci");
  if (!input) return;

  input.addEventListener("blur", function () {
    const keywords = this.value
      .split(",")
      .map((k) => k.trim())
      .filter((k) => k.length > 0)
      .join(", ");

    this.value = keywords;
  });
}

/**
 * Format currency input
 */
function formatCurrencyInput() {
  const input = document.getElementById("total_pengajuan_dana");
  if (!input) return;

  const normalizeNumericValue = function () {
    this.value = this.value.replace(/\D/g, "");
  };

  input.addEventListener("input", normalizeNumericValue);
  input.addEventListener("change", normalizeNumericValue);

  if (input.value) {
    input.value = input.value.replace(/\D/g, "");
  }
}

// Initialize helpers when DOM ready
document.addEventListener("DOMContentLoaded", function () {
  formatKeywordsInput();
  formatCurrencyInput();
});

/**
 * Show loading spinner on submit
 */
function showLoadingOnSubmit() {
  const form = document.getElementById("proposalForm");
  if (!form) return;

  form.addEventListener("submit", function () {
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn && form.checkValidity()) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
    }
  });
}

document.addEventListener("DOMContentLoaded", showLoadingOnSubmit);
