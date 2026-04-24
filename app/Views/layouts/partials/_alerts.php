<?php
$session = session();
$success = $session->getFlashdata('success') ?? '';
$error   = $session->getFlashdata('error')   ?? '';
$warning = $session->getFlashdata('warning') ?? '';
$info    = $session->getFlashdata('info')    ?? '';
$welcome = $session->getFlashdata('welcome') ?? '';
?>

<?php /* Data flash tersimpan di hidden div, dibaca oleh swal.js untuk ditampilkan sebagai toast */ ?>
<div id="page-flash"
    aria-hidden="true"
    data-welcome="<?= esc($welcome) ?>"
    data-success="<?= esc($success) ?>"
    data-error="<?= esc($error) ?>"
    data-warning="<?= esc($warning) ?>"
    data-info="<?= esc($info) ?>"></div>