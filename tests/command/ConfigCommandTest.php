<?php

namespace kradwhite\tests\command;

class ConfigCommandTest extends \Codeception\Test\Unit
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
    public function testExecuteSuccess()
    {
        $cd = getcwd();
        if (file_exists("$cd/tests/_data/work/language.php")) {
            $this->tester->deleteFile("$cd/tests/_data/work/language.php");
        }
        $this->tester->runShellCommand('php lang config -p tests/_data/work');
        $this->assertFileExists("$cd/tests/_data/work/language.php");
        $this->tester->deleteFile("$cd/tests/_data/work/language.php");
    }
}