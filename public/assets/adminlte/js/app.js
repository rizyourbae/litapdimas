document.addEventListener("DOMContentLoaded", function () {
  // =======================================================
  // 1. SETUP NOTYF (TOAST NOTIFICATION)
  // =======================================================
  const notyf = new Notyf({
    duration: 4000,
    position: { x: "right", y: "top" },
    ripple: true,
    dismissible: true,
    types: [
      {
        type: "success",
        background: "#28a745",
        icon: { className: "bi bi-check-circle", tagName: "i", color: "white" },
      },
      {
        type: "error",
        background: "#dc3545",
        icon: { className: "bi bi-exclamation-triangle", tagName: "i", color: "white" },
      },
      // 👇 TAMBAHKAN TIPE WARNING (KUNING/ORANGE)
      {
        type: "warning",
        background: "#ffc107",
        icon: { className: "bi bi-exclamation-circle", tagName: "i", color: "white" },
      },
    ],
  });

  // Ambil data dari Bridge
  const flashData = document.getElementById("flash-data");
  const successMsg = flashData.getAttribute("data-success");
  const errorMsg = flashData.getAttribute("data-error");
  const warningMsg = flashData.getAttribute("data-warning"); // 👈 BACA DATA WARNING

  // Trigger Notyf
  if (successMsg) {
    notyf.success(successMsg);
  }
  if (errorMsg) {
    notyf.error(errorMsg);
  }
  if (warningMsg) {
    // Panggil custom type 'warning' yang baru kita buat
    notyf.open({
      type: "warning",
      message: warningMsg,
    });
  }

  // =======================================================
  // 2. GLOBAL DELETE CONFIRMATION (SWEETALERT2)
  // =======================================================
  // Cara pakai: Tambahkan class "btn-delete" dan atribut "data-id" pada tombol hapus
  // Pastikan form hapus memiliki ID: "delete-form-{id}"
  document.body.addEventListener("click", function (e) {
    // Gunakan Event Delegation biar aman untuk elemen dinamis (DataTable)
    const btnDelete = e.target.closest(".btn-delete");

    if (btnDelete) {
      e.preventDefault();
      const id = btnDelete.getAttribute("data-id");
      const form = document.getElementById(`delete-form-${id}`);

      if (!form) {
        console.error(`Form delete-form-${id} tidak ditemukan!`);
        return;
      }

      Swal.fire({
        title: "Hapus data ini?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    }
  });

  // =======================================================
  // 4. AUTO INIT CHOICES.JS (SEARCHABLE DROPDOWN)
  // =======================================================
  const choiceElements = document.querySelectorAll(".choices-single");

  choiceElements.forEach((element) => {
    new Choices(element, {
      searchEnabled: true, // Aktifkan pencarian
      searchPlaceholderValue: "Ketik untuk mencari...",
      itemSelectText: "", // Hapus teks "Press to select" yg ganggu
      shouldSort: false, // Jangan urutkan ulang (biar sesuai urutan DB)
      allowHTML: true, // Izinkan HTML (aman untuk select option biasa)
      removeItemButton: true, // Tombol 'x' untuk clear selection
    });
  });

  const chartCanvas = document.getElementById("categoryChart");

  if (chartCanvas) {
    // Ambil data dari attribute HTML
    // JSON.parse wajib dilakukan karena data dikirim sebagai string JSON
    const labels = JSON.parse(chartCanvas.getAttribute("data-labels"));
    const values = JSON.parse(chartCanvas.getAttribute("data-values"));

    const ctx = chartCanvas.getContext("2d");
    new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: labels,
        datasets: [
          {
            data: values,
            backgroundColor: ["#f56954", "#00a65a", "#f39c12", "#00c0ef", "#3c8dbc", "#d2d6de"],
          },
        ],
      },
      options: {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
          legend: {
            position: "right",
          },
        },
      },
    });
  }

  // =======================================================
  // 6. GLOBAL MAP VIEWER (Untuk Detail Page)
  // =======================================================
  const viewers = document.querySelectorAll(".map-viewer");

  viewers.forEach((el) => {
    const lat = el.getAttribute("data-lat");
    const lng = el.getAttribute("data-lng");
    const label = el.getAttribute("data-label") || "Lokasi";

    if (lat && lng) {
      const map = L.map(el, {
        center: [lat, lng],
        zoom: 15,
        zoomControl: false,
        dragging: false,
        scrollWheelZoom: false,
      });

      L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OSM",
      }).addTo(map);

      L.marker([lat, lng]).addTo(map).bindPopup(label).openPopup();
    }
  });

  // =======================================================
  // 7. GLOBAL MAP PICKER (SMART EDIT & ADD)
  // =======================================================
  document.addEventListener("shown.bs.modal", function (event) {
    const modal = event.target;
    const picker = modal.querySelector(".map-picker");

    if (picker) {
      if (picker._leaflet_id) return; // Prevent double init

      // Ambil ID input target
      const inputLatId = picker.getAttribute("data-input-lat");
      const inputLngId = picker.getAttribute("data-input-lng");
      const inputLat = document.getElementById(inputLatId);
      const inputLng = document.getElementById(inputLngId);

      // LOGIC PINTAR: Cek Initial Value (Data Lama)
      // Kalau ada data lama (Edit Mode), pakai itu.
      // Kalau kosong (Add Mode), pakai Default Jakarta.
      const initialLat = picker.getAttribute("data-initial-lat");
      const initialLng = picker.getAttribute("data-initial-lng");

      // Default Jakarta
      let startLat = -0.5668434417332966;
      let startLng = 117.11089700460437;

      // Jika initial data ada dan valid (tidak kosong/null)
      if (initialLat && initialLng && !isNaN(initialLat)) {
        startLat = parseFloat(initialLat);
        startLng = parseFloat(initialLng);
      }

      // Init Map di posisi startLat/startLng
      const map = L.map(picker).setView([startLat, startLng], 15);

      L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "&copy; OpenStreetMap",
      }).addTo(map);

      const marker = L.marker([startLat, startLng], { draggable: true }).addTo(map);

      // Fungsi Update Input
      function updateCoords(lat, lng) {
        if (inputLat) inputLat.value = lat;
        if (inputLng) inputLng.value = lng;
      }

      // Kalau mode ADD (kosong), kita update input biar gak kosong saat pertama buka
      // Kalau mode EDIT, jangan update input dulu (biarkan nilai asli)
      if (!initialLat) {
        updateCoords(startLat, startLng);
      }

      marker.on("dragend", function (e) {
        const pos = marker.getLatLng();
        updateCoords(pos.lat, pos.lng);
      });

      map.on("click", function (e) {
        marker.setLatLng(e.latlng);
        updateCoords(e.latlng.lat, e.latlng.lng);
      });

      setTimeout(() => {
        map.invalidateSize();
      }, 200);
    }
  });

  // =======================================================
  // 8. MAINTENANCE MODAL HELPER
  // =======================================================
  const modalFinish = document.getElementById("modalFinishMaint");
  if (modalFinish) {
    modalFinish.addEventListener("show.bs.modal", function (event) {
      const button = event.relatedTarget;
      const id = button.getAttribute("data-maint-id");
      const vendor = button.getAttribute("data-vendor");

      // Update Text
      modalFinish.querySelector("#finishVendorName").textContent = vendor;

      // Update Form Action URL
      const form = modalFinish.querySelector("#formFinishMaint");
      form.action = "/maintenance/" + id + "/complete";
    });
  }

  // =======================================================
  // 9. STOCK OPNAME SCANNER LOGIC
  // =======================================================
  const readerEl = document.getElementById("reader");

  if (readerEl) {
    // 1. Setup Variables
    const opnameId = readerEl.getAttribute("data-opname-id");
    const csrfName = readerEl.getAttribute("data-csrf-name"); // Nama token (misal: csrf_test_name)

    const beepOk = document.getElementById("beep-ok");
    const beepError = document.getElementById("beep-error");
    const btnToggle = document.getElementById("btn-toggle-scan");
    const statusBadge = document.getElementById("scan-status");
    const btnManual = document.getElementById("btn-manual-submit");
    const inputManual = document.getElementById("manual-code");

    let html5QrcodeScanner;
    let isScanning = false;
    let isProcessing = false;

    // 2. Fungsi Start/Stop Scanner
    btnToggle.addEventListener("click", function () {
      if (!isScanning) {
        // Start
        html5QrcodeScanner = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        html5QrcodeScanner
          .start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure)
          .then(() => {
            isScanning = true;
            btnToggle.innerHTML = '<i class="bi bi-stop-circle me-2"></i> Stop Kamera';
            btnToggle.classList.replace("btn-primary", "btn-danger");
            statusBadge.className = "badge bg-success";
            statusBadge.innerText = "Scanning...";
          })
          .catch((err) => {
            console.error("Error starting scanner", err);
            alert("Gagal mengakses kamera. Pastikan izin diberikan dan menggunakan HTTPS/Localhost.");
          });
      } else {
        // Stop
        if (html5QrcodeScanner) {
          html5QrcodeScanner.stop().then(() => {
            isScanning = false;
            btnToggle.innerHTML = '<i class="bi bi-play-circle me-2"></i> Mulai Kamera';
            btnToggle.classList.replace("btn-danger", "btn-primary");
            statusBadge.className = "badge bg-secondary";
            statusBadge.innerText = "Standby";
          });
        }
      }
    });

    // 3. Callback Sukses Scan
    function onScanSuccess(decodedText, decodedResult) {
      if (isProcessing) return;
      isProcessing = true;
      sendScanData(decodedText);

      // Debounce 1.5 detik
      setTimeout(() => {
        isProcessing = false;
      }, 1500);
    }

    function onScanFailure(error) {
      // No-op (biar console gak penuh warning frame kosong)
    }

    // 4. Manual Input Event
    btnManual.addEventListener("click", function () {
      if (inputManual.value.trim() !== "") {
        sendScanData(inputManual.value.trim());
        inputManual.value = "";
      }
    });

    inputManual.addEventListener("keypress", function (e) {
      if (e.key === "Enter" && inputManual.value.trim() !== "") {
        sendScanData(inputManual.value.trim());
        inputManual.value = "";
      }
    });

    // 5. AJAX Send Data
    function sendScanData(code) {
      // Ambil value token terbaru dari meta tag (karena token bisa berubah/regenerate)
      const csrfHash = document.querySelector(`meta[name="${csrfName}"]`).getAttribute("content");

      fetch(`/opname/${opnameId}/process-scan`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": csrfHash, // Kirim token di header
        },
        body: JSON.stringify({ code: code }),
      })
        .then((response) => response.json())
        .then((data) => {
          handleScanResult(data, code);
        })
        .catch((err) => {
          console.error(err);
          alert("Gagal koneksi ke server.");
        });
    }

    // 6. Handle UI Updates
    function handleScanResult(data, code) {
      const logContainer = document.getElementById("scan-log");
      const emptyMsg = document.querySelector(".empty-msg");
      if (emptyMsg) emptyMsg.remove();

      let itemHtml = "";
      const timeNow = new Date().toLocaleTimeString();

      if (data.status === "success") {
        if (beepOk) beepOk.play();
        flashOverlay("success");
        updateStats(); // Update angka stat

        itemHtml = `
                    <div class="list-group-item list-group-item-success d-flex justify-content-between align-items-center animate__animated animate__fadeIn">
                        <div>
                            <i class="bi bi-check-circle-fill me-2"></i> <strong>${data.asset ? data.asset.name : "Item"}</strong>
                            <div class="small">${code}</div>
                        </div>
                        <span class="small">${timeNow}</span>
                    </div>`;
      } else if (data.status === "info") {
        // Sudah discan
        itemHtml = `
                    <div class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-info-circle-fill me-2"></i> ${data.message}
                            <div class="small">${code}</div>
                        </div>
                        <span class="small">${timeNow}</span>
                    </div>`;
      } else {
        // Error / Salah Kamar
        if (beepError) beepError.play();
        flashOverlay("error");

        itemHtml = `
                    <div class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center animate__animated animate__shakeX">
                        <div>
                            <i class="bi bi-x-circle-fill me-2"></i> ${data.message}
                            <div class="small">${code}</div>
                        </div>
                        <span class="small">${timeNow}</span>
                    </div>`;
      }

      logContainer.insertAdjacentHTML("afterbegin", itemHtml);
    }

    function flashOverlay(type) {
      const overlay = document.getElementById("scan-overlay");
      if (!overlay) return;

      overlay.className = "position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white";

      if (type === "success") {
        overlay.classList.add("bg-success", "bg-opacity-75");
        overlay.innerHTML = '<i class="bi bi-check-circle-fill" style="font-size: 5rem;"></i><h3 class="fw-bold">FOUND!</h3>';
      } else {
        overlay.classList.add("bg-danger", "bg-opacity-75");
        overlay.innerHTML = '<i class="bi bi-x-circle-fill" style="font-size: 5rem;"></i><h3 class="fw-bold">ERROR!</h3>';
      }

      setTimeout(() => {
        overlay.classList.add("d-none");
        overlay.classList.remove("d-flex");
      }, 800);
    }

    function updateStats() {
      const elFound = document.getElementById("stat-found");
      const elMissing = document.getElementById("stat-missing");

      if (elFound && elMissing) {
        let found = parseInt(elFound.innerText) + 1;
        let missing = parseInt(elMissing.innerText) - 1;
        elFound.innerText = found;
        elMissing.innerText = missing;
      }
    }
  }
});
