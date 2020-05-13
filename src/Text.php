<?php
/**
 * Date: 12.05.2020
 * Time: 20:12
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language;

/**
 * Class Text
 * @package kradwhite\language
 */
abstract class Text
{
    /** @var string */
    protected string $locale;

    /** @var string */
    protected string $name;

    /** @var array */
    protected array $texts;

    /**
     * Text constructor.
     * @param string $locale
     * @param string $name
     * @param array $texts
     */
    public function __construct(string $locale, string $name, array $texts)
    {
        $this->locale = $locale;
        $this->name = $name;
        $this->texts = $texts;
    }

    /**
     * @param string $id
     * @param array $params
     * @return string
     * @throws LangException
     */
    public function phrase(string $id, array $params): string
    {
        if (!isset($this->texts[$id])) {
            throw new LangException("Locale={$this->locale}, Name={$this->name}. Не найдена фраза с идентификатором '$id'");
        }
        if (!isset($this->texts[$id][1])) {
            $this->texts[$id][1] = [];
        }
        return sprintf($this->texts[$id][0], ...array_merge($this->texts[$id][1], $params));
    }
}