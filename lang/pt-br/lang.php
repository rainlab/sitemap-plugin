<?php

return [
    'plugin' => [
        'name' => 'Sitemap',
        'description' => 'Gera o arquivo sitemap.xml para seu site.',
        'permissions' => [
            'access_settings' => 'Acessar as definições de configuração so sitemap',
            'access_definitions' => 'Acessar a página de definições do sitemap',
        ],
    ],
    'item' => [
        'location' => 'Localização:',
        'priority' => 'Prioridade',
        'changefreq' => 'Mudar a frequência',
        'always' => 'sempre',
        'hourly' => 'de hora em hora',
        'daily' => 'diariamente',
        'weekly' => 'semanalmente',
        'monthly' => 'mensal',
        'yearly' => 'anual',
        'never' => 'nunca',
        'editor_title' => 'Editar item no sitemap',
        'type' => 'Tipo',
        'allow_nested_items' => 'Permitir itens aninhados',
        'allow_nested_items_comment' => 'Itens aninhados poderiam ser gerados dinamicamente por página estática e alguns outros tipos de itens',
        'url' => 'URL',
        'reference' => 'Referência',
        'title_required' => 'O título é obrigatório',
        'unknown_type' => 'Tipo de item Desconhecido',
        'unnamed' => 'Sem nome',
        'add_item' => 'Adicione um <u>I</u>tem',
        'new_item' => 'Novo item',
        'cms_page' => 'Página do CMS',
        'cms_page_comment' => 'Selecione a página a ser usado para o endereço URL.',
        'reference_required' => 'A referência é obrigatória.',
        'url_required' => 'A URL é obrigatória.',
        'cms_page_required' => 'Por favor, selecione uma página do CMS',
        'page' => 'Página',
        'check' => 'Checar',
        'definition' => 'Definição',
        'save_definition' => 'Salvando Definição...',
        'load_indicator' => 'Limpando Definição...',
        'empty_confirm' => 'Limpar esta definição?'
    ]
];
