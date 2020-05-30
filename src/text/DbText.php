<?php
/**
 * Date: 13.05.2020
 * Time: 7:46
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language\text;

use kradwhite\language\LangException;

/**
 * Class DbText
 * @package kradwhite\language
 */
class DbText extends Text
{
    /** @var TextRepository */
    private TextRepository $repository;

    /**
     * DbText constructor.
     * @param string $locale
     * @param string $name
     * @param TextRepository $repository
     */
    public function __construct(string $locale, string $name, TextRepository $repository)
    {
        parent::__construct($locale, $name, []);
        $this->repository = $repository;
    }

    /**
     * @param string $id
     * @param array $params
     * @return string
     * @throws LangException
     */
    public function phrase(string $id, array $params = []): string
    {
        if (!isset($this->texts[$id])) {
            $this->texts[$id] = $this->repository->loadPhrase($this->locale, $this->name, $id);
        }
        return parent::phrase($id, $params);
    }
}