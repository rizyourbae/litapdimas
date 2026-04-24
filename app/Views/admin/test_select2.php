<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Test Select2</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Select 1 (with data-select2)</label>
                <select class="form-select" data-select2>
                    <option value="">-- Pilih --</option>
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Select 2 (with data-select2)</label>
                <select class="form-select" data-select2>
                    <option value="">-- Pilih --</option>
                    <option value="a">Option A</option>
                    <option value="b">Option B</option>
                    <option value="c">Option C</option>
                </select>
            </div>
        </div>
        <hr>
        <div class="alert alert-info">
            <strong>Testing:</strong>
            <ul>
                <li>Check if dropdowns have Select2 styling (blue border, searchable, etc)</li>
                <li>Try clicking on dropdown to see if it opens</li>
                <li>Try typing to search options</li>
                <li>Open browser console (F12) and check for errors</li>
            </ul>
        </div>
    </div>
</div>

<?= $this->endSection() ?>