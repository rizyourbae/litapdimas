<?php

/**
 * dosen/proposals/_partials/_step4.php
 * Step 4: Unggah Berkas (File Upload)
 */
?>

<div class="card border-0 bg-light mb-4" style="border-radius:0.8rem;">
    <div class="card-body py-3">
        <h6 class="mb-1"><i class="fas fa-file-arrow-up text-danger me-1"></i> Unggah Berkas Proposal</h6>
        <p class="text-muted small mb-0">Format PDF, maksimal 2 MB per file. Tiga dokumen wajib harus tersedia sebelum lanjut.</p>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-4">
        <div class="card h-100" style="border-radius:0.8rem;">
            <div class="card-body">
                <label for="file_proposal" class="form-label fw-semibold">File Proposal <span class="text-danger">*</span></label>
                <div class="upload-zone card border-primary p-4 text-center"
                    id="file_proposalZone"
                    data-file-type="file_proposal"
                    ondrop="handleDrop(event)"
                    ondragover="handleDragOver(event)"
                    ondragout="handleDragOut(event)"
                    onclick="document.getElementById('file_proposal').click()"
                    style="cursor:pointer; border-style:dashed;">
                    <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                    <p class="mb-1 small"><strong>Klik atau seret file</strong></p>
                    <p class="text-muted small mb-0">PDF, max 2 MB</p>
                    <input type="file" id="file_proposal" name="file_proposal" class="d-none" accept=".pdf" onchange="handleFileSelect(event)">
                </div>
                <div class="upload-progress mt-2" style="display:none;">
                    <div class="progress" style="height:16px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%"></div>
                    </div>
                    <small class="text-muted">Uploading...</small>
                </div>
                <div class="upload-success mt-2" style="display:none;">
                    <p class="text-success mb-1"><i class="fas fa-check-circle me-1"></i> File berhasil dipilih</p>
                    <small class="text-muted" id="file_proposal_name"></small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100" style="border-radius:0.8rem;">
            <div class="card-body">
                <label for="file_rab" class="form-label fw-semibold">File RAB <span class="text-danger">*</span></label>
                <div class="upload-zone card border-primary p-4 text-center"
                    id="file_rabZone"
                    data-file-type="file_rab"
                    ondrop="handleDrop(event)"
                    ondragover="handleDragOver(event)"
                    ondragout="handleDragOut(event)"
                    onclick="document.getElementById('file_rab').click()"
                    style="cursor:pointer; border-style:dashed;">
                    <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                    <p class="mb-1 small"><strong>Klik atau seret file</strong></p>
                    <p class="text-muted small mb-0">PDF, max 2 MB</p>
                    <input type="file" id="file_rab" name="file_rab" class="d-none" accept=".pdf" onchange="handleFileSelect(event)">
                </div>
                <div class="upload-progress mt-2" style="display:none;">
                    <div class="progress" style="height:16px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%"></div>
                    </div>
                    <small class="text-muted">Uploading...</small>
                </div>
                <div class="upload-success mt-2" style="display:none;">
                    <p class="text-success mb-1"><i class="fas fa-check-circle me-1"></i> File berhasil dipilih</p>
                    <small class="text-muted" id="file_rab_name"></small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100" style="border-radius:0.8rem;">
            <div class="card-body">
                <label for="file_similarity" class="form-label fw-semibold">File Similarity Check <span class="text-danger">*</span></label>
                <div class="upload-zone card border-primary p-4 text-center"
                    id="file_similarityZone"
                    data-file-type="file_similarity"
                    ondrop="handleDrop(event)"
                    ondragover="handleDragOver(event)"
                    ondragout="handleDragOut(event)"
                    onclick="document.getElementById('file_similarity').click()"
                    style="cursor:pointer; border-style:dashed;">
                    <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                    <p class="mb-1 small"><strong>Klik atau seret file</strong></p>
                    <p class="text-muted small mb-0">PDF, max 2 MB</p>
                    <input type="file" id="file_similarity" name="file_similarity" class="d-none" accept=".pdf" onchange="handleFileSelect(event)">
                </div>
                <div class="upload-progress mt-2" style="display:none;">
                    <div class="progress" style="height:16px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%"></div>
                    </div>
                    <small class="text-muted">Uploading...</small>
                </div>
                <div class="upload-success mt-2" style="display:none;">
                    <p class="text-success mb-1"><i class="fas fa-check-circle me-1"></i> File berhasil dipilih</p>
                    <small class="text-muted" id="file_similarity_name"></small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card" style="border-radius:0.8rem;">
    <div class="card-header">
        <h6 class="mb-0"><i class="fas fa-paperclip text-secondary me-1"></i> File Pendukung (Opsional)</h6>
    </div>
    <div class="card-body">
        <label for="file_pendukung" class="form-label">Lampiran Tambahan</label>
        <div class="upload-zone card border-secondary p-4 text-center"
            id="file_pendukungZone"
            ondrop="handleDrop(event)"
            ondragover="handleDragOver(event)"
            ondragout="handleDragOut(event)"
            onclick="document.getElementById('file_pendukung').click()"
            style="cursor:pointer; border-style:dashed;">
            <i class="fas fa-cloud-upload-alt fa-2x text-secondary mb-2"></i>
            <p class="mb-1 small"><strong>Klik atau seret file</strong></p>
            <p class="text-muted small mb-0">PDF, max 2 MB, boleh lebih dari satu file</p>
            <input type="file" id="file_pendukung" name="file_pendukung[]" class="d-none" accept=".pdf" onchange="handleFileSelect(event)" multiple>
        </div>

        <div id="pendukungFilesList" class="mt-3"></div>
    </div>
</div>

<script src="<?= base_url('custom/js/proposal-upload.js') ?>?v=20260427-01"></script>