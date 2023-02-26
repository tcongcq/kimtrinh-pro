<?php

/* 
 * CMS config
 */

return [
    'backend_prefix' => 'admin',
    'backend_login'  => 'cms::login',
    'temp_dir'       => 'attachment/temps',
    'customer_dir'   => 'attachment/customer',
    'customer-thumbnail' => 'attachment/customer/thumbnail',
    'thumbnail-config'   => [
        'width'     => 300,
        'height'    => null
    ],
    'default_product'=> 12,
    'oldster'        => [],
    'zalo_web_link'  => 'https://chat.zalo.me/',
    'customer_state' => ['CONTACT','LEAD','CLIENT']
];

