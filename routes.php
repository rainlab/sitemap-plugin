<?php
use RainLab\Sitemap\Models\Definition;

Route::get('sitemap.xml', function (){

    return Definition::first()->generateSitemap();

});