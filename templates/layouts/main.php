<?php view()->beginContent('@Theme/templates/admin/layouts/main.php'); ?>

<?= view()->render('_sidebarNav') ?>

<header class="grid grid-flow-col gap-4 auto-cols-max place-items-center mb-3">
    <div>
        <svg width="50" height="50" fill="currentColor">
            <use xlink:href="<?= view()->theme->baseUrl . '/images/icons.svg#speedometer' ?>" />
        </svg>
    </div>
    <div>
        <h1 class="text-xl font-semibold"><?= translate('admin', 'Dashboard') ?></h1>
        <p class="text-mute text-base"><?= translate('admin', 'The summary of your application can view from here.') ?></p>
    </div>
</header>

<?= $content ?>

<?php view()->endContent(); ?>