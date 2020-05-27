<?php

namespace kradwhite\tests\unit;

use kradwhite\language\text\DbConfig;
use kradwhite\language\text\DbTexts;
use kradwhite\language\text\SqlTextRepository;
use kradwhite\language\text\TextFactory;

class DbTextsTest extends \Codeception\Test\Unit
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
    public function testCreate()
    {
        (new DbTexts(
            'database',
            ['names'],
            $this->make(TextFactory::class),
            $this->make(DbConfig::class),
            $this->make(SqlTextRepository::class, ['createTable' => null])
        ))->create(['ru']);
    }
}