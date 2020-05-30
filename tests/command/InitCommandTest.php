<?php

namespace kradwhite\tests\command;

class InitCommandTest extends \Codeception\Test\Unit
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
        $cd = getcwd();
        $work = 'tests/_data/testInitCommandExecute';
        if (file_exists("$cd/$work/language")) {
            $this->tester->deleteDir("$cd/$work/language");
        }
        $this->tester->runShellCommand("php lang init -p $work");
        $this->assertDirectoryExists("$cd/$work/language");
        $this->assertDirectoryExists("$cd/$work/language/ru");
        $this->assertFileExists("$cd/$work/language/ru/errors.php");
        $this->tester->deleteDir("$cd/$work/language");
    }
}