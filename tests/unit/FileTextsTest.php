<?php

namespace kradwhite\tests\unit;

use kradwhite\language\LangException;
use kradwhite\language\text\FileText;
use kradwhite\language\text\FileTexts;
use kradwhite\language\text\TextFactory;

class FileTextsTest extends \Codeception\Test\Unit
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
    public function testTextFailFileNotExist()
    {
        $this->tester->expectThrowable(new LangException("Файл '/ru/errors.php' не существует"), function () {
            $factory = $this->make(TextFactory::class, ['buildFileText' => $this->make(FileText::class)]);
            $texts = new FileTexts('php', ['names' => ['errors']], $factory, '');
            $texts->getText('ru', 'errors');
        });
    }

    public function testTextSuccess()
    {
        $factory = $this->make(TextFactory::class, ['buildFileText' => $this->make(FileText::class)]);
        $texts = new FileTexts('php', ['names' => ['errors']], $factory, __DIR__ . '/../_data');
        $text = $texts->getText('ru', 'errors');
        $this->assertInstanceOf(FileText::class, $text);
    }

    public function testInitTextsFileDirectoryNotFound()
    {
        $this->tester->expectThrowable(new LangException("Для ресурсов типа 'php' требуется имя директории 'directory' => 'path'"), function () {
            (new FileTexts('php', ['names'], $this->make(TextFactory::class), ''))->create(['ru']);
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
        (new FileTexts('php', ['test1', 'test2'], $this->make(TextFactory::class), $path))->create(['ru', 'en']);
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
}