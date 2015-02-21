<?php namespace RainLab\Sitemap;

use Backend;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

/**
 * Sitemap Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'rainlab.sitemap::lang.plugin.name',
            'description' => 'rainlab.sitemap::lang.plugin.description',
            'author'      => 'Alexey Bobkov, Samuel Georges',
            'icon'        => 'icon-sitemap',
            'homepage'    => 'https://github.com/rainlab/sitemap-plugin'
        ];
    }

    public function registerSettings()
    {
        return [
            'definitions' => [
                'label'       => 'rainlab.sitemap::lang.plugin.name',
                'description' => 'rainlab.sitemap::lang.plugin.description',
                'icon'        => 'icon-sitemap',
                'url'         => Backend::url('rainlab/sitemap/definitions'),
                'category'    => SettingsManager::CATEGORY_CMS
            ]
        ];
    }
}
