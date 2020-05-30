<?php

namespace kradwhite\tests\command;

use kradwhite\language\LocalLang;

class NamesCommandTest extends \Codeception\Test\Unit
{
    /**
     * @var \CommandTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testExecute()
    {
        $this->tester->runShellCommand('php lang names -p tests/_data/testNamesCommandExecute');
        $this->tester->canSeeInShellOutput(LocalLang::init()->phrase('messages', 'names3')
            . "\nmessages\nexceptions\nerrors\n");
    }
}