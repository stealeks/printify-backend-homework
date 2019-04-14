<?php

return [
    'min_order_sum' => 10.00,
    'default_country_code' => env('DEFAULT_COUNTRY_CODE', 'us'),
    'limit_per_country_date_interval' => '-5 minutes',
    'limit_per_country_max_orders' => 10,
];
