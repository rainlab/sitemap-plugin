<?php

namespace RainLab\Sitemap\Http\Controllers;

use Illuminate\Routing\Controller;

use App;
use Log;
use Response;
use Cms\Classes\Theme;
use Cms\Classes\Controller as CmsController;
use RainLab\Sitemap\Models\Definition;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFound;

class SitemapController extends Controller
{
    public function sitemap()
    {
        $themeActive = Theme::getActiveTheme()->getDirName();

        try {
            $definition = Definition::where('theme', $themeActive)->firstOrFail();
        } catch (ModelNotFound $e) {
            Log::info(trans('rainlab.sitemap::lang.definition.not_found'));
    
            return App::make(Controller::class)->setStatusCode(404)->run('/404');
        }
    
        return Response::make($definition->generateSitemap())
            ->header('Content-Type', 'application/xml');
    }
}
