<?php namespace RainLab\Sitemap;

use Backend;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

class Plugin extends PluginBase
{
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
                'category'    => SettingsManager::CATEGORY_CMS,
                'permissions' => ['rainlab.sitemap.access_settings']
            ]
        ];
    }

    public function registerPermissions()
    {
        return [
            'rainlab.sitemap.access_settings' => [
                'tab'   => 'rainlab.sitemap::lang.plugin.name',
                'label' => 'rainlab.sitemap::lang.plugin.permissions.access_settings'
            ]
        ];
    }
}
