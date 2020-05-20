<?php
/**
 * Date: 12.05.2020
 * Time: 20:21
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language\text;

/**
 * Interface TextRepository
 * @package kradwhite\language
 */
interface TextRepository
{
    /**
     * TextRepository constructor.
     * @param DbConfig $config
     */
    public function __construct(DbConfig $config);

    /**
     * @param string $locale
     * @param string $name
     * @param string $id
     * @return array
     */
    public function loadPhrase(string $locale, string $name, string $id): array;

    /**
     * @param string $name
     * @param array $columns
     * @param array $locales
     */
    public function createTable(string $name, array $columns, array $locales);
}