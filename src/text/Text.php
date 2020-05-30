<?php
/**
 * Date: 12.05.2020
 * Time: 20:12
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language\text;

use kradwhite\language\LangException;

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
            throw new LangException('phrase-not-found', [$this->locale, $this->name, $id]);
        }
        if (!is_array($this->texts[$id])) {
            $this->texts[$id] = [$this->texts[$id]];
        }
        if (isset($this->texts[$id][1])) {
            $params = array_merge($this->texts[$id][1], $params);
        }
        try {
            return sprintf($this->texts[$id][0], ...$params);
        } catch (\Throwable $e) {
            $params = implode(', ', $params);
            throw new LangException('phrase-params-wrong', [$params, $this->texts[$id][0]], $e);
        }
    }
}