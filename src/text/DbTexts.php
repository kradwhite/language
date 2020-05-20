<?php
/**
 * Date: 20.05.2020
 * Time: 7:30
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language\text;

/**
 * Class DbTexts
 * @package kradwhite\language\text
 */
class DbTexts extends Texts
{
    /** @var DbConfig */
    private ?DbConfig $config;

    /** @var TextRepository */
    private ?TextRepository $repository;

    /**
     * DbTexts constructor.
     * @param string $type
     * @param array $names
     * @param TextFactory $factory
     * @param DbConfig $config
     * @param TextRepository $repository
     */
    public function __construct(string $type, array $names, TextFactory $factory, DbConfig $config, TextRepository $repository)
    {
        parent::__construct($type, $names, $factory);
        $this->config = $config;
        $this->repository = $repository;
    }

    /**
     * @param array $locales
     * @return void
     */
    public function create(array $locales)
    {
        $this->repository->createTable($this->config->table(), $this->config->columns(), $locales);
    }

    /**
     * @param string $locale
     * @param string $name
     * @return DbText
     */
    protected function buildText(string $locale, string $name): DbText
    {
        return $this->getFactory()->buildDbText($locale, $name, $this->repository);
    }
}