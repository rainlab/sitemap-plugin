<?php
use RainLab\Sitemap\Models\Definition;

Route::get('sitemap.xml', function (){

    return Response::make(Definition::first()->generateSitemap(), 200, array('Content-Type' => 'application/xml'));
});