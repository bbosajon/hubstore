<?php

return [
    'name' => 'Aster',
    'route' => '',
    'url' => 'javascript:',
    'icon' => '<i class="fa-solid fa-screwdriver-wrench"></i>',
    'index' => 0,
    'path' => 'theme_route',
    'comfortable_panel_version' => SOFTWARE_VERSION,
    'route_list' => [
        [
            'name' => 'Promotional_Banners',
            'route' => 'admin.banner.list',
            'module_permission' => 'promotion_management',
            'url' => url('/') . '/admin/banner/list',
            'icon' => '<i class="fi fi-sr-pennant"></i>',
            'path' => 'admin/banner/list',
            'route_list' => []
        ],
        [
            'name' => 'In-House_Store_Banner',
            'route' => 'admin.product-settings.inhouse-shop',
            'module_permission' => 'business_settings',
            'url' => url('/') . '/admin/business-settings/inhouse-shop?action=edit',
            'icon' => '<i class="fi fi-sr-pennant"></i>',
            'path' => 'admin/business-settings/inhouse-shop?action=edit',
            'route_list' => []
        ],
    ]
];
