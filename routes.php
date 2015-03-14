<?php
use RainLab\Sitemap\Models\Definition;

Route::get('sitemap.xml', function (){

    return Response::make(Definition::first()->generateSitemap())
        ->header("Content-Type", "application/xml");

});
