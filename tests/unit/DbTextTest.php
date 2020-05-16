<?php

namespace kradwhite\tests\unit;

use kradwhite\language\text\DbText;
use kradwhite\language\text\SqlTextRepository;

class DbTextTest extends \Codeception\Test\Unit
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
    public function testPhrase()
    {
        $repo = $this->make(SqlTextRepository::class, ['loadPhrase' => ['Phrase', []]]);
        $phrase = (new DbText('', '', $repo))->phrase('phrase', []);
        $this->assertEquals($phrase, 'Phrase');
    }
}