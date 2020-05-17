<?php

/**
 * Допускается одновременное использование нескольких конфигураций
 * Значения в массивах 'names' должны быть уникальными на уровне приложения
 */

return [
    //'default' => 'errors',
    //'factory' => \kradwhite\language\text\TextFactory::class,
    'texts' => [
        /* конфигурация для хранения фраз в файлах */
        [
            'type' => 'php',
            'names' => ['errors'],
            'directory' => __DIR__ . DIRECTORY_SEPARATOR . 'language'
        ],
        /* конфигурация для хранения фраз в базе данных */
        /*
        [
            'type' => 'database',
            'names' => ['messages'],
            'table' => 'kw_language',
            'columns => [
                'locale' => 'locale',
                'name' => 'name',
                'id' => 'id',
                'text' => 'text',
                'params' => 'params'
            ],
            'repository' => \kradwhite\language\text\SqlTextRepository::class,
            'connection' => [
                'driver' => 'pgsql',    // или 'mysql'
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