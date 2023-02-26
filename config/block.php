<?php

return [
    'navi' => [
        '' => [
            'dashboard' => [
                'icon' => 'glyphicon glyphicon-dashboard',
                'ctrl' => '\App\Http\Controllers\Admin\DashboardController'
            ],
        ],
        'reminder' => [
            'customer-reminder' => [
                'icon' => 'fa fa-calendar',
                'ctrl' => '\App\Http\Controllers\Admin\Customer\CustomerReminderController'
            ],
            'customer-reminder-report' => [
                'icon' => 'fa fa-calendar-check-o',
                'ctrl' => '\App\Http\Controllers\Admin\Customer\CustomerReminderReportController'
            ],
        ],
        'customer' => [
            'customer' => [
                'icon' => 'fa fa-users',
                'ctrl' => '\App\Http\Controllers\Admin\Customer\CustomerController'
            ],
            'customer-contact' => [
                'icon' => 'fa fa-trello',
                'ctrl' => '\App\Http\Controllers\Admin\Customer\CustomerContactController'
            ],
            'customer-report' => [
                'icon' => 'fa fa-clock-o',
                'ctrl' => '\App\Http\Controllers\Admin\Customer\CustomerReportController'
            ]
        ],
        'resource' => [
            'product' => [
                'icon' => 'fa fa-product-hunt',
                'ctrl' => '\App\Http\Controllers\Admin\Resource\ProductController'
            ],
            'reminder-template' => [
                'icon' => 'glyphicon glyphicon-dashboard',
                'ctrl' => '\App\Http\Controllers\Admin\Resource\ReminderTemplateController'
            ],
            'attachment' => [
                'icon' => 'fa fa-files-o',
                'ctrl' => '\App\Http\Controllers\Admin\Resource\AttachmentController'
            ],
        ],
        'setting' => [
            'permission' => [
                'icon' => 'fa fa-pencil-square-o',
                'ctrl' => '\App\Http\Controllers\Admin\Setting\PermissionController'
            ],
            'system-config' => [
                'icon' => 'fa fa-cogs',
                'ctrl' => '\App\Http\Controllers\Admin\Setting\SystemConfigController'
            ],
        ],
        'user-manager'   => [
            'user'       => [
                'icon' => 'fa fa-user',
                'ctrl' => '\App\Http\Controllers\Admin\User\UserController'
            ],
            'user-group' => [
                'icon' => 'fa fa-users',
                'ctrl' => '\App\Http\Controllers\Admin\User\UserGroupController'
            ],
        ]
    ]
];
