<?php namespace RainLab\Sitemap\Classes;

use Model;
use Event;
use Illuminate\Database\Eloquent\Collection;

/**
 * Item Model
 */
class DefinitionItem
{
    /**
     * @var string How frequently the page is likely to change.
     */
    public $changefreq;

    /**
     * @var string The priority of this URL relative to other URLs on the site.
     */
    public $priority;

    /**
     * @var boolean Determines whether the auto-generated items could have subitems.
     */
    public $nesting;

    /**
     * @var string Specifies the item type - URL, static page, etc.
     */
    public $type;

    /**
     * @var string Specifies the URL for URL-type items.
     */
    public $url;

    /**
     * @var string Specifies the item code.
     */
    public $code;

    /**
     * @var string Specifies the object identifier the item refers to.
     * The identifier could be the database identifier or an object code.
     */
    public $reference;

    /**
     * @var string Specifies the CMS page path to resolve dynamic items to.
     */
    public $cmsPage;

    /**
     * @var boolean Used by the system internally.
     */
    public $exists = false;

    /**
     * @var array Fillable fields
     */
    public $fillable = [
        'changefreq',
        'priority',
        'nesting',
        'type',
        'url',
        'reference',
        'cmsPage'
    ];

    /**
     * Initializes a item from a data array.
     * @param array $items Specifies the item data.
     * @return Returns an array of the Item objects.
     */
    public static function initFromArray($items)
    {
        if (!is_array($items)) {
            return null;
        }

        $result = [];
        foreach ($items as $itemData) {
            $obj = new self;

            foreach ($itemData as $name => $value) {
                if (property_exists($obj, $name)) {
                    $obj->$name = $value;
                }
            }

            $result[] = $obj;
        }

        return new Collection($result);
    }

    /**
     * Returns a list of registered item types
     * @return array Returns an array of registered item types
     */
    public function getTypeOptions($keyValue = null)
    {
        $result = ['url' => 'URL'];

        $apiResult = Event::fire('pages.menuitem.listTypes');
        if (is_array($apiResult)) {
            foreach ($apiResult as $typeList) {
                if (!is_array($typeList)) {
                    continue;
                }

                foreach ($typeList as $typeCode => $typeName) {
                    $result[$typeCode] = $typeName;
                }
            }
        }

        return $result;
    }

    public function getCmsPageOptions($keyValue = null)
    {
        return []; // CMS Pages are loaded client-side
    }

    public function getReferenceOptions($keyValue = null)
    {
        return []; // References are loaded client-side
    }

    public static function getTypeInfo($type)
    {
        $result = [];
        $apiResult = Event::fire('pages.menuitem.getTypeInfo', [$type]);
        if (is_array($apiResult)) {
            foreach ($apiResult as $typeInfo) {
                if (!is_array($typeInfo))
                    continue;

                foreach ($typeInfo as $name=>$value) {
                    if ($name == 'cmsPages') {
                        $cmsPages = [];

                        foreach ($value as $page) {
                            $baseName = $page->getBaseFileName();
                            $pos = strrpos ($baseName, '/');

                            $dir = $pos !== false ? substr($baseName, 0, $pos).' / ' : null;
                            $cmsPages[$baseName] = strlen($page->title)
                                ? $dir.$page->title
                                : $baseName;
                        }

                        $value = $cmsPages;
                    }

                    $result[$name] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Converts the item data to an array
     * @return array Returns the item data as array
     */
    public function toArray()
    {
        $result = [];
        foreach ($this->fillable as $property) {
            $result[$property] = $this->$property;
        }

        return $result;
    }

}