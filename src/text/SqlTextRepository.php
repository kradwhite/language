<?php
/**
 * Date: 12.05.2020
 * Time: 21:39
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language\text;

use kradwhite\db\Connection;
use kradwhite\db\driver\DriverFactory;
use kradwhite\db\exception\BeforeQueryException;
use kradwhite\db\exception\PdoException;
use kradwhite\db\exception\PdoStatementException;

/**
 * Class SqlTextRepository
 * @package kradwhite\language
 */
class SqlTextRepository implements TextRepository
{
    /** @var Connection */
    private Connection $conn;

    /** @var array */
    private array $config;

    /**
     * SqlTextRepository constructor.
     * @param array $config
     * @throws BeforeQueryException
     */
    public function __construct(array &$config)
    {
        if (!isset($config['table']) || !$config['table']) {
            $config['table'] = 'kw_language';
        }
        if (!isset($config['columns']) || !$config['columns']) {
            $config['columns'] = [];
        }
        $columns = ['id' => 'id', 'locale' => 'locale', 'params' => 'params', 'name' => 'name', 'text' => 'text'];
        $config['columns'] = array_merge($config['columns'], $columns);
        $this->config = $config;
        $this->conn = new Connection(DriverFactory::buildFromArray($config['connection']));
    }

    /**
     * @param string $locale
     * @param string $name
     * @param string $id
     * @return array
     * @throws BeforeQueryException
     * @throws PdoException
     * @throws PdoStatementException
     */
    public function loadPhrase(string $locale, string $name, string $id): array
    {
        $cs = &$this->config['columns'];
        $filter = [$cs['id'] => $id, $cs['locale'] => $locale, $cs['name'] => $name];
        if ($text = $this->conn->selectOne($this->config['table'], [$cs['text'], $cs['params']], $filter)
            ->prepareExecute('num')) {
            $text[1] = json_decode($text[1], true);
        }
        return $text;
    }
}