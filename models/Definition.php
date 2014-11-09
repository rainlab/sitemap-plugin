<?php namespace RainLab\Sitemap\Models;

use URL;
use Model;
use Event;
use Request;
use DOMDocument;
use RainLab\Sitemap\Classes\DefinitionItem;

/**
 * Definition Model
 */
class Definition extends Model
{

    /**
     * Maximum URLs allowed (Protocol limit is 50k)
     */
    const MAX_URLS = 50000;

    /**
     * Maximum generated URLs per type
     */
    const MAX_GENERATED = 10000;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rainlab_sitemap_definitions';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var integer A tally of URLs added to the sitemap
     */
    protected $urlCount = 0;

    /**
     * @var array List of attribute names which are json encoded and decoded from the database.
     */
    protected $jsonable = ['data'];

    /**
     * @var array The menu items.
     * Items are objects of the \RainLab\Pages\Classes\MenuItem class.
     */
    protected $items;

    public function beforeSave()
    {
        $this->data = (array) $this->items;
    }

    public function afterFetch()
    {
        $this->items = DefinitionItem::initFromArray($this->data);
    }

    public function generateSitemap()
    {
        // header("Content-Type: application/xml");
        $xml = new DOMDocument;
        $xml->encoding = 'UTF-8';

        $urlset = $xml->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $urlset->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

        $currentUrl = Request::path();

        /*
         * Cycle each page and add its URL
         */
        foreach ($this->items as $item) {

            if ($item->type == 'url') {
                // $pageUrl = URL::to('/');
                $pageUrl = URL::to($item->url);
            }
            else {

                $apiResult = Event::fire('pages.menuitem.resolveItem', [$item->type, $item, $currentUrl, $this->theme]);
                if (is_array($apiResult)) {
                    foreach ($apiResult as $itemInfo) {
                        if (!is_array($itemInfo))
                            continue;

                        if (!$item->replace && isset($itemInfo['url'])) {
                            $pageUrl = $itemInfo['url'];
                        }

                        // if (isset($itemInfo['items'])) {
                        //     $itemIterator = function($items) use (&$itemIterator, $parentReference) {
                        //         $result = [];

                        //         foreach ($items as $item) {
                        //             $reference = new MenuItemReference();
                        //             $reference->title = isset($item['title']) ? $item['title'] : '--no title--';
                        //             $reference->url = isset($item['url']) ? $item['url'] : '#';
                        //             $reference->isActive = isset($item['isActive']) ? $item['isActive'] : false;

                        //             if (!strlen($parentReference->url)) {
                        //                 $parentReference->url = $reference->url;
                        //                 $parentReference->isActive = $reference->isActive;
                        //             }

                        //             if (isset($item['items']))
                        //                 $reference->items = $itemIterator($item['items']);

                        //             $result[] = $reference;
                        //         }

                        //         return $result;
                        //     };

                        //     $parentReference->items = $itemIterator($itemInfo['items']);
                        // }
                    }
                }

            }

            $urlElement = $this->prepareUrlElement(
                $xml,
                $pageUrl,
                // date('c', $page->mtime),
                date('c'),
                'weekly',
                '0.7'
            );

            if ($urlElement)
                $urlset->appendChild($urlElement);
        }

        $xml->appendChild($urlset);
        return $xml->saveXML();
    }

    protected function prepareUrlElement($xml, $pageUrl, $lastModified, $frequency, $priority)
    {
        if ($this->urlCount >= self::MAX_URLS)
            return false;

        $this->urlCount++;

        $url = $xml->createElement('url');
        $url->appendChild($xml->createElement('loc', $pageUrl));
        $url->appendChild($xml->createElement('lastmod', $lastModified));
        $url->appendChild($xml->createElement('changefreq', $frequency));
        $url->appendChild($xml->createElement('priority', $priority));
        return $url;
    }

}