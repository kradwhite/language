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
        $this->tester->amInPath('src');
        $cd = getcwd();
        if(file_exists("$cd/../tests/_data/language.php")){
            $this->tester->deleteFile("$cd/../tests/_data/language.php");
        }
        $this->tester->runShellCommand('php index.php config -p ../tests/_data');
        $this->assertFileExists("$cd/../tests/_data/language.php");
        $this->tester->deleteFile("$cd/../tests/_data/language.php");
    }
}