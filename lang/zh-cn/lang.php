<?php

return [
    'plugin' => [
        'name' => '站点地图',
        'description' => '为您的网站生成一个 sitemap.xml 文件。',
        'permissions' => [
            'access_settings' => '访问站点地图配置设置',
            'access_definitions' => '访问站点地图定义页面',
        ],
    ],
    'item' => [
        'location' => '位置：',
        'priority' => '优先事项',
        'changefreq' => '变更频率',
        'always' => '总是',
        'hourly' => '每小时',
        'daily' => '日常的',
        'weekly' => '每周',
        'monthly' => '每月',
        'yearly' => '每年',
        'never' => '绝不',
        'editor_title' => '编辑站点地图项',
        'type' => '类型',
        'allow_nested_items' => '允许嵌套项',
        'allow_nested_items_comment' => '嵌套项目可以由静态页面和一些其他项目类型动态生成',
        'url' => '网址',
        'reference' => '参考',
        'title_required' => '标题为必填项',
        'unknown_type' => '未知项目类型',
        'unnamed' => '未命名的项目',
        'add_item' => '添加项目',
        'new_item' => '新项目',
        'cms_page' => '内容管理页面',
        'cms_page_comment' => '选择用于 URL 地址的页面。',
        'reference_required' => '项目参考是必需的。',
        'url_required' => '网址为必填项',
        'cms_page_required' => '请选择一个 CMS 页面',
        'page' => '页',
        'check' => '查看',
        'definition' => '定义',
        'save_definition' => '保存定义...',
        'load_indicator' => '清空定义...',
        'empty_confirm' => '清空这个定义？'
    ],
    'definition' => [
        'not_found' => '未找到站点地图定义。 尝试先创建一个。'
    ]
];
