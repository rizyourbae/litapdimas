/**
 * public/custom/js/proposal-upload.js
 * Handle file upload with drag-drop support
 */

/**
 * Handle drag over upload zone
 */
function handleDragOver(event) {
  event.preventDefault();
  event.stopPropagation();

  const zone = event.currentTarget;
  zone.classList.add("border-success", "bg-light");
}

/**
 * Handle drag out upload zone
 */
function handleDragOut(event) {
  event.preventDefault();
  event.stopPropagation();

  const zone = event.currentTarget;
  zone.classList.remove("border-success", "bg-light");
}

/**
 * Handle drop on upload zone
 */
function handleDrop(event) {
  event.preventDefault();
  event.stopPropagation();

  const zone = event.currentTarget;
  zone.classList.remove("border-success", "bg-light");

  const files = event.dataTransfer.files;
  if (files.length > 0) {
    // Get the file type from zone's data attribute
    const fileType = zone.dataset.fileType;

    if (fileType) {
      // Get corresponding input
      const input = document.getElementById(fileType);
      if (input) {
        input.files = files;
        handleFileSelect({ target: input });
      }
    } else {
      // Multiple files for pendukung
      const input = document.getElementById("file_pendukung");
      if (input) {
        input.files = files;
        handleFileSelect({ target: input });
      }
    }
  }
}

/**
 * Handle file select
 */
function handleFileSelect(event) {
  const input = event.target;
  const files = input.files;

  if (files.length === 0) return;

  // Validate each file
  for (let i = 0; i < files.length; i++) {
    const file = files[i];

    // Check MIME type
    if (file.type !== "application/pdf") {
      alert(`File "${file.name}" bukan PDF. Hanya file PDF yang diperbolehkan.`);
      input.value = "";
      return;
    }

    // Check file size (2MB)
    const maxSize = 2 * 1024 * 1024;
    if (file.size > maxSize) {
      alert(`File "${file.name}" terlalu besar. Maksimal 2MB.`);
      input.value = "";
      return;
    }
  }

  // Update UI based on input type
  if (input.name.includes("file_pendukung")) {
    updatePendukungFilesList(files);
  } else {
    updateSingleFileDisplay(input.id, files[0]);
  }
}

/**
 * Update display for single file
 */
function updateSingleFileDisplay(inputId, file) {
  const zone = document.getElementById(inputId + "Zone");
  if (!zone) return;

  const progressDiv = zone.nextElementSibling;
  const successDiv = progressDiv?.nextElementSibling;

  if (progressDiv && successDiv) {
    progressDiv.style.display = "block";

    // Simulate upload progress
    let progress = 0;
    const interval = setInterval(() => {
      progress += Math.random() * 30;
      if (progress >= 100) {
        progress = 100;
        clearInterval(interval);

        progressDiv.style.display = "none";
        successDiv.style.display = "block";

        if (successDiv.querySelector("#" + inputId + "_name")) {
          successDiv.querySelector("#" + inputId + "_name").textContent = `Nama file: ${file.name}`;
        }
      }

      const progressBar = progressDiv.querySelector(".progress-bar");
      if (progressBar) {
        progressBar.style.width = progress + "%";
      }
    }, 100);
  }
}

/**
 * Update display for multiple files (pendukung)
 */
function updatePendukungFilesList(files) {
  const listDiv = document.getElementById("pendukungFilesList");
  if (!listDiv) return;

  listDiv.innerHTML = '<h6 class="mb-2">File yang akan diunggah:</h6>';

  const list = document.createElement("ul");
  list.className = "list-group";

  for (let i = 0; i < files.length; i++) {
    const file = files[i];
    const li = document.createElement("li");
    li.className = "list-group-item";
    li.innerHTML = `
            <i class="fas fa-file-pdf text-danger"></i> 
            ${file.name} 
            <small class="text-muted">(${(file.size / 1024).toFixed(2)} KB)</small>
        `;
    list.appendChild(li);
  }

  listDiv.appendChild(list);
}
