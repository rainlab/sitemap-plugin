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

            return $this->redirectToThemeSitemap($theme);
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
        $this->bodyClass = 'compact-container';

        try {
            if (!$editTheme = Theme::getEditTheme())
                throw new ApplicationException('Unable to find the active theme.');

            $result = $this->asExtension('FormController')->update($recordId, $context);

            $model = $this->formGetModel();
            $theme = Theme::load($model->theme);

            /*
             * Not editing the active sitemap definition
             */
            if ($editTheme->getDirName() != $theme->getDirName()) {
                return $this->redirectToThemeSitemap($editTheme);
            }

            $this->vars['theme'] = $theme;
            $this->vars['themeName'] = $theme->getConfigValue('name', $theme->getDirName());
            $this->vars['sitemapUrl'] = URL::to('/sitemap.xml');

            return $result;
        }
        catch (Exception $ex) {
            $this->handleError($ex);
        }
    }

    public function onGetItemTypeInfo()
    {
        $type = Request::input('type');

        return [
            'sitemapItemTypeInfo' => SitemapItem::getTypeInfo($type)
        ];
    }

    //
    // Helpers
    //

    protected function redirectToThemeSitemap($theme)
    {
        $model = Definition::firstOrCreate(['theme' => $theme->getDirName()]);
        $updateUrl = sprintf('rainlab/sitemap/definitions/update/%s', $model->getKey());
        return Backend::redirect($updateUrl);
    }
}