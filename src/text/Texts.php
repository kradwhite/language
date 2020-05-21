<?php
/**
 * Date: 12.05.2020
 * Time: 7:52
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language\text;

use kradwhite\db\exception\DbException;
use kradwhite\language\LangException;

/**
 * Class Texts
 * @package kradwhite\language
 */
abstract class Texts
{
    /** @var string */
    private string $type;

    /** @var array */
    private array $names;

    /** @var TextFactory */
    private ?TextFactory $factory;

    /** @var Text[] */
    private array $texts = [];

    /**
     * Texts constructor.
     * @param string $type
     * @param array $names
     * @param TextFactory $factory
     */
    public function __construct(string $type, array $names, TextFactory $factory)
    {
        $this->type = $type;
        $this->names = $names;
        $this->factory = $factory;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function existName(string $name): bool
    {
        return in_array($name, $this->names);
    }

    /**
     * @param string $locale
     * @param string $name
     * @return Text
     * @throws LangException
     * @throws DbException
     */
    public function getText(string $locale, string $name): Text
    {
        if (!isset($this->texts[$name])) {
            $this->texts[$name] = $this->buildText($locale, $name);
        }
        return $this->texts[$name];
    }

    /**
     * @return array
     */
    public function getNames(): array
    {
        return $this->names;
    }

    /**
     * @param array $locales
     * @throws LangException
     * @throws DbException
     */
    abstract public function create(array $locales);

    /**
     * @return TextFactory
     */
    protected function getFactory(): TextFactory
    {
        return $this->factory;
    }

    /**
     * @return string
     */
    protected function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $locale
     * @param string $name
     * @return Text
     * @throws LangException
     * @throws DbException
     */
    abstract protected function buildText(string $locale, string $name): Text;
}