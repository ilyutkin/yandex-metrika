<?php

/**
 * Настройки Yandex Metrika API
 */
return [

    /**
     * OAuth Token
     */
    'token'          => env('YANDEX_METRIKA_API_TOKEN', ''),

    /**
     * Id счетчика Яндекс метрики
     */
    'counter_id'     => env('YANDEX_METRIKA_API_COUNTER_ID', 0),

    /**
     * Время жизни кэша в секундах
     */
    'cache_lifetime' => env('YANDEX_METRIKA_API_CACHE_LIFETIME', 60),

];
