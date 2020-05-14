<?php

namespace kradwhite\tests\unit;

use kradwhite\language\Config;
use kradwhite\language\LangException;
use kradwhite\language\TextFactory;
use kradwhite\language\Texts;

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

    // tests
    public function testBuildTexts()
    {
        $texts = (new TextFactory())->buildTexts($this->make(Config::class));
        $this->assertInstanceOf(Texts::class, $texts);
    }

    public function testBuildTextWrongType()
    {
        $this->tester->expectThrowable(new LangException("Неизвестный тип 'wrong' ресурсов"), function () {
            (new TextFactory())->buildText(['type' => 'wrong'], 'ru', 'name');
        });
    }

    public function testBuildFileTextNotFoundDirectory()
    {
        $this->tester->expectThrowable(new LangException("Для ресурсов типа 'php' требуется имя директории 'directory' => 'path'"), function () {
            (new TextFactory())->buildText(['type' => 'php'], 'ru', 'name');
        });
    }

    public function testBuildFileTextNotFoundFile()
    {
        $filename = __DIR__ . '/../_data';
        $this->tester->expectThrowable(new LangException("Файл '$filename/ru/name.php' не существует"), function () use ($filename) {
            (new TextFactory())->buildText(['type' => 'php', 'directory' => $filename], 'ru', 'name');
        });
    }
}