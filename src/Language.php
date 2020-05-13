<?php
/**
 * Date: 11.05.2020
 * Time: 20:32
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language;

/**
 * Class Language
 * @package kradwhite\language
 */
class Language
{
    /** @var string */
    private string $locale;

    /** @var Texts */
    private ?Texts $texts;

    /**
     * Language constructor.
     * @param array $config
     * @param string $locale
     * @throws LangException
     */
    public function __construct(array $config, string $locale = 'ru')
    {
        $this->locale = $locale;
        $this->texts = new Texts($config);
    }

    /**
     * @param string $name
     * @return Text
     * @throws LangException
     */
    public function text(string $name = ''): Text
    {
        return $this->texts->getText($this->locale, $name);
    }

    /**
     * @param string $name
     * @param string $id
     * @param array $params
     * @return string
     * @throws LangException
     */
    public function phrase(string $name, string $id, array $params = []): string
    {
        return $this->texts->getText($this->locale, $name)->phrase($id, $params);
    }
}