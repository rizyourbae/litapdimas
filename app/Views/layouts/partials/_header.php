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

    <!-- Custom CSS (opsional) -->
    <link rel="stylesheet" href="<?= base_url('assets/custom/css/style.css') ?>">
</head>