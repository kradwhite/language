<?php

/**
 * %s
 * %s
 */

return [
    // %s
    //'locales' => ['ru'],

    // %s
    //'default' => 'errors',

    // %s \kradwhite\language\text\TextFactory
    //'factory' => \kradwhite\language\text\TextFactory::class,

    // %s
    'texts' => [
        /* %s */
        [
            // %s
            'type' => 'php',

            // %s
            'names' => ['errors'],

            // %s
            'directory' => __DIR__ . DIRECTORY_SEPARATOR . 'language'
        ],

        // %s
        /*
        [
            // %s
            'type' => 'database',

            // %s name
            'names' => ['messages'],

            // %s
            'table' => 'kw_language',

            // %s
            'textLimit' => 256,

            // %s
            'paramsLimit' => 256,

            //
            'columns => [
                'locale' => 'locale',
                'name' => 'name',
                'id' => 'id',
                'text' => 'text',
                'params' => 'params'
            ],

            // %s \kradwhite\language\text\TextRepository
            'repository' => \kradwhite\language\text\SqlTextRepository::class,

            // %s
            'connection' => [
                'driver' => 'pgsql',                                // или 'mysql'
                'host' => 'localhost',
                'user' => 'admin',
                'password' => 'admin',
                'dbName' => 'test',
                'port' => '5432'
            ]
        ]
        */
    ]
];