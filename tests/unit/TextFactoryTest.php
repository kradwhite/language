<?php

namespace kradwhite\tests\unit;

use kradwhite\language\LangException;
use kradwhite\language\text\DbText;
use kradwhite\language\text\DbTexts;
use kradwhite\language\text\FileText;
use kradwhite\language\text\FileTexts;
use kradwhite\language\text\SqlTextRepository;
use kradwhite\language\text\TextFactory;

class TextFactoryTest extends \Codeception\Test\Unit
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

    public function testBuildFileText()
    {
        $text = (new TextFactory())->buildFileText('ru', 'name', []);
        $this->assertInstanceOf(FileText::class, $text);
    }

    public function testBuildDbText()
    {
        $text = (new TextFactory())->buildDbText('ru', 'name', $this->make(SqlTextRepository::class));
        $this->assertInstanceOf(DbText::class, $text);
    }

    public function testBuildFileTextNotFoundDirectory()
    {
        $this->tester->expectThrowable(new LangException('dir-key-not-found', ['php']), function () {
            (new TextFactory())->buildFileTexts(['type' => 'php']);
        });
    }

    public function testBuildFileTextSuccess()
    {
        $filename = __DIR__ . '/../_data';
        $texts = (new TextFactory())->buildFileTexts(['type' => 'php', 'directory' => $filename, 'names' => []]);
        $this->assertInstanceOf(FileTexts::class, $texts);
    }

    public function testBuildDbTextSuccess()
    {
        $connection = $this->tester->getConnectionConfig();
        $text = (new TextFactory())->buildDbTexts(['type' => 'database', 'connection' => $connection, 'names' => []]);
        $this->assertInstanceOf(DbTexts::class, $text);
    }

    // tests
    public function testBuildTextsFailTextsArrayNotFound()
    {
        $this->tester->expectThrowable(new LangException('texts-not-found'), function () {
            (new TextFactory())->buildTexts([]);
        });
    }

    public function testBuildTextsFailNamesArrayNotFound()
    {
        $this->tester->expectThrowable(new LangException('names-not-found'), function () {
            (new TextFactory())->buildTexts(['texts' => [[]]]);
        });
    }

    public function testBuildTextsFailWrongType()
    {
        $this->tester->expectThrowable(new LangException('type-unknown', ['wrong']), function () {
            (new TextFactory())->buildTexts(['texts' => [['type' => 'wrong', 'names' => []]]]);
        });
    }

    public function testBuildTextsSuccess()
    {
        $texts = (new TextFactory())->buildTexts(['texts' => [['type' => 'php', 'names' => [], 'directory' => 'path']]]);
        $this->assertIsArray($texts);
        $this->assertCount(1, $texts);
        $this->assertInstanceOf(FileTexts::class, $texts[0]);
    }
}