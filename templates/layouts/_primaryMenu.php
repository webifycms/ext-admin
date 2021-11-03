<?php
/**
 * @var $asset
 */
?>

<div
        x-show="primaryMenuOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="primary-menu absolute origin-top-left left-0 top-14 bg-white rounded-b-xl shadow-xl pt-10 pb-8 px-16
        w-full
        overflow-y-auto z-10"
>
    <ul class="grid grid-cols-3 xl:grid-cols-6 gap-4 xl:gap-8">
        <?php foreach (view()->params['primaryMenuItems'] as $item) : ?>
            <li>
                <a href="<?= url($item['route']) ?>" class="block text-center" role="menuitem">
                    <svg class="block mx-auto w-6 h-6 xl:w-8 xl:h-8" width="32" height="32" fill="currentColor">
                        <use xlink:href="<?= $asset->baseUrl . '/icons/icons.svg#' . $item['icon'] ?>"/>
                    </svg>
                    <span class="block mt-2 mx-auto"><?= $item['label'] ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
