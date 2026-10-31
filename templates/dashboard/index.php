<?php

/**
 * The file is part of the "webifycms/ext-admin", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/admin
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

use Webify\Base\Infrastructure\Service\Template\View;

/**
 * The dashboard page shown when someone opens the administration.
 *
 * It uses the theme's components, so it needs no stylesheet of its own.
 * Its behaviour is loaded as this extension's own file, pinned by owner name
 * so it cannot be replaced by another package.
 *
 * @var View $view
 */
?>
<?= $view->assetEntrypoint('webifycms-admin:js/dashboard.js'); ?>
<div class="canvas-stack canvas-stack--lg" x-data="dashboardPage()">
    <header class="canvas-page-header">
        <div>
            <h1 class="canvas-title">Dashboard</h1>
            <p class="canvas-muted">Welcome back. Here's what's happening today.</p>
        </div>
        <div class="canvas-page-actions">
            <a href="#" class="canvas-btn canvas-btn--primary">
                <svg class="canvas-icon" aria-hidden="true"><use href="#plus-lg"/></svg>
                New Article
            </a>
        </div>
    </header>

    <section class="canvas-grid">
        <?php
		$stats = [
			['label' => 'Articles', 'value' => '24', 'icon' => 'file-text', 'trend' => '+3', 'period' => 'this week'],
			['label' => 'Pages', 'value' => '8', 'icon' => 'file-earmark', 'trend' => '+1', 'period' => 'this week'],
			['label' => 'Users', 'value' => '12', 'icon' => 'people', 'trend' => '+2', 'period' => 'this month'],
			['label' => 'Media Files', 'value' => '156', 'icon' => 'images', 'trend' => '+12', 'period' => 'this week'],
		];
foreach ($stats as $stat) { ?>
        <article class="canvas-card">
            <div class="canvas-card__body canvas-stack">
                <div class="canvas-cluster--between canvas-cluster">
                    <div>
                        <p class="canvas-muted"><?= $stat['label']; ?></p>
                        <p class="canvas-title"><?= $stat['value']; ?></p>
                    </div>
                    <span class="canvas-admin__brand-mark">
                        <svg class="canvas-icon" aria-hidden="true"><use href="#<?= $stat['icon']; ?>"/></svg>
                    </span>
                </div>
                <p class="canvas-cluster">
                    <span class="canvas-badge canvas-badge--success">
                        <svg class="canvas-icon" aria-hidden="true"><use href="#arrow-up-right"/></svg>
                        <?= $stat['trend']; ?>
                    </span>
                    <span class="canvas-muted"><?= $stat['period']; ?></span>
                </p>
            </div>
        </article>
        <?php } ?>
    </section>

    <section class="canvas-grid canvas-grid--2">
        <article class="canvas-card">
            <header class="canvas-card__header">
                <h2 class="canvas-card__title">Recent Articles</h2>
                <a href="#" class="canvas-btn canvas-btn--ghost canvas-btn--sm">View all</a>
            </header>
            <table class="canvas-table">
                <tbody>
                    <?php
				$articles = [
					['title' => 'Getting Started with WebifyCMS', 'status' => 'published', 'date' => 'Aug 25, 2026'],
					['title' => 'Customizing Your Theme', 'status' => 'published', 'date' => 'Aug 23, 2026'],
					['title' => 'Understanding the Plugin System', 'status' => 'draft', 'date' => 'Aug 20, 2026'],
					['title' => 'Building Custom Extensions', 'status' => 'draft', 'date' => 'Aug 18, 2026'],
				];
foreach ($articles as $article) { ?>
                    <tr>
                        <td>
                            <?= $article['title']; ?>
                            <span class="canvas-muted"> — <?= $article['date']; ?></span>
                        </td>
                        <td>
                            <span class="canvas-badge canvas-badge--<?= 'published' === $article['status'] ? 'success' : 'warning'; ?>">
                                <?= ucfirst($article['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </article>

        <div class="canvas-stack canvas-stack--lg">
            <article class="canvas-card">
                <header class="canvas-card__header">
                    <h2 class="canvas-card__title">Quick Actions</h2>
                </header>
                <div class="canvas-card__body canvas-stack">
                    <?php
				$actions = [
					['icon' => 'plus-lg', 'label' => 'New Article'],
					['icon' => 'file-earmark-plus', 'label' => 'New Page'],
					['icon' => 'upload', 'label' => 'Upload Media'],
					['icon' => 'person-plus', 'label' => 'Add User'],
				];
foreach ($actions as $action) { ?>
                    <a href="#" class="canvas-nav-link">
                        <svg class="canvas-icon" aria-hidden="true"><use href="#<?= $action['icon']; ?>"/></svg>
                        <?= $action['label']; ?>
                    </a>
                    <?php } ?>
                </div>
            </article>

            <article class="canvas-card">
                <header class="canvas-card__header">
                    <h2 class="canvas-card__title">Recent Activity</h2>
                </header>
                <div class="canvas-card__body canvas-stack">
                    <?php
				$activity = [
					['text' => 'Article published', 'when' => '2 hours ago'],
					['text' => 'New user registered', 'when' => '5 hours ago'],
					['text' => 'Media file uploaded', 'when' => '1 day ago'],
					['text' => 'Page updated', 'when' => '2 days ago'],
				];
foreach ($activity as $event) { ?>
                    <div>
                        <p><?= $event['text']; ?></p>
                        <p class="canvas-muted"><?= $event['when']; ?></p>
                    </div>
                    <?php } ?>
                </div>
            </article>
        </div>
    </section>
</div>
