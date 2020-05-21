<?php

namespace kradwhite\tests\unit;

use kradwhite\language\Lang;
use kradwhite\language\LangException;
use kradwhite\language\text\FileText;

class LangTest extends \Codeception\Test\Unit
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
    public function testInit()
    {
        $config = ['texts' => []];
        new Lang($config);
    }

    public function testLocale()
    {
        $config = ['texts' => []];
        $app = new Lang($config);
        $result = $app->locale();
        $this->assertEquals('ru', $result);
    }

    public function testPhrase()
    {
        $config = [
            'texts' => [
                [
                    'names' => ['errors'],
                    'type' => 'php',
                    'directory' => __DIR__ . '/../_data',
                ]
            ]
        ];
        $app = new Lang($config);
        $result = $app->phrase('errors', 'update-error', ['test']);
        $this->assertEquals('error message: test', $result);
    }

    public function testText()
    {
        $config = [
            'texts' => [
                [
                    'names' => ['errors'],
                    'type' => 'php',
                    'directory' => __DIR__ . '/../_data',
                ]
            ]
        ];
        $app = new Lang($config);
        $result = $app->text('errors');
        $this->assertInstanceOf(FileText::class, $result);
    }

    public function testInitConfigFailDirectoryNotExist()
    {
        $this->tester->expectThrowable(new LangException("Директория 'path/not/exist' не существует"), function () {
            (new Lang(['texts' => [['names' => ['one'], 'type' => 'php', 'directory' => 'dir']]]))->initConfig('path/not/exist');
        });
    }

    public function testInitConfigFailNotDirectory()
    {
        $path = __DIR__ . '/../_data/config/language.php';
        $this->tester->expectThrowable(new LangException("'$path' не является директорией"), function () use ($path) {
            (new Lang(['texts' => [['names' => ['one'], 'type' => 'php', 'directory' => 'dir']]]))->initConfig($path);
        });
    }

    public function testInitConfigFailAlreadyExist()
    {
        $path = __DIR__ . '/../_data/config';
        $pwd = getcwd();
        if (!file_exists("$pwd/tests/_data/config/language.php")) {
            file_put_contents("$pwd/tests/_data/config/language.php", '<?php');
        }
        $this->tester->expectThrowable(new LangException("Файл конфигурации '$path/language.php' уже существует"), function () use ($path) {
            (new Lang(['texts' => [['names' => ['one'], 'type' => 'php', 'directory' => 'dir']]]))->initConfig($path);
        });
    }

    public function testInitConfigSuccess()
    {
        $this->tester->amInPath('tests/_data');
        $pwd = getcwd();
        if (file_exists("$pwd/config/language.php")) {
            $this->tester->deleteFile("$pwd/config/language.php", '<?php');
        }
        (new Lang(['texts' => [['names' => ['one'], 'type' => 'php', 'directory' => 'dir']]]))->initConfig($pwd . '/config');
        $this->assertFileExists($pwd . '/config/language.php');
    }

    public function testNames()
    {
        $config = [
            'texts' => [
                [
                    'names' => ['errors'],
                    'type' => 'php',
                    'directory' => __DIR__ . '/../_data',
                ]
            ]
        ];
        $app = new Lang($config);
        $result = $app->names();
        $this->assertEquals(['errors'], $result);
    }
}