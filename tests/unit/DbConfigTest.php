<?php

namespace kradwhite\tests\unit;

use kradwhite\language\LangException;
use kradwhite\language\text\DbConfig;

class DbConfigTest extends \Codeception\Test\Unit
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
    public function testTable()
    {
        $this->assertEquals('table', (new DbConfig(['table' => 'table']))->table());
        $this->assertEquals('kw_language', (new DbConfig([]))->table());
    }

    public function testDefaultColumns()
    {
        $columns = (new DbConfig([]))->columns();
        $this->assertCount(5, $columns);
        $this->assertArrayHasKey('id', $columns);
        $this->assertArrayHasKey('locale', $columns);
        $this->assertArrayHasKey('params', $columns);
        $this->assertArrayHasKey('text', $columns);
        $this->assertArrayHasKey('name', $columns);
        $this->assertEquals('id', $columns['id']);
        $this->assertEquals('locale', $columns['locale']);
        $this->assertEquals('params', $columns['params']);
        $this->assertEquals('text', $columns['text']);
        $this->assertEquals('name', $columns['name']);
    }

    public function testColumns()
    {
        $_columns = ['id' => '_i', 'locale' => '_l', 'name' => '_n', 'params' => '_p', 'text' => '_t'];
        $columns = (new DbConfig(['columns' => $_columns]))->columns();
        $this->assertCount(5, $columns);
        $this->assertArrayHasKey('id', $columns);
        $this->assertArrayHasKey('locale', $columns);
        $this->assertArrayHasKey('params', $columns);
        $this->assertArrayHasKey('text', $columns);
        $this->assertArrayHasKey('name', $columns);
        $this->assertEquals('_i', $columns['id']);
        $this->assertEquals('_l', $columns['locale']);
        $this->assertEquals('_p', $columns['params']);
        $this->assertEquals('_t', $columns['text']);
        $this->assertEquals('_n', $columns['name']);
    }

    public function testTextLimit()
    {
        $this->assertEquals(256, (new DbConfig([]))->textLimit());
        $this->assertEquals(43, (new DbConfig(['textLimit' => 43]))->textLimit());
    }

    public function testParamsLimit()
    {
        $this->assertEquals(256, (new DbConfig([]))->paramsLimit());
        $this->assertEquals(43, (new DbConfig(['paramsLimit' => 43]))->paramsLimit());
    }

    public function testConnectionFail()
    {
        $this->tester->expectThrowable(new LangException('connection-not-found', ['database']), function () {
            (new DbConfig(['type' => 'database']))->connection();
        });
    }

    public function testConfigByNameNotFound()
    {
        $connection = (new DbConfig(['connection' => ['conn']]))->connection();
        $this->assertEquals(['conn'], $connection);
    }
}