<?php
/**
 * Date: 12.05.2020
 * Time: 21:39
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language;

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
            $config['table'] = 'kw_text';
        }
        if (!isset($config['columns']) || !$config['columns']) {
            $config['columns'] = [];
        }
        if (!isset($config['connection']) || !$config['connection']) {
            $config['connection'] = new Connection(DriverFactory::buildFromArray($config));
        }
        $columns = ['id' => 'id', 'locale' => 'locale', 'params' => 'params', 'name' => 'name', 'text' => 'text'];
        $config['columns'] = array_merge($config['columns'], $columns);
        $this->config = $config;
        $this->conn = $config['connection'];
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
        $columns = &$this->config['columns'];
        $filter = [$columns['id'] => $id, $columns['locale'] => $locale, $columns['name'] => $name];
        if (!$text = $this->conn->selectOne($this->config['table'], [$columns['text'], $columns['params']], $filter)->prepareExecute('num')) {
            $text = ['', '[]'];
        }
        $text[1] = unserialize($text[1]);
        return $text;
    }
}