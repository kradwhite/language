<?php
/**
 * Date: 20.05.2020
 * Time: 19:47
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language\text;

use kradwhite\language\LangException;

/**
 * Class DbConfig
 * @package kradwhite\language\text
 */
class DbConfig
{
    /** @var array */
    private array $config;

    /**
     * DbConfig constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function table(): string
    {
        return isset($this->config['table']) ? $this->config['table'] : 'kw_language';
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        if (!isset($this->config['columns'])) {
            $this->config['columns'] = [];
        }
        return array_merge(
            ['id' => 'id', 'locale' => 'locale', 'params' => 'params', 'name' => 'name', 'text' => 'text'],
            $this->config['columns']
        );
    }

    /**
     * @return int
     */
    public function textLimit(): int
    {
        return isset($this->config['textLimit']) && $this->config['textLimit'] > 1 ? (int)$this->config['textLimit'] : 256;
    }

    /**
     * @return int
     */
    public function paramsLimit(): int
    {
        return isset($this->config['paramsLimit']) && $this->config['paramsLimit'] > 1 ? (int)$this->config['paramsLimit'] : 256;
    }

    /**
     * @return array
     * @throws LangException
     */
    public function connection(): array
    {
        if (!isset($this->config['connection'])) {
            throw new LangException("Для ресурсов типа '{$this->config['type']}' требуется конфигурация 'connection' => []");
        }
        return $this->config['connection'];
    }
}