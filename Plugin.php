<?php namespace RainLab\Sitemap;

use System\Classes\PluginBase;

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
            'name'        => 'Sitemap',
            'description' => 'Generate sitemap.xml files',
            'author'      => 'Alexey Bobkov, Samuel Georges',
            'icon'        => 'icon-sitemap'
        ];
    }

}
