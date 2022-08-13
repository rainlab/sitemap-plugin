<?php

use RainLab\Sitemap\Http\Controllers\SitemapController;

Route::get('sitemap.xml', [SitemapController::class, 'sitemap']);
