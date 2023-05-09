<?php declare(strict_types=1);

/**
 * The admin layout.
 *
 * @var string $content
 */
$asset = view()->params['adminAsset'];
?>

<?php view()->beginPage(); ?>
<!doctype html>
<html lang="<?php echo app()->language; ?>">

<head>
    <meta charset="<?php echo app()->charset; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo app()->name; ?></title>

    <?php view()->registerCsrfMetaTags(); ?>
    <?php view()->head(); ?>
</head>

<body class="relative h-full max-h-screen bg-secondary-100 text-secondary-600">
    <?php view()->beginBody(); ?>

    <header x-data="{ primaryMenuOpen: false }" class="sticky flex top-1 flex-wrap justify-start rounded-md shadow-md m-2 xl:m-5 z-50" id="header">
        <nav class="flex flex-wrap items-center justify-between bg-primary-500 w-full h-16 px-1 rounded-md z-20
                ring-1 ring-primary-600 shadow">
            <div>
                <a href="#" class="flex items-center px-4 text-white font-bold text-lg">
                    <svg class="w-8 h-8" width="32" height="32" fill="currentColor">
                        <use xlink:href="<?php echo $asset->baseUrl . '/icons/icons.svg#app'; ?>" />
                    </svg>
                    <span class="ml-2"><?php echo app()->name; ?></span>
                </a>
            </div>

            <div class="flex flex-grow justify-start items-center">
                <?php echo view()->render('_navbarLeft', compact('asset')); ?>

                <?php echo view()->render('_navbarRight', compact('asset')); ?>
            </div>
        </nav>

        <?php echo view()->render('_primaryMenu', compact('asset')); ?>
    </header>

    <main class="flex flex-wrap justify-start m-2 xl:m-5" id="main">
        <aside class="fixed w-28 xl:w-48 bg-white text-secondary-600 rounded-md shadow-md overflow-y-auto z-0" id="sidebar">
            <?php if (isset(view()->blocks['sidebarNav'])) { ?>
                <?php echo view()->blocks['sidebarNav']; ?>
            <?php } else { ?>
                <nav class="flex flex-col p-5">
                    <a href="<?php echo url(['index']); ?>" class="text-center">
                        <svg class="block mx-auto w-6 h-6 xl:w-8 xl:h-8" width="32" height="32" fill="currentColor">
                            <use xlink:href="<?php echo $asset->baseUrl . '/icons/icons.svg#columns-gap'; ?>" />
                        </svg>
                        <span class="block mt-2 mx-auto">Widgets</span>
                    </a>
                </nav>
            <?php } ?>
        </aside>

        <div class="w-full ml-32 xl:ml-56 min-h-full" id="content">
            <?php echo $content; ?>
        </div>
    </main>

    <?php view()->endBody(); ?>
</body>

</html>
<?php view()->endPage(); ?>