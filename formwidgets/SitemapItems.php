<?php namespace RainLab\Sitemap\FormWidgets;

use Request;
use RainLab\Sitemap\Classes\DefinitionItem as SitemapItem;
use Backend\Classes\FormWidgetBase;

/**
 * Sitemap items widget.
 *
 * @package october\backend
 * @author Alexey Bobkov, Samuel Georges
 */
class SitemapItems extends FormWidgetBase
{
    protected $typeListCache = false;
    protected $typeInfoCache = [];

    /**
     * {@inheritDoc}
     */
    protected $defaultAlias = 'sitemapitems';

    public $referenceRequiredMessage = 'rainlab.sitemap::lang.item.reference_required';

    public $urlRequiredMessage = 'rainlab.sitemap::lang.item.url_required';

    public $cmsPageRequiredMessage = 'rainlab.sitemap::lang.item.cms_page_required';

    /**
     * {@inheritDoc}
     */
    public function init()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('sitemapitems');
    }

    /**
     * Prepares the list data
     */
    public function prepareVars()
    {
        $sitemapItem = new SitemapItem;

        $this->vars['itemProperties'] = json_encode($sitemapItem->fillable);
        $this->vars['items'] = $this->model->items;

        $emptyItem = new SitemapItem;
        $emptyItem->type = 'url';
        $emptyItem->url = '/';
        $emptyItem->changefreq = 'always';
        $emptyItem->priority = '0.5';

        $this->vars['emptyItem'] = $emptyItem;

        $widgetConfig = $this->makeConfig('~/plugins/rainlab/sitemap/classes/definitionitem/fields.yaml');
        $widgetConfig->model = $sitemapItem;
        $widgetConfig->alias = $this->alias.'SitemapItem';

        $this->vars['itemFormWidget'] = $this->makeWidget('Backend\Widgets\Form', $widgetConfig);
    }

    /**
     * {@inheritDoc}
     */
    protected function loadAssets()
    {
        $this->addJs('js/sitemap-items-editor.js', 'core');
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveValue($value)
    {
        return post('itemData');
    }

    //
    // Methods for the internal use
    //

    /**
     * Returns the item reference description.
     * @param \RainLab\Pages\Classes\SitemapItem $item Specifies the sitemap item
     * @return string 
     */
    protected function getReferenceDescription($item)
    {
        if ($this->typeListCache === false) {
            $this->typeListCache = $item->getTypeOptions();
        }

        if (!isset($this->typeInfoCache[$item->type])) {
            $this->typeInfoCache[$item->type] = SitemapItem::getTypeInfo($item->type);
        }

        if (isset($this->typeInfoCache[$item->type])) {
            $result = $this->typeListCache[$item->type];

            if ($item->type !== 'url') {
                if (isset($this->typeInfoCache[$item->type]['references']))
                    $result .= ': '.$this->findReferenceName($item->reference, $this->typeInfoCache[$item->type]['references']);
            }
            else {
                $result .= ': '.$item->url;
            }
        }
        else {
            $result = trans('rainlab.sitemap::lang.item.unknown_type');
        }

        return $result;
    }

    protected function findReferenceName($search, $typeOptionList)
    {
        $iterator = function($optionList, $path) use ($search, &$iterator) {
            foreach ($optionList as $reference => $info) {
                if ($reference == $search) {
                    $result = $this->getSitemapItemTitle($info);

                    return strlen($path) ? $path.' / ' .$result : $result;
                }

                if (is_array($info) && isset($info['items'])) {
                    $result = $iterator($info['items'], $path . ' / '.$this->getSitemapItemTitle($info));

                    if (strlen($result)) {
                        return strlen($path) ? $path.' / ' .$result : $result;
                    }
                }
            }
        };

        $result = $iterator($typeOptionList, null);
        if (!strlen($result))
            $result = trans('rainlab.sitemap::lang.item.unnamed');

        $result = preg_replace('|^\s+\/|', '', $result);

        return $result;
    }

    protected function getSitemapItemTitle($itemInfo)
    {
        if (is_array($itemInfo)) {
            if (!array_key_exists('title', $itemInfo) || !strlen($itemInfo['title']))
                return trans('rainlab.sitemap::lang.item.unnamed');

            return $itemInfo['title'];
        }

        return strlen($itemInfo) ? $itemInfo : trans('rainlab.sitemap::lang.item.unnamed');
    }
}