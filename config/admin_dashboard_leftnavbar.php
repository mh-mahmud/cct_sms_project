<?php

return [
    [
        'name' => 'dashboard',
        'title' => 'Dashboard',
        'icon' => 'flaticon-dashboard',
        'sidebar_menu_active' => 'dashboard',
        'vue_route_name' => 'Dashboard',
        'url' => '#/admin/dashboard',
        'model_list' => []
    ],
    [
        'name' => 'administrator',
        'title' => 'Inbound List',
        'icon' => 'flaticon-list',
        'sidebar_menu_active' => 'inbound-list',
        'vue_route_name' => 'InboundList',
        'url' => '#/admin/inbound-list',
        'model_list' => []
    ],
    [
        'name' => 'administrator',
        'title' => 'Outbound List',
        'icon' => 'flaticon-list',
        'sidebar_menu_active' => 'outbound-list',
        'vue_route_name' => 'OutboundList',
        'url' => '#/admin/outbound-list',
        'model_list' => []
    ],
    [
        'name' => 'administrator',
        'title' => 'Audit Log',
        'icon' => 'flaticon-file',
        'sidebar_menu_active' => 'auditLog',
        'vue_route_name' => 'AuditLog',
        'url' => '#/admin/audit-log-list',
        'model_list' => []
    ]

];