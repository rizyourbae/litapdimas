/**
 * public/custom/js/proposal-repeatable.js
 * Handle add/remove rows untuk repeatable items
 * (Peneliti, Mahasiswa, Anggota Eksternal, Substansi)
 */

/**
 * Add new peneliti row
 */
function addPenelitiRow() {
  const tbody = document.getElementById("penelitiBody");
  if (!tbody) return;

  const rowCount = tbody.querySelectorAll("tr").length;

  const newRow = document.createElement("tr");
  newRow.className = "peneliti-row";
  newRow.dataset.index = rowCount;
  newRow.innerHTML = `
        <td>
            <input type="text" class="form-control form-control-sm" name="peneliti_internal[${rowCount}][nama]" placeholder="Nama lengkap">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="peneliti_internal[${rowCount}][nip]" placeholder="NIP">
        </td>
        <td>
            <input type="email" class="form-control form-control-sm" name="peneliti_internal[${rowCount}][email]" placeholder="email@example.com">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="peneliti_internal[${rowCount}][asal_instansi]" placeholder="Institusi">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="peneliti_internal[${rowCount}][posisi]" placeholder="Posisi">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger remove-row" data-repeatable-action="remove-peneliti" title="Hapus baris">
                <i class="fas fa-trash me-1"></i>Hapus
            </button>
        </td>
    `;

  tbody.appendChild(newRow);
}

/**
 * Remove peneliti row
 */
function removePenelitiRow(btn) {
  const tbody = document.getElementById("penelitiBody");
  if (tbody.querySelectorAll("tr").length > 1) {
    btn.closest("tr").remove();
  } else {
    alert("Minimal 1 peneliti internal harus ada");
  }
}

/**
 * Add new mahasiswa row
 */
function addMahasiswaRow() {
  const tbody = document.getElementById("mahasiswaBody");
  if (!tbody) return;

  const rowCount = tbody.querySelectorAll("tr").length;

  const newRow = document.createElement("tr");
  newRow.className = "mahasiswa-row";
  newRow.dataset.index = rowCount;
  newRow.innerHTML = `
        <td>
            <input type="text" class="form-control form-control-sm" name="mahasiswa[${rowCount}][nama]" placeholder="Nama mahasiswa">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="mahasiswa[${rowCount}][nim]" placeholder="NIM">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="mahasiswa[${rowCount}][program_studi_id]" placeholder="Program Studi">
        </td>
        <td>
            <input type="email" class="form-control form-control-sm" name="mahasiswa[${rowCount}][email]" placeholder="Email">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger remove-row" data-repeatable-action="remove-mahasiswa" title="Hapus baris">
                <i class="fas fa-trash me-1"></i>Hapus
            </button>
        </td>
    `;

  tbody.appendChild(newRow);
}

/**
 * Remove mahasiswa row
 */
function removeMahasiswaRow(btn) {
  btn.closest("tr").remove();
}

/**
 * Add new eksternal row
 */
function addEksternalRow() {
  const tbody = document.getElementById("eksternalBody");
  if (!tbody) return;

  const rowCount = tbody.querySelectorAll("tr").length;

  const newRow = document.createElement("tr");
  newRow.className = "eksternal-row";
  newRow.dataset.index = rowCount;
  newRow.innerHTML = `
        <td>
            <input type="text" class="form-control form-control-sm" name="anggota_eksternal[${rowCount}][nama]" placeholder="Nama">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="anggota_eksternal[${rowCount}][institusi]" placeholder="Institusi">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="anggota_eksternal[${rowCount}][posisi]" placeholder="Posisi">
        </td>
        <td>
            <input type="email" class="form-control form-control-sm" name="anggota_eksternal[${rowCount}][email]" placeholder="Email">
        </td>
        <td>
            <select class="form-select form-select-sm" name="anggota_eksternal[${rowCount}][tipe]">
                <option value="Profesional">Profesional</option>
                <option value="PTU">PTU</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger remove-row" data-repeatable-action="remove-eksternal" title="Hapus baris">
                <i class="fas fa-trash me-1"></i>Hapus
            </button>
        </td>
    `;

  tbody.appendChild(newRow);
}

/**
 * Remove eksternal row
 */
function removeEksternalRow(btn) {
  btn.closest("tr").remove();
}

function initializeRepeatableHandlers() {
  if (window.__proposalRepeatableInitialized) {
    return;
  }
  window.__proposalRepeatableInitialized = true;

  document.addEventListener("click", function (event) {
    const button = event.target.closest("[data-repeatable-action]");
    if (!button) return;

    const action = button.getAttribute("data-repeatable-action");
    switch (action) {
      case "add-peneliti":
        addPenelitiRow();
        break;
      case "remove-peneliti":
        removePenelitiRow(button);
        break;
      case "add-mahasiswa":
        addMahasiswaRow();
        break;
      case "remove-mahasiswa":
        removeMahasiswaRow(button);
        break;
      case "add-eksternal":
        addEksternalRow();
        break;
      case "remove-eksternal":
        removeEksternalRow(button);
        break;
      default:
        break;
    }
  });
}

window.proposalRepeatableInit = initializeRepeatableHandlers;

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initializeRepeatableHandlers, {
    once: true,
  });
} else {
  initializeRepeatableHandlers();
}
