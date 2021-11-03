<?php
/**
 * @var $asset
 */
?>

<div class="border-l border-secondary-300">
    <button
            class="flex items-center bg-transparent text-white px-4"
            :aria-expanded="primaryMenuOpen.toString()"
            aria-haspopup="true"
            @click="primaryMenuOpen = !primaryMenuOpen"
            @click.outside="primaryMenuOpen = false"
    >
        <span class="sr-only">Open main menu</span>
        <svg class="h-6 w-6" fill="currentColor">
            <use xlink:href="<?= $asset->baseUrl . '/icons/icons.svg#card-list' ?>"/>
        </svg>
    </button>
</div>
