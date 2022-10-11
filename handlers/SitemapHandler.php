<?php namespace RainLab\Sitemap\Handlers;

use Log;
use View;
use Response;
use Cms\Classes\Theme;
use RainLab\Sitemap\Models\Definition;
use Illuminate\Routing\Controller as ControllerBase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * SitemapHandler
 */
class SitemapHandler extends ControllerBase
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
            return Response::make(View::make('cms::404'), 404);
        }

        return Response::make($definition->generateSitemap())
            ->header('Content-Type', 'application/xml');
    }
}
