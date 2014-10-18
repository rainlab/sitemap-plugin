<?php namespace RainLab\Sitemap\Models;

use URL;
use Model;
use DOMDocument;

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
     * @var array List of attribute names which are json encoded and decoded from the database.
     */
    protected $jsonable = ['data'];

    /**
     * @var integer A tally of URLs added to the sitemap
     */
    protected $urlCount = 0;

    public function beforeSave()
    {
        /*
         * Dynamic attributes are stored in the jsonable attribute 'data'.
         */
        $staticAttributes = ['id', 'theme', 'data'];
        $dynamicAttributes = array_except($this->getAttributes(), $staticAttributes);

        $this->data = $dynamicAttributes;
        $this->setRawAttributes(array_only($this->getAttributes(), $staticAttributes));
    }

    public function afterFetch()
    {
        /*
         * Fill this model with the jsonable attributes kept in 'data'.
         */
        $this->setRawAttributes((array) $this->getAttributes() + (array) $this->data, true);
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

        /*
         * Cycle each page and add its URL
         */
        $pages = [];

        foreach ($pages as $page) {

            $pageUrl = URL::to('/');
            $urlElement = $this->prepareUrlElement(
                $xml,
                $pageUrl,
                date('c', $page->mtime),
                'weekly',
                '0.7'
            );

            if ($urlElement)
                $urlset->appendChild($url);
        }

        /*
         * @todo Cycle each registered definition type and add it
         */

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