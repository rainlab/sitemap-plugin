<?php namespace RainLab\Sitemap\Controllers;

use URL;
use Backend;
use Request;
use Redirect;
use BackendMenu;
use Cms\Classes\Theme;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;
use RainLab\Sitemap\Models\Definition;
use ApplicationException;
use RainLab\Sitemap\Classes\DefinitionItem as SitemapItem;
use Exception;

/**
 * Definitions Back-end Controller
 */
class Definitions extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController'
    ];

    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('RainLab.Sitemap', 'definitions');

        $this->addJs('/modules/backend/assets/js/october.treeview.js', 'core');
        $this->addJs('/plugins/rainlab/sitemap/assets/js/sitemap-definitions.js');
    }

    /**
     * Index action. Find or create a new Definition model,
     * then redirect to the update form.
     */
    public function index()
    {
        try {
            if (!$theme = Theme::getEditTheme())
                throw new ApplicationException('Unable to find the active theme.');

            $model = Definition::firstOrCreate(['theme' => $theme->getDirName()]);
            $updateUrl = sprintf('rainlab/sitemap/definitions/update/%s', $model->getKey());
            return Redirect::to(Backend::url($updateUrl));
        }
        catch (Exception $ex) {
            $this->handleError($ex);
        }
    }

    /**
     * Update action. Add the theme object to the page vars.
     */
    public function update($recordId = null, $context = null)
    {
        try {
            if (!$theme = Theme::getEditTheme())
                throw new ApplicationException('Unable to find the active theme.');

            $this->vars['theme'] = $theme;
            $this->vars['themeName'] = $theme->getConfigValue('name', $theme->getDirName());
            $this->vars['sitemapUrl'] = URL::to('/sitemap.xml');

            return $this->asExtension('FormController')->update($recordId, $context);
        }
        catch (Exception $ex) {
            $this->handleError($ex);
        }
    }

    public function update_onSave($recordId = null, $context = null)
    {
        // @todo Remove this method?
        // traceLog($_POST);
        return $this->asExtension('FormController')->update_onSave($recordId, $context);
    }

    public function onGetMenuItemTypeInfo()
    {
        $type = Request::input('type');

        return [
            'menuItemTypeInfo' => SitemapItem::getTypeInfo($type)
        ];
    }
}