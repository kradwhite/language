<?php

namespace kradwhite\tests\unit;

use kradwhite\db\Connection;
use kradwhite\db\driver\DriverFactory;
use kradwhite\language\Config;
use kradwhite\language\LangException;
use kradwhite\language\text\TextFactory;
use kradwhite\language\text\TextRepository;
use kradwhite\language\text\Texts;

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
        $this->tester->expectThrowable(new LangException("Для ресурсов типа 'database' требуется конфигурация 'connection' => []"), function () {
            (new TextFactory())->buildText(['type' => 'database'], 'ru', 'name');
        });
    }

    public function testBuildDbTextWrongRepositoryClass()
    {
        $this->tester->expectThrowable(new LangException("Класс репозитория 'class' должен реализовывать интерфейс '" . TextRepository::class . "'"), function () {
            (new TextFactory())->buildText(['type' => 'database', 'repository' => 'class', 'connection' => []], 'ru', 'name');
        });
    }

    public function testBuildDbTextSuccess()
    {
        $connection = $this->tester->getConnectionConfig();
        $text = (new TextFactory())->buildText(['type' => 'database', 'connection' => $connection], 'ru', 'errors');
        $message = $text->phrase('update-error', ['variable']);
        $this->assertEquals('error message: variable', $message);
    }

    public function testInitTextsFileDirectoryNotFound()
    {
        $this->tester->expectThrowable(new LangException("Для ресурсов типа 'php' требуется имя директории 'directory' => 'path'"), function () {
            (new TextFactory())->initTexts(['type' => 'php'], ['ru']);
        });
    }

    public function testInitTextsFileSuccess()
    {
        $this->tester->amInPath('tests/_data');
        $path = getcwd();
        if (file_exists("$path/en")) {
            $this->tester->deleteDir("$path/en");
        }
        if (file_exists("$path/ru/test1.php")) {
            $this->tester->deleteFile("$path/ru/test1.php");
        }
        if (file_exists("$path/ru/test2.php")) {
            $this->tester->deleteFile("$path/ru/test2.php");
        }
        (new TextFactory())->initTexts(['type' => 'php', 'names' => ['test1', 'test2'], 'directory' => $path], ['ru', 'en']);
        $this->assertDirectoryExists("$path/en");
        $this->assertFileExists("$path/en/test1.php");
        $this->assertFileExists("$path/en/test2.php");
        $this->assertFileExists("$path/ru/test1.php");
        $this->assertFileExists("$path/ru/test2.php");
        chmod("$path/en", 0777);
        chmod("$path/en/test1.php", 0666);
        chmod("$path/en/test2.php", 0666);
        chmod("$path/ru/test1.php", 0666);
        chmod("$path/ru/test2.php", 0666);
    }

    public function testInitTextsDbExistTable()
    {
        (new TextFactory())->initTexts(['type' => 'database', 'connection' => $this->tester->getConnectionConfig()], ['ru']);
    }

    public function testInitTextsDbNotExistTable()
    {
        (new Connection(DriverFactory::buildFromArray($this->tester->getConnectionConfig())))->table('kw_language')->drop();
        (new TextFactory())->initTexts(['type' => 'database', 'connection' => $this->tester->getConnectionConfig()], ['ru']);
    }
}