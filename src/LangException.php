<?php
/**
 * Date: 12.05.2020
 * Time: 7:56
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language;

use Throwable;

/**
 * Class LangException
 * @package kradwhite\language
 */
class LangException extends \Exception
{
    /**
     * LangException constructor.
     * @param string $id
     * @param array $params
     * @param Throwable|null $previous
     * @throws LangException
     */
    public function __construct(string $id, array $params = [], ?Throwable $previous = null)
    {
        parent::__construct(LocalLang::init()->phrase('exceptions', $id, $params), 0, $previous);
    }
}