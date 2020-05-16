<?php

namespace kradwhite\tests\unit;

use kradwhite\language\Config;
use kradwhite\language\text\FileText;
use kradwhite\language\text\TextFactory;
use kradwhite\language\text\Texts;

class TextsTest extends \Codeception\Test\Unit
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
    public function testText()
    {
        $config = $this->make(Config::class, [
            'defaultName' => 'name',
            'factory' => $this->make(TextFactory::class, ['buildText' => $this->make(FileText::class)]),
            'configByName' => [],
        ]);
        $config = new Texts($config);
        $result = $config->getText('', '');
        $this->assertInstanceOf(FileText::class, $result);
        $result = $config->getText('', 'default');
        $this->assertInstanceOf(FileText::class, $result);
    }
}