<?php declare(strict_types=1);
view()->beginContent('@Theme/templates/admin/layouts/main.php'); ?>

<?php echo view()->render('_sidebarNav'); ?>

<header class="grid grid-flow-col gap-4 auto-cols-max place-items-center mb-3">
    <div>
        <svg width="50" height="50" fill="currentColor">
            <use xlink:href="<?php echo view()->theme->baseUrl . '/images/icons.svg#speedometer'; ?>" />
        </svg>
    </div>
    <div>
        <h1 class="text-xl font-semibold"><?php echo translate('admin', 'Dashboard'); ?></h1>
        <p class="text-mute text-base"><?php echo translate('admin', 'The summary of your application can view from here.'); ?></p>
    </div>
</header>

<?php echo $content; ?>

<?php view()->endContent(); ?>