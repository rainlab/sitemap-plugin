<?php

return [
    'plugin' => [
        'name' => 'Sitemap (Plan du site)',
        'description' => 'Générer un fichier sitemap.xml pour votre site web.',
        'permissions' => [
            'access_settings' => 'Accéder aux paramètres de configuration du Sitemap',
            'access_definitions' => 'Accédez à la page des définitions du sitemap',
        ],
    ],
    'item' => [
        'location' => 'Emplacement:',
        'priority' => 'Priorité',
        'changefreq' => 'Frequence de changement',
        'always' => 'toujours',
        'hourly' => 'horaire',
        'daily' => 'tous les jours',
        'weekly' => 'hebdomadaire',
        'monthly' => 'mensuelle',
        'yearly' => 'annuellement',
        'never' => 'jamais',
        'editor_title' => 'Modifier un élément du Sitemap',
        'type' => 'Type',
        'allow_nested_items' => 'Autoriser les éléments imbriqués',
        'allow_nested_items_comment' => 'Nested items could be generated dynamically by static page and some other item types',
        'url' => 'URL',
        'reference' => 'Référence',
        'title_required' => 'Le titre est requis',
        'unknown_type' => 'Elément de type inconnu',
        'unnamed' => 'Elément sans nom',
        'add_item' => 'Ajouter <u>E</u>lément',
        'new_item' => 'Nouvel élément',
        'cms_page' => 'CMS Page',
        'cms_page_comment' => 'Sélectionnez la page à utiliser pour l\'adresse URL.',
        'reference_required' => 'La référence de l\'élément est requise.',
        'url_required' => 'L\'URL est requise',
        'cms_page_required' => 'S\'il vous plaît sélectionner une page CMS',
        'page' => 'Page',
        'check' => 'Vérifier'
    ]
];
