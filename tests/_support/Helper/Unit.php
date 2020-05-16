<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Unit extends \Codeception\Module
{
    public function getConnectionConfig()
    {
        return [
            'user' => 'admin',
            'password' => 'admin',
            'dbName' => 'test-2',
            'host' => 'pgsql',
            'driver' => 'pgsql'
        ];
    }
}
