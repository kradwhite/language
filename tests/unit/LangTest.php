<?php

namespace kradwhite\tests\unit;

use kradwhite\language\Config;
use kradwhite\language\text\FileText;
use kradwhite\language\Lang;
use kradwhite\language\text\Text;
use kradwhite\language\text\TextFactory;
use kradwhite\language\text\Texts;

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
    public function testText()
    {
        $text = $this->make(Text::class);
        $config = $this->make(Config::class,
            ['factory' => $this->make(TextFactory::class,
                ['buildTexts' => $this->make(Texts::class, ['getText' => $text])])]);
        $app = new Lang($config);
        $result = $app->text();
        $this->assertEquals($text, $result);
    }

    public function testLocale()
    {
        $config = $this->make(Config::class,
            ['factory' => $this->make(TextFactory::class,
                ['buildTexts' => $this->make(Texts::class)])]);
        $app = new Lang($config);
        $result = $app->locale();
        $this->assertEquals('ru', $result);
    }

    public function testPhrase()
    {
        $text = $this->make(FileText::class, ['phrase' => 'message']);
        $config = $this->make(Config::class,
            ['factory' => $this->make(TextFactory::class,
                ['buildTexts' => $this->make(Texts::class, ['getText' => $text])])]);
        $app = new Lang($config);
        $result = $app->phrase('name', 'id');
        $this->assertEquals('message', $result);
    }
}