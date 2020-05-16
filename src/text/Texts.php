<?php
/**
 * Date: 12.05.2020
 * Time: 7:52
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language\text;

use kradwhite\db\exception\BeforeQueryException;
use kradwhite\language\Config;
use kradwhite\language\LangException;

/**
 * Class Texts
 * @package kradwhite\language
 */
class Texts
{
    /** @var Config */
    private Config $config;

    /** @var Text[] */
    private array $texts = [];

    /**
     * Texts constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $locale
     * @param string $name
     * @return Text
     * @throws LangException
     * @throws BeforeQueryException
     */
    public function getText(string $locale, string $name): Text
    {
        if (!$name) {
            $name = $this->config->defaultName();
        }
        if (!isset($this->texts[$name])) {
            $this->texts[$name] = $this->config->factory()->buildText($this->config->configByName($name), $locale, $name);
        }
        return $this->texts[$name];
    }
}