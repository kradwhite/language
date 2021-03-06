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
use kradwhite\db\exception\DbException;
use kradwhite\db\exception\PdoException;
use kradwhite\db\exception\PdoStatementException;
use kradwhite\language\LangException;

/**
 * Class SqlTextRepository
 * @package kradwhite\language
 */
class SqlTextRepository implements TextRepository
{
    /** @var Connection */
    private Connection $conn;

    /** @var DbConfig */
    private DbConfig $config;

    /**
     * SqlTextRepository constructor.
     * @param DbConfig $config
     * @throws LangException
     * @throws BeforeQueryException
     */
    public function __construct(DbConfig $config)
    {
        $this->conn = new Connection(DriverFactory::buildFromArray($config->connection()));
        $this->config = $config;
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
        $cs = $this->config->columns();
        $filter = [$cs['id'] => $id, $cs['locale'] => $locale, $cs['name'] => $name];
        if ($text = $this->conn->selectOne($this->config->table(), [$cs['text'], $cs['params']], $filter)
            ->prepareExecute('num')) {
            $text[1] = json_decode($text[1], true);
        }
        return $text;
    }

    /**
     * @param string $name
     * @param array $columns
     * @param array $locales
     * @return void
     * @throws BeforeQueryException
     * @throws PdoException
     * @throws DbException
     * @throws LangException
     */
    public function createTable(string $name, array $columns, array $locales)
    {
        if (!in_array($name, $this->conn->meta()->tables($this->config->connection()['dbName']))) {
            $this->conn->table($name)
                ->addColumn($columns['id'], 'string', ['limit' => 64, 'null' => false])
                ->addColumn($columns['locale'], 'string', ['limit' => 5, 'null' => false])
                ->addColumn($columns['name'], 'string', ['limit' => 64, 'null' => false])
                ->addColumn($columns['text'], 'string', ['limit' => $this->config->textLimit(), 'null' => false])
                ->addColumn($columns['params'], 'string', ['limit' => $this->config->paramsLimit(), 'null' => false, 'default' => json_encode([])])
                ->addIndex([$columns['locale'], $columns['name'], $columns['id']], ['unique' => true])
                ->create();
        }
    }
}