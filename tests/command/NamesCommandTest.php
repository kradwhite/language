<?php

namespace kradwhite\tests\command;

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
        $this->tester->runShellCommand('php lang names -p tests/_data/config');
        $this->tester->canSeeInShellOutput("Имена всех конфигурация языка:\nerrors\n");
    }
}