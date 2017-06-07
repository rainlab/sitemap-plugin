<?php

use Cms\Classes\Theme;
use Cms\Classes\Controller;
use RainLab\Sitemap\Models\Definition;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFound;

Route::get('sitemap.xml', function()
{
    $themeActive = Theme::getActiveTheme()->getDirName();

    try {
    	$definition = Definition::where('theme', $themeActive)->firstOrFail();
    } catch (ModelNotFound $e) {
    	Log::info(trans('rainlab.sitemap::lang.definition.not_found'));

    	return App::make(Controller::class)->setStatusCode(404)->run('/404');
    }

    return Response::make($definition->generateSitemap())
        ->header("Content-Type", "application/xml");
});
