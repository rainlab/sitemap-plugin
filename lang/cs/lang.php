<?php

return [
    'plugin' => [
        'name' => 'Mapa stránek',
        'description' => 'Generuje soubor sitemap.xml pro vaše stránky.',
        'permissions' => [
            'access_settings' => 'Povolit nastavení mapy stránek',
        ],
    ],
    'item' => [
        'location' => 'Umístění:',
        'priority' => 'Priorita',
        'changefreq' => 'Četnost kontroly',
        'always' => 'vždy',
        'hourly' => 'každou hodinu',
        'daily' => 'denně',
        'weekly' => 'týdně',
        'monthly' => 'měsíčně',
        'yearly' => 'ročně',
        'never' => 'nikdy',
        'editor_title' => 'Upravit položku mapy stránek',
        'type' => 'Typ',
        'allow_nested_items' => 'Povolit vnořené položky',
        'allow_nested_items_comment' => 'Vnořené položky mohou být generovány dynamicky statickou stránkou a nebo i jiným typem položky',
        'url' => 'URL',
        'reference' => 'Reference',
        'title_required' => 'Nadpis je povinný',
        'unknown_type' => 'Neznámy typ položky',
        'unnamed' => 'Nepojmenovaná položka',
        'add_item' => 'Přidat <u>P</u>oložku',
        'new_item' => 'Nová položka',
        'cms_page' => 'CMS Stránka',
        'cms_page_comment' => 'Vyberte stránku ze které se použije její URL adresa.',
        'reference_required' => 'Je nutno vybrat referenci položky.',
        'url_required' => 'URL adresa je povinná',
        'cms_page_required' => 'Vyberte prosím CMS stránku',
        'page' => 'Stránka',
        'check' => 'Zkontrolovat'
    ]
];
