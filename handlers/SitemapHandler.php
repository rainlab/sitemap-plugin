<?php namespace RainLab\Sitemap\Handlers;

use App;
use Log;
use Response;
use Cms\Classes\Theme;
use Illuminate\Routing\Controller;
use RainLab\Sitemap\Models\Definition;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * SitemapHandler
 */
class SitemapHandler extends Controller
{
    /**
     * sitemap route: api/package/browse
     */
    public function sitemap()
    {
        $themeActive = Theme::getActiveTheme()->getDirName();

        try {
            $definition = Definition::where('theme', $themeActive)->firstOrFail();
        }
        catch (ModelNotFoundException $e) {
            Log::info(trans('rainlab.sitemap::lang.definition.not_found'));

            return App::make(Controller::class)->setStatusCode(404)->run('/404');
        }

        return Response::make($definition->generateSitemap())
            ->header('Content-Type', 'application/xml');
    }
}
