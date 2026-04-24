<?= $this->include('layouts/partials/_header') ?>

<body class="layout-fixed sidebar-expand-lg sidebar-mini bg-body-tertiary">
    <div class="app-wrapper">

        <?= $this->include('layouts/partials/_navbar') ?>

        <?= $this->include('layouts/partials/_sidebar') ?>

        <main class="app-main">
            <!-- App Content Header (Breadcrumb area) -->
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0"><?= esc($title ?? 'Halaman') ?></h3>
                        </div>
                        <div class="col-sm-6">
                            <?= $this->include('layouts/partials/_breadcrumb') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- App Content -->
            <div class="app-content">
                <div class="container-fluid">
                    <?= $this->include('layouts/partials/_alerts') ?>
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </main>

        <?= $this->include('layouts/partials/_footer') ?>

        <!-- Global Scripts & Libraries -->
        <?= $this->include('layouts/partials/_scripts') ?>
    </div>

    <!-- Page-specific Scripts (Optional) -->
    <?= $this->renderSection('scripts') ?>
</body>

</html>