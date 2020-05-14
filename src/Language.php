<?php
/**
 * Date: 11.05.2020
 * Time: 20:32
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language;

use kradwhite\db\exception\BeforeQueryException;

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
     * @param Config $config
     * @param string $locale
     */
    public function __construct(Config $config, string $locale = 'ru')
    {
        $this->locale = $locale;
        $this->texts = $config->factory()->buildTexts($config);
    }

    /**
     * @param string $name
     * @return Text
     * @throws LangException
     * @throws BeforeQueryException
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
     * @throws BeforeQueryException
     */
    public function phrase(string $name, string $id, array $params = []): string
    {
        return $this->texts->getText($this->locale, $name)->phrase($id, $params);
    }

    /**
     * @return string
     */
    public function locale(): string
    {
        return $this->locale;
    }
}