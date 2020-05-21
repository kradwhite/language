<?php

namespace kradwhite\tests\unit;

use kradwhite\db\Connection;
use kradwhite\db\driver\DriverFactory;
use kradwhite\language\text\DbConfig;
use kradwhite\language\text\SqlTextRepository;

class SqlTextRepositoryTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testLoadPhraseNotFound()
    {
        $config = new DbConfig(['connection' => $this->tester->getConnectionConfig()]);
        $repository = new SqlTextRepository($config);
        $phrase = $repository->loadPhrase('wrong', 'name', 'id');
        $this->assertIsArray($phrase);
        $this->assertEmpty($phrase);
    }

    public function testLoadPhraseFound()
    {
        $config = new DbConfig(['connection' => $this->tester->getConnectionConfig()]);
        $repository = new SqlTextRepository($config);
        $phrase = $repository->loadPhrase('ru', 'errors', 'update-error');
        $this->assertIsArray($phrase);
        $this->assertNotEmpty($phrase);
        $this->assertCount(2, $phrase);
        $this->assertEquals('error message: %s', $phrase[0]);
        $this->assertEquals([], $phrase[1]);
    }

    public function testCreateTableIfExist()
    {
        $config = new DbConfig(['connection' => $this->tester->getConnectionConfig()]);
        $repository = new SqlTextRepository($config);
        $repository->createTable($config->table(), $config->columns(), []);
    }

    public function testCreateTableIfNotExist()
    {
        $config = new DbConfig(['connection' => $this->tester->getConnectionConfig()]);
        (new Connection(DriverFactory::buildFromArray($this->tester->getConnectionConfig())))->table($config->table())->drop();
        $repository = new SqlTextRepository($config);
        $repository->createTable($config->table(), $config->columns(), []);
    }
}