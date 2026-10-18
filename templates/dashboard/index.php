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

use Webify\Base\Infrastructure\Service\View;

/** @var View $view */

// The page's own script is registered rather than written out here. A `<script>`
// embedded in this markup does not run when the markup is swapped into a page
// that is already open, whereas the layout emits this as a real script element,
// which a client-side navigation library will execute.
$view->pushScript(<<<'JS'
	function dashboardPage() {
	    return {
	        init() {
	            // Dashboard-specific logic can go here
	        },
	    };
	}
	JS, 'dashboard-page');
?>
<div class="space-y-8" x-data="dashboardPage()">
    <!-- Page header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Welcome back. Here's what's happening today.</p>
        </div>
        <a href="#" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white rounded-lg accent-bg accent-bg-hover transition-colors shrink-0">
            <svg class="w-4 h-4"><use href="#plus-lg"/></svg>
            New Article
        </a>
    </div>

    <!-- Stat cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <!-- Articles -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Articles</p>
                    <p class="text-2xl font-bold mt-1">24</p>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center accent-soft-bg">
                    <svg class="w-5 h-5 accent-text"><use href="#file-text"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-sm">
                <span class="text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5"><use href="#arrow-up-right"/></svg>
                    +3
                </span>
                <span class="text-gray-400 dark:text-gray-500">this week</span>
            </div>
        </div>

        <!-- Pages -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pages</p>
                    <p class="text-2xl font-bold mt-1">8</p>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center accent-soft-bg">
                    <svg class="w-5 h-5 accent-text"><use href="#file-earmark"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-sm">
                <span class="text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5"><use href="#arrow-up-right"/></svg>
                    +1
                </span>
                <span class="text-gray-400 dark:text-gray-500">this week</span>
            </div>
        </div>

        <!-- Users -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Users</p>
                    <p class="text-2xl font-bold mt-1">12</p>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center accent-soft-bg">
                    <svg class="w-5 h-5 accent-text"><use href="#people"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-sm">
                <span class="text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5"><use href="#arrow-up-right"/></svg>
                    +2
                </span>
                <span class="text-gray-400 dark:text-gray-500">this month</span>
            </div>
        </div>

        <!-- Media -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Media Files</p>
                    <p class="text-2xl font-bold mt-1">156</p>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center accent-soft-bg">
                    <svg class="w-5 h-5 accent-text"><use href="#images"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-sm">
                <span class="text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5"><use href="#arrow-up-right"/></svg>
                    +12
                </span>
                <span class="text-gray-400 dark:text-gray-500">this week</span>
            </div>
        </div>
    </div>

    <!-- Content grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Recent articles -->
        <div class="xl:col-span-2 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 transition-colors">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-800">
                <h2 class="font-semibold">Recent Articles</h2>
                <a href="#" class="text-sm font-medium accent-text hover:underline">View all</a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-800">
                <?php
				$articles = [
					['title' => 'Getting Started with WebifyCMS', 'status' => 'published', 'date' => 'Aug 25, 2026'],
					['title' => 'Customizing Your Theme', 'status' => 'published', 'date' => 'Aug 23, 2026'],
					['title' => 'Understanding the Plugin System', 'status' => 'draft', 'date' => 'Aug 20, 2026'],
					['title' => 'Building Custom Extensions', 'status' => 'draft', 'date' => 'Aug 18, 2026'],
				];
foreach ($articles as $article) { ?>
                <div class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="min-w-0">
                        <p class="text-sm font-medium truncate"><?= $article['title']; ?></p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5"><?= $article['date']; ?></p>
                    </div>
                    <span class="ml-4 shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= 'published' === $article['status'] ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400'; ?>">
                        <?= ucfirst($article['status']); ?>
                    </span>
                </div>
                <?php } ?>
            </div>
        </div>

        <!-- Quick actions + activity -->
        <div class="space-y-6">
            <!-- Quick actions -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 transition-colors">
                <h2 class="font-semibold mb-4">Quick Actions</h2>
                <div class="space-y-2">
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg class="w-4 h-4 accent-text"><use href="#plus-lg"/></svg>
                        New Article
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg class="w-4 h-4 accent-text"><use href="#file-earmark-plus"/></svg>
                        New Page
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg class="w-4 h-4 accent-text"><use href="#upload"/></svg>
                        Upload Media
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg class="w-4 h-4 accent-text"><use href="#person-plus"/></svg>
                        Add User
                    </a>
                </div>
            </div>

            <!-- Recent activity -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 transition-colors">
                <h2 class="font-semibold mb-4">Recent Activity</h2>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 mt-2 shrink-0"></div>
                        <div>
                            <p class="text-sm">Article <span class="font-medium">published</span></p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-2 h-2 rounded-full bg-blue-500 mt-2 shrink-0"></div>
                        <div>
                            <p class="text-sm">New user <span class="font-medium">registered</span></p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">5 hours ago</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-2 h-2 rounded-full bg-amber-500 mt-2 shrink-0"></div>
                        <div>
                            <p class="text-sm">Media file <span class="font-medium">uploaded</span></p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">1 day ago</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 mt-2 shrink-0"></div>
                        <div>
                            <p class="text-sm">Page <span class="font-medium">updated</span></p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">2 days ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
