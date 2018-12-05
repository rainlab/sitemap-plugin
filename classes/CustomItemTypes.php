<?php namespace RainLab\Sitemap\Classes;

use File;
use Url;
use Cms\Classes\Page as CmsPage;
use Symfony\Component\Yaml\Yaml;
use Cms\Classes\Theme;

class CustomItemTypes extends \October\Rain\Extension\ExtensionBase
{
    protected $parent;

    protected $itemTypes;

    public function __construct($parent)
    {
        $this->parent = $parent;
        $configPath = plugins_path($this->parent->itemTypeConfig);
        $this->itemTypes = Yaml::parse(File::get($configPath));
    }

   public function getMenuTypeInfo($type)
   {
       $result = [];

       if(! array_key_exists($type, $this->itemTypes))
       {
            return;
       }
       
       $itemType = $this->getItemTypeOptions($type);
       
       
       if (! $itemType['dynamic']) {

            $references = [];
            $items = $this->parent::orderBy($itemType['orderBy'])->get();

            foreach ($items as $item) {
                $references[$item->id] = $item->title;
            }
            $result = [
                'references'   => $references,
                'nesting'      => false,
                'dynamicItems' => false
            ];

       }
       if ($itemType['dynamic']) {
           $result = [
               'dynamicItems' => true
           ];
       }
       if ($result) {
           $theme = Theme::getActiveTheme();
           $pages = CmsPage::listInTheme($theme, true);
           $cmsPages = [];
           foreach ($pages as $page) {
               if (!$page->hasComponent($itemType['component'])) {
                   continue;
               }

               $cmsPages[] = $page;
           }
           $result['cmsPages'] = $cmsPages;
       }


       return $result;
   }

   public function resolveMenuItem($item, $url, $theme)
   {
        $result = null;
        if (!$item->cmsPage)
        {
            return;
        }
            
        $page = CmsPage::loadCached($theme, $item->cmsPage);
        if (!$page)
        {
            return;
        }
            
        if (array_key_exists($item->type, $this->itemTypes)) {
            
            $itemType = $this->getItemTypeOptions($item->type);

            $paramName = $itemType['paramName'];
                        
            if (!$paramName)
            {
                return;
            }

            if($itemType['dynamic'])
            {
                
                $result = $this->getDynamicItems($itemType, $paramName, $page, $url);
            }else{

                $result = $this->getItemByReference($item, $itemType, $paramName, $page, $url);  
            }
        }

       return $result;
   }

   protected function getDynamicItems($itemType, $paramName, $page, $url)
   {
        $result = [
            'items' => []
        ];

        $scope = $itemType['scope'];

        $items = new $this->parent;

        if(isset($itemType['scope']))
        {
            $items = $items->isVisible();
        }

        $items = $items->orderBy('title')->get();

        foreach ($items as $item) {
            $result['items'][] = self::getModelMenuItem($page, $item, $paramName, $url, $itemType['slug']);
        }

        return $result;
   }

   protected function getItemByReference($item, $itemType, $paramName, $page, $url)
   {
        if (!$item->reference)
        {
            return;
        }
            
        $model = $this->parent::find($item->reference);
        if (!$model)
        {
            return;
        }
            
        $result = self::getModelMenuItem($page, $model, $paramName, $url, $itemType['slug']);

        return $result;

   }

    /**
     * Returns an array of the specified item type.
     * Optional field are merged or overwritten here.
     *
     * @param $itemType
    */
   protected function getItemTypeOptions($itemType)
   {
       $options = array_merge([
           'paramName' => 'slug',
           'slug' => 'slug',
           'scope' => 'isVisible',
           'orderBy' => 'title',
           'dynamic' =>  null,
       ], $this->itemTypes[$itemType]);

       return $options;
   }

   /**
    * Returns URL of a apartment page.
    *
    * @param $page
    * @param $model
    * @param $paramName
    */
   protected function getModelPageUrl($page, $model, $paramName, $slug)
   {
       $url = CmsPage::url($page->getBaseFileName(), [$paramName => $model->slug]);
       if (!$url)
           return;

       $url = Url::to($url);

       return $url;
   }
   
   protected function getModelMenuItem($page, $item, $paramName, $url, $slug)
   {
        $result = [];

        $pageUrl = self::getModelPageUrl($page, $item, $paramName, $slug);

        $result['title'] = $item->title;
        $result['url'] = $pageUrl;
        $result['isActive'] = $pageUrl == $url;
        $result['mtime'] = $item->updated_at;
        return $result;
   }

}

// Model Menu Item ML
