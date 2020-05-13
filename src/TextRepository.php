<?php
/**
 * Date: 12.05.2020
 * Time: 20:21
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language;

/**
 * Interface TextRepository
 * @package kradwhite\language
 */
interface TextRepository
{
    /**
     * @param string $locale
     * @param string $name
     * @param string $id
     * @return array
     */
    public function loadPhrase(string $locale, string $name, string $id): array;
}