<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)">
    <title><?= esc($title ?? 'Litapdimas') ?> | Litapdimas</title>

    <!-- Fonts -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        crossorigin="anonymous"
        media="print" onload="this.media='all'">

    <!-- OverlayScrollbars -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.css') ?>">

    <!-- Select2 + Bootstrap 5 theme -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
        crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        crossorigin="anonymous">

    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet"
        href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css"
        crossorigin="anonymous">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('custom/css/style.css') ?>">

    <!-- Flatpickr Datepicker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">

    <!-- Page-specific Styles (Optional) -->
    <?= $this->renderSection('styles') ?>
</head>