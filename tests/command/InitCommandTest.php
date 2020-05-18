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
        $this->tester->amInPath('src');
        $cd = getcwd();
        if(file_exists("$cd/language")){
            $this->tester->deleteDir("$cd/language");
        }
        $this->tester->runShellCommand('php index.php init');
        $this->assertDirectoryExists("$cd/language");
        $this->assertDirectoryExists("$cd/language/ru");
        $this->assertFileExists("$cd/language/ru/errors.php");
        $this->tester->deleteDir("$cd/language");
    }
}