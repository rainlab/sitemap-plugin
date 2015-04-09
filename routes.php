<?php

use Cms\Classes\Theme;
use RainLab\Sitemap\Models\Definition;

Route::get('sitemap.xml', function (){

    $themeActive = Theme::getActiveTheme()->getDirName();

    return Response::make(Definition::where('theme', $themeActive)->firstOrFail()->generateSitemap())
        ->header("Content-Type", "application/xml");

});
