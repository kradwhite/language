<?php

namespace kradwhite\tests\unit;

use kradwhite\language\FileText;
use kradwhite\language\LangException;

class FileTextTest extends \Codeception\Test\Unit
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
    public function testPhraseNotFound()
    {
        $this->tester->expectThrowable(new LangException("Locale=ru, Name=error. Не найдена фраза с идентификатором 'not_exist'"), function () {
            (new FileText('ru', 'error', []))->phrase('not_exist', []);
        });
    }

    public function testPhraseFoundOnlyText()
    {
        $phrase = (new FileText('', '', ['only_text' => ['Simple text']]))->phrase('only_text', []);
        $this->assertEquals($phrase, 'Simple text');
    }

    public function testPhraseFoundWithParams()
    {
        $phrase = (new FileText('', '', ['with_params' => ['Simple %s', ['text']]]))->phrase('with_params', []);
        $this->assertEquals($phrase, 'Simple text');
    }

    public function testPhraseFoundWithParams2()
    {
        $phrase = (new FileText('', '', ['with_params' => ['Simple %s']]))->phrase('with_params', ['text']);
        $this->assertEquals($phrase, 'Simple text');
    }

    public function testPhraseFoundWrongParams()
    {
        $this->tester->expectThrowable(new LangException("Неверные параметры '' фразы 'Simple %s'"), function () {
            (new FileText('', '', ['wrong_params' => ['Simple %s']]))->phrase('wrong_params', []);
        });
    }
}