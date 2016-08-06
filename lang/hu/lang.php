<?php

return [
    'plugin' => [
        'name' => 'Oldaltérkép',
        'description' => 'A weboldalhoz sitemap.xml fájl generálása.',
        'permissions' => [
            'access_settings' => 'Oldaltérkép kezelése',
            'access_definitions' => 'Definíciós oldal kezelése',
        ],
    ],
    'item' => [
        'location' => 'Hely:',
        'priority' => 'Prioritás',
        'changefreq' => 'Gyakoriság',
        'always' => 'mindig',
        'hourly' => 'óránkénti',
        'daily' => 'napi',
        'weekly' => 'heti',
        'monthly' => 'havi',
        'yearly' => 'évi',
        'never' => 'soha',
        'editor_title' => 'Módosítás',
        'type' => 'Típus',
        'allow_nested_items' => 'Engedélyezett elemek',
        'allow_nested_items_comment' => 'Nested items could be generated dynamically by static page and some other item types',
        'url' => 'Webcím',
        'reference' => 'Hivatkozás',
        'title_required' => 'A cím megadása kötelező',
        'unknown_type' => 'Ismeretlen elem típus',
        'unnamed' => 'Névtelen elem',
        'add_item' => 'Hozzáadás',
        'new_item' => 'Új elem',
        'cms_page' => 'Oldal',
        'cms_page_comment' => 'Válassza ki, hogy melyik oldal tartozik a fenti hivatkozáshoz.',
        'reference_required' => 'A hivatkozó elem megadása kötelező.',
        'url_required' => 'A webcím megadása kötelező.',
        'cms_page_required' => 'Válasszon egy oldalt.',
        'page' => 'Oldal',
        'check' => 'Ellenőrzés',
        'definition' => 'Definíció',
        'load_indicator' => 'Visszaállítás folyamatban...',
        'empty_confirm' => 'Állítsuk vissza üres állapotba?'
    ]
];
