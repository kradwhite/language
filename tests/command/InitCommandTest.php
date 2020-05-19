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
        if(file_exists("$cd/src/language")){
            $this->tester->deleteDir("$cd/src/language");
        }
        $this->tester->runShellCommand('php lang init -p src');
        $this->assertDirectoryExists("$cd/src/language");
        $this->assertDirectoryExists("$cd/src/language/ru");
        $this->assertFileExists("$cd/src/language/ru/errors.php");
        $this->tester->deleteDir("$cd/src/language");
    }
}