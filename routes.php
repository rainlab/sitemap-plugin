<?php

Route::get('sitemap.xml', [\RainLab\Sitemap\Handlers\SitemapHandler::class, 'sitemap']);
