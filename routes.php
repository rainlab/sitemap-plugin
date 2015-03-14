<?php
use RainLab\Sitemap\Models\Definition;

Route::get('sitemap.xml', function (){

    return response(Definition::first()->generateSitemap())->header("Content-Type", "application/xml");
});
