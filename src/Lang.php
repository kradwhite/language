<?php
/**
 * Date: 11.05.2020
 * Time: 20:32
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language;

use kradwhite\db\exception\BeforeQueryException;
use kradwhite\language\text\Text;
use kradwhite\language\text\Texts;

/**
 * Class Lang
 * @package kradwhite\language
 */
class Lang
{
    /** @var string */
    private string $locale;

    /** @var Texts */
    private ?Texts $texts;

    /** @var Config */
    private ?Config $config;

    /**
     * Lang constructor.
     * @param Config $config
     * @param string $locale
     * @throws LangException
     */
    public function __construct(Config $config, string $locale = 'ru')
    {
        $this->config = $config;
        if ($locale && !$config->existLocale($locale)) {
            $localesStr = implode('|', $config->locales());
            throw new LangException("Неизвестный язык '$locale'. Допустимые значения [$localesStr]");
        }
        $this->locale = $locale ? $locale : $config->locale();
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

    /**
     * @return Config
     */
    public function config(): Config
    {
        return $this->config;
    }
}