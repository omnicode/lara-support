<?php

namespace Tests;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use LaraSupport\LaraDB;
use LaraTest\Traits\MockTraits;

class LaraDBTest extends TestCase
{
    use MockTraits;

    /**
     * @var
     */
    protected $laraDb;

    /**
     * @var
     */
    protected $connection;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
//         $this->connection = $this->getMockObjectWithMockedMethods(['select'], Connection::class, ['pdo']);
        $this->connection = app(Connection::class);
        $this->laraDb = new LaraDB($this->connection);
    }

//     public function testHasTableMinutesIsFalse()
//     {
//         Config::set('lara_support.cache.time', false);
//         $this->assertFalse($this->laraDb->hasTable('_table_'));
//     }

//     /**
//      *
//      */
//     public function testHasTableMinutesIsNotFalse()
//     {
//         Config::set('lara_support.cache.time', 12);
//         $this->assertFalse($this->laraDb->hasTable('_table_'));
//     }

//     /**
//      *
//      */
//     public function testHasColumnMinutesIsFalse()
//     {
//         Config::set('lara_support.cache.time', false);
//         $this->assertFalse($this->laraDb->hasColumn('_table_', '_column_'));
//     }

    /**
     *
     */
    public function testHasColumnMinutesIsFalseColumnExists()
    {
        $minutes = 12;
        Config::set('lara_support.cache.time', $minutes);
        $table = 'table';
        $column = 'column';

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn([$column]);

        $this->assertTrue($this->laraDb->hasColumn($table, $column));
    }

    /**
     *
     */
    public function testHasColumnMinutesIsFalseColumnNotExists()
    {
        $minutes = 12;
        Config::set('lara_support.cache.time', $minutes);
        $table = 'table';
        $column = 'column';

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn([]);

        $this->assertFalse($this->laraDb->hasColumn($table, $column));
    }

    /**
     *
     */
    public function testGetFullColumns_ColumnIsString()
    {
        $this->assertEquals('table.column', $this->laraDb->getFullColumns('column', 'table'));
        $this->assertEquals('table.column as table.column', $this->laraDb->getFullColumns('column', 'table', true));
        $this->assertEquals('table.column', $this->laraDb->getFullColumns('column', 'table', false));
        $this->assertEquals('table.column as prefix_column', $this->laraDb->getFullColumns('column', 'table', 'prefix'));
    }

    /**
     *
     */
    public function testGetFullColumns_ColumnIsArray()
    {
        $this->assertEquals(['table.column'], $this->laraDb->getFullColumns(['column'], 'table'));
    }


//     /**
//      *
//      */
//     public function testGetTables()
//     {
//         $stdClass = new \stdClass();
//         $prop = 'Tables_in_' . env('DB_DATABASE');
//         $table = 'table';
//         $stdClass->{$prop} = $table;

//         $collection = [
//             $stdClass
//         ];

//         $this->methodWillReturn($this->connection, 'select', $collection, 'SHOW TABLES');
//         $this->assertEquals([$table], $this->laraDb->getTables());
//     }

//     /**
//      *
//      */
//     public function testGetColumnsFullInfo()
//     {
//         $table = 'table';
//         $query = 'show columns from ' . $table;

//         $stdClass = new \stdClass();
//         $stdClass->Field = 'column';
//         $stdClass->Type = 'varchar(100)';
//         $stdClass->Null = 'NO';
//         $stdClass->Key =  45 ;
//         $stdClass->Default = null;
//         $stdClass->Extra = '';

//         $collection = [
//             $stdClass
//         ];

//         $expected = [
//             'column' => [
//                 'type' => 'varchar',
//                 'key' => 45,
//                 'is_nullable' => false,
//                 'default' => null,
//                 'extra' => '',
//                 'unsigned' => false,
//                 'full_info' => 'varchar(100)',
//                 'length' => '100',
//           ]
//         ];

//         $this->methodWillReturn($this->connection, 'select', $collection, $query);
//         $this->assertEquals($expected, $this->laraDb->getColumnsFullInfo($table));
//     }

//     /**
//      *
//      */
//     public function testGetDBStructure()
//     {
//         // TODO
//         $this->assertTrue(true);
//     }

}
