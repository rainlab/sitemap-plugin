<?php
use RainLab\Sitemap\Models\Definition;

Route::get('sitemap.xml', function (){

    header("Content-Type: application/xml");
    return Definition::first()->generateSitemap();

});