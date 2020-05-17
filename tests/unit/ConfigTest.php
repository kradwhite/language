<?php

namespace kradwhite\tests\unit;

use kradwhite\language\Config;
use kradwhite\language\LangException;
use kradwhite\language\text\TextFactory;

class ConfigTest extends \Codeception\Test\Unit
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
    public function testInitFailWrongFactory()
    {
        $this->tester->expectThrowable(new LangException("Класс фабрики 'wrong' должен наследоваться " . TextFactory::class), function () {
            new Config(['factory' => 'wrong']);
        });
    }

    public function testInitFailNotFoundTexts()
    {
        $this->tester->expectThrowable(new LangException("Конфигурация ресурсов должна содержать массив 'texts' => ['type' => 'php|database', 'names' => ['имена файлов без пути и расширения']]"), function () {
            new Config([]);
        });
    }

    public function testInitSuccess()
    {
        $config = new Config(['texts' => []]);
        $this->assertEquals('default', $config->defaultName());
        $this->assertInstanceOf(TextFactory::class, $config->factory());
    }

    public function testConfigByNameEmpty()
    {
        $this->tester->expectThrowable(new LangException("Имя ресурса 'wrong' не найдено"), function () {
            (new Config(['texts' => []]))->configByName('wrong');
        });
    }

    public function testConfigByNameNotFound()
    {
        $this->tester->expectThrowable(new LangException("Имя ресурса 'not found' не найдено"), function () {
            (new Config(['texts' => [['names' => ['one']], ['names' => ['two']]]]))->configByName('not found');
        });
    }

    public function testConfigByNameFound()
    {
        $config = new Config(['texts' => [['names' => ['one']], ['names' => ['two']]]]);
        $result = $config->configByName('one');
        $this->assertEquals(['names' => ['one']], $result);
    }

    public function testCreateFailDirectoryNotExist()
    {
        $this->tester->expectThrowable(new LangException("Директория 'path/not/exist' не существует"), function () {
            (new Config(['texts' => [['names' => ['one']], ['names' => ['two']]]]))->create('path/not/exist');
        });
    }

    public function testCreateFailNotDirectory()
    {
        $path = __DIR__ . '/../_data/language.php';
        $this->tester->expectThrowable(new LangException("'$path' не является директорией"), function () use ($path) {
            (new Config(['texts' => [['names' => ['one']], ['names' => ['two']]]]))->create($path);
        });
    }

    public function testCreateFailAlreadyExist()
    {
        $path = __DIR__ . '/../_data';
        $this->tester->expectThrowable(new LangException("Файл конфигурации '$path/language.php' уже существует"), function () use ($path) {
            (new Config(['texts' => [['names' => ['one']], ['names' => ['two']]]]))->create($path);
        });
    }

    public function testCreateFailSourceNotFound()
    {
        $path = __DIR__ . '/../_data';
        $this->tester->expectThrowable(new LangException("Файл конфигурации '$path/language.php' уже существует"), function () use ($path) {
            (new Config(['texts' => [['names' => ['one']], ['names' => ['two']]]]))->create($path);
        });
    }

    public function testCreateSuccess()
    {
        $this->tester->amInPath('tests/_data');
        $pwd = getcwd();
        if (file_exists("$pwd/language/language.php")) {
            $this->tester->deleteFile("$pwd/language/language.php", '<?php');
        }
        (new Config(['texts' => [['names' => ['one']], ['names' => ['two']]]]))->create($pwd . '/language');
        $this->assertFileExists($pwd . '/language/language.php');
    }
}