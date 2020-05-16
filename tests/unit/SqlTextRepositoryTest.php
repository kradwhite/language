<?php

namespace kradwhite\tests\unit;

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
        $config = ['connection' => $this->tester->getConnectionConfig()];
        $repository = new SqlTextRepository($config);
        $phrase = $repository->loadPhrase('wrong', 'name', 'id');
        $this->assertIsArray($phrase);
        $this->assertEmpty($phrase);
    }

    public function testLoadPhraseFound()
    {
        $config = ['connection' => $this->tester->getConnectionConfig()];
        $repository = new SqlTextRepository($config);
        $phrase = $repository->loadPhrase('ru', 'errors', 'update-error');
        $this->assertIsArray($phrase);
        $this->assertNotEmpty($phrase);
        $this->assertCount(2, $phrase);
        $this->assertEquals('error message: %s', $phrase[0]);
        $this->assertEquals([], $phrase[1]);
    }
}