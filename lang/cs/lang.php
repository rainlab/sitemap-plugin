<?php

return [
    'plugin' => [
        'name' => 'Mapa stránek',
        'description' => 'Generuje soubor sitemap.xml pro vaše stránky.',
        'permissions' => [
            'access_settings' => 'Povolit nastavení mapy stránek',
            'access_definitions' => 'Povolit zobrazení definice mapy stránek', // aaa
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
        'check' => 'Zkontrolovat',
        'definition' => 'Definice',
        'save_definition' => 'Ukládám mapu stránek...',
        'load_indicator' => 'Mažu definici...',
        'empty_confirm' => 'Opravdu chcete smazat definici mapy stránek?',
    ],
    'definition' => [
        'not_found' => 'Žádná sitemap nebyla nalezena. Zkuste nejdříve nějakou vytvořit.',
    ],
];
