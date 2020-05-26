<?php
/**
 * Date: 26.05.2020
 * Time: 20:59
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language;

/**
 * Class LocalLang
 * @package kradwhite\language
 */
class LocalLang extends Lang
{
    /** @var Lang */
    private static ?Lang $lang = null;

    /**
     * @param string $configFilename
     * @param string $locale
     * @return Lang
     * @throws LangException
     */
    public static function init(string $configFilename = '', string $locale = 'ru'): Lang
    {
        if (!self::$lang) {
            $configFilename = __DIR__ . '/../language.php';
            $rawLocale = setlocale(LC_ALL, '');
            $locale = substr($rawLocale, 0, 2);
            self::$lang = parent::init($configFilename, $locale);
        }
        return self::$lang;
    }
}