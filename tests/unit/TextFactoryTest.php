<?php

namespace kradwhite\tests\unit;

use Codeception\Stub;
use kradwhite\language\Config;
use kradwhite\language\LangException;
use kradwhite\language\SqlTextRepository;
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

    public function testBuildFileTextSuccess()
    {
        $filename = __DIR__ . '/../_data';
        $text = (new TextFactory())->buildText(['type' => 'php', 'directory' => $filename], 'ru', 'errors');
        $message = $text->phrase('update-error', ['variable']);
        $this->assertEquals('error message: variable', $message);
    }

    public function testBuildDbTextNotFoundConnection()
    {
        $this->tester->expectThrowable(new LangException("Для ресурсов типа 'sql' требуется конфигурация 'connection' => []"), function () {
            (new TextFactory())->buildText(['type' => 'sql'], 'ru', 'name');
        });
    }

    public function testBuildDbTextSuccess()
    {
        $connection = ['user' => 'admin', 'password' => 'admin', 'host' => 'pgsql', 'dbName' => 'test-2', 'driver' => 'pgsql'];
        $text = (new TextFactory())->buildText(['type' => 'sql', 'connection' => $connection], 'ru', 'errors');
        $message = $text->phrase('update-error', ['variable']);
        $this->assertEquals('error message: variable', $message);
    }
}