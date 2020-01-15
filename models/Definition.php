<?php namespace RainLab\Sitemap\Models;

use Url;
use Model;
use Event;
use Request;
use DOMDocument;
use Cms\Classes\Theme;
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
     * @var array The sitemap items.
     * Items are objects of the \RainLab\Sitemap\Classes\DefinitionItem class.
     */
    public $items;

    /**
     * @var DOMDocument element
     */
    protected $urlSet;

    /**
     * @var DOMDocument
     */
    protected $xmlObject;

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
        if (!$this->items) {
            return;
        }

        $currentUrl = Request::path();
        $theme = Theme::load($this->theme);

        /*
         * Cycle each page and add its URL
         */
        foreach ($this->items as $item) {

            /*
             * Explicit URL
             */
            if ($item->type == 'url') {
                $this->addItemToSet($item, Url::to($item->url));
            }
            /*
             * Registered sitemap type
             */
            else {

                $apiResult = Event::fire('pages.menuitem.resolveItem', [$item->type, $item, $currentUrl, $theme]);

                if (!is_array($apiResult)) {
                    continue;
                }

                foreach ($apiResult as $itemInfo) {
                    if (!is_array($itemInfo)) {
                        continue;
                    }

                    /*
                     * Single item
                     */
                    if (isset($itemInfo['url'])) {
                        $this->addItemToSet($item, $itemInfo['url'], array_get($itemInfo, 'mtime'));
                    }

                    /*
                     * Multiple items
                     */
                    if (isset($itemInfo['items'])) {

                        $parentItem = $item;

                        $itemIterator = function($items) use (&$itemIterator, $parentItem)
                        {
                            foreach ($items as $item) {
                                if (isset($item['url'])) {
                                    $this->addItemToSet($parentItem, $item['url'], array_get($item, 'mtime'));
                                }

                                if (isset($item['items'])) {
                                    $itemIterator($item['items']);
                                }
                            }
                        };

                        $itemIterator($itemInfo['items']);
                    }
                }

            }

        }

        $urlSet = $this->makeUrlSet();
        $xml = $this->makeXmlObject();
        $xml->appendChild($urlSet);

        return $xml->saveXML();
    }

    protected function makeXmlObject()
    {
        if ($this->xmlObject !== null) {
            return $this->xmlObject;
        }

        $xml = new DOMDocument;
        $xml->encoding = 'UTF-8';

        return $this->xmlObject = $xml;
    }

    protected function makeUrlSet()
    {
        if ($this->urlSet !== null) {
            return $this->urlSet;
        }

        $xml = $this->makeXmlObject();
        $urlSet = $xml->createElement('urlset');
        $urlSet->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlSet->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $urlSet->setAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
        $urlSet->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

        return $this->urlSet = $urlSet;
    }

    protected function addItemToSet($item, $url, $mtime = null)
    {
        if ($mtime instanceof \DateTime) {
            $mtime = $mtime->getTimestamp();
        }

        $xml = $this->makeXmlObject();
        $urlSet = $this->makeUrlSet();
        $mtime = $mtime ? date('c', $mtime) : date('c');
        
        

        $urlElement = $this->makeUrlElement(
            $xml,
            $url,
            $mtime,
            $item->changefreq,
            $item->priority,
            $item
        );

        if ($urlElement) {
            $urlSet->appendChild($urlElement);
        }

        return $urlSet;
    }

    /**
     * Build the URL element for the provided information
     *
     * @param DomDocument $xml The XML object to write to
     * @param string $pageUrl The URL to generate an item for
     * @param string $lastModified The ISO 8601 date that the item was last modified
     * @param string $frequency The change frequency of the item
     * @param float $priority The priority of the item from 0.1 to 1.0
     * @param DefinitionItem $item The actual definition item object 
     */
    protected function makeUrlElement($xml, $pageUrl, $lastModified, $frequency, $priority, $item)
    {
        if ($this->urlCount >= self::MAX_URLS) {
            return false;
        }
        
        /**
         * @event rainlab.sitemap.beforeMakeUrlElement
         * Provides an opportunity to prevent an element from being produced
         *
         * Example usage (stops the generation process):
         *
         *     Event::listen('rainlab.sitemap.beforeMakeUrlElement', function ((Definition) $definition, (DomDocument) $xml, (string) &$pageUrl, (string) &$lastModified, (string) &$frequency, (float) &$priority, (DefinitionItem) $item) {
         *         if ($pageUrl === '/ignore-this-specific-page') {
         *             return false;
         *         }
         *     });
         *
         */
        if (Event::fire('rainlab.sitemap.beforeMakeUrlElement', [$this, $xml, &$pageUrl, &$lastModified, &$frequency, &$priority, $item], true) === false) {
            return false;
        }

        $this->urlCount++;

        $url = $xml->createElement('url');
        $url->appendChild($xml->createElement('loc', $pageUrl));
        $url->appendChild($xml->createElement('lastmod', $lastModified));
        $url->appendChild($xml->createElement('changefreq', $frequency));
        $url->appendChild($xml->createElement('priority', $priority));
        
        /**
         * @event rainlab.sitemap.makeUrlElement
         * Provides an opportunity to interact with a sitemap element after it has been generated.
         *
         * Example usage:
         *
         *     Event::listen('rainlab.sitemap.makeUrlElement', function ((Definition) $definition, (DomDocument) $xml, (string) $pageUrl, (string) $lastModified, (string) $frequency, (float) $priority, (DefinitionItem) $item, (ElementNode) $urlElement) {
         *         $url->appendChild($xml->createElement('bestcmsever', 'OctoberCMS');
         *     });
         *
         */
        Event::fire('rainlab.sitemap.makeUrlElement', [$this, $xml, $pageUrl, $lastModified, $frequency, $priority, $item, $url]);

        return $url;
    }
}
