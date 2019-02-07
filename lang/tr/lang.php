<?php

return [
    'plugin' => [
        'name' => 'Site Haritası',
        'description' => 'Web sitenize sitemap.xml dosyası oluşturur.',
        'permissions' => [
            'access_settings' => 'Site haritası ayarlarına erişim',
            'access_definitions' => 'Site haritası tanımlamalarına erişim',
        ],
    ],
    'item' => [
        'location' => 'Konum:',
        'priority' => 'Öncelik',
        'changefreq' => 'Değişiklik Sıklığı',
        'always' => 'sürekli',
        'hourly' => 'saatlik',
        'daily' => 'günlük',
        'weekly' => 'haftalık',
        'monthly' => 'aylık',
        'yearly' => 'yıllık',
        'never' => 'asla',
        'editor_title' => 'Site haritası kaydını düzenle',
        'type' => 'Tür',
        'allow_nested_items' => 'İç içe kayıtlara izin ver',
        'allow_nested_items_comment' => 'İç içe kayıtlar statik sayfalar ve bazı kayıtlar tarafından otomatik olarak oluşturulabilir.',
        'url' => 'URL',
        'reference' => 'Sayfa',
        'title_required' => 'Başlık alanı gereklidir.',
        'unknown_type' => 'Bilinmeyen kayıt türü',
        'unnamed' => 'İsimsiz kayıt',
        'add_item' => 'Yeni kayıt',
        'new_item' => 'Yeni kayıt',
        'cms_page' => 'CMS Sayfası',
        'cms_page_comment' => 'Select the page to use for the URL address.',
        'reference_required' => 'Kayıt sayfası alanı gereklidir.',
        'url_required' => 'URL alanı gereklidir.',
        'cms_page_required' => 'Lütfen bir CMS sayfası seçiniz.',
        'page' => 'Sayfa',
        'check' => 'Kontrol et',
        'definition' => 'Tanımlama',
        'save_definition' => 'Tanımlama Kaydediliyor...',
        'load_indicator' => 'Tanımlama Boşaltılıyor...',
        'empty_confirm' => 'Tanımlama boşaltılsın mı?'
    ],
    'definition' => [
        'not_found' => 'Site haritası tanımlaması bulunamadı. Hemen bir tane oluşturun.',
    ],
];
