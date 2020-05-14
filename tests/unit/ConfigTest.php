<?php

namespace kradwhite\tests\unit;

use kradwhite\language\Config;
use kradwhite\language\LangException;
use kradwhite\language\TextFactory;

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
        $this->tester->expectThrowable(new LangException("Конфигурация ресурсов должна содержать массив 'texts' => ['type' => 'php|sql', 'names' => ['имена файлов без пути и расширения']]"), function () {
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
}