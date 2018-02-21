<?php

namespace LaraSupport;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class LaraDB
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * LaraDB constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    /**
     * checks if the given table exists - and caches the result
     *
     * @param $table
     * @return mixed
     */
    public function hasTable($table)
    {
        $minutes = Config::get('lara_support.cache.time', false);

        // if all cache is disabled
        if ($minutes === false) {
            return Schema::hasTable($table);
        }

        return Cache::remember('table_exists_' . $table, $minutes, function () use ($table) {
            return Schema::hasTable($table);
        });
    }

    /**
     * check if the given table has the given column - and caches the query
     *
     * @param $table
     * @param $column
     * @return bool
     */
    public function hasColumn($table, $column)
    {
        $column = str_replace($table.'.', '', $column);
        $minutes = Config::get('lara_util.cache.time');

        // if all cache is disabled
        if ($minutes === false) {
            return Schema::hasColumn($table, $column);
        }

        $columnList = Cache::remember('table_schema_' . $table, $minutes, function () use ($table) {
            return Schema::getColumnListing($table);
        });

        if (in_array($column, $columnList)) {
            return true;
        }

        return false;
    }


    /**
     * checks if table name exists in columns and adds if does not
     * @TODO - col name with dot ?
     *
     * @param array $columns
     * @param $table
     * @param string|bool $prefix
     * @return array
     */
    public function getFullColumns($columns = [], $table, $prefix = '')
    {
        $isString = false;
        if (is_string($columns)) {
            $isString = true;
            $columns = [
                $columns
            ];
        }

        $result = [];
        foreach ($columns as $column) {
            if (is_string($column) && !stristr($column, '.')) {
                $origCol = $column;
                $column = $table . '.' . str_replace($table . '.', '', $column);

                if ($prefix) {
                    $column .= is_bool($prefix) ? ' as ' . $column : ' as ' . ($prefix . '_' . $origCol);
                }
            }

            $result[] = $column;
        }

        if ($isString) {
            return $result[0];
        }

        return $result;
    }



    /**
     * @return array
     */
    public function getTables()
    {
        $tablesInDb = $this->connection->select('SHOW TABLES');
        $db = "Tables_in_".env('DB_DATABASE');
        $tables = [];

        foreach($tablesInDb as $table){
            $tables[] = $table->{$db};
        }

        return $tables;
    }

    /**
     * @param $table
     * @return array
     */
    public function getColumnsFullInfo($table)
    {
        $query = 'show columns from ' . $table;
        $columns = $this->connection->select($query);
        $columnsInfo = [];

        foreach ($columns as $column) {
            $columnsInfo[$column->Field] = [
                'type' => Str::before($column->Type, '('),
                'key' => $column->Key,
                'is_nullable' => $column->Null == 'YES' ? true : false,
                'default' => $column->Default,
                'extra' => $column->Extra,
                'unsigned' => str_contains($column->Type, 'unsigned') ? true : false,
                'full_info' => $column->Type,
            ];
            if (Str::between($column->Type, '(', ')') != $column->Type) {
                $columnsInfo[$column->Field]['length'] = Str::between($column->Type, '(', ')');
            }
        }

        return $columnsInfo;
    }

    /**
     * @return array
     */
    public function getDBStructure()
    {
        $query = sprintf("SELECT * FROM information_schema.columns WHERE table_schema ='%s'", env('DB_DATABASE'));
        $dbStructures = $this->connection->select($query);
        $tables = [];

        foreach ($dbStructures as $dbStructure) {
            if ($dbStructure->DATA_TYPE == 'int') {
            }
            $tables[$dbStructure->TABLE_NAME][$dbStructure->COLUMN_NAME] = [
                'type' => $dbStructure->DATA_TYPE,
                'is_nullable' => $dbStructure->IS_NULLABLE == 'YES' ? true : false,
                'default' => $dbStructure->COLUMN_DEFAULT,
                'extra' => $dbStructure->EXTRA,
                'unsigned' => str_contains($dbStructure->COLUMN_TYPE, 'unsigned') ? true : false,
                'column_type' => $dbStructure->COLUMN_TYPE,
            ];
            if ($dbStructure->CHARACTER_MAXIMUM_LENGTH) {
                $tables[$dbStructure->TABLE_NAME][$dbStructure->COLUMN_NAME]['length'] = $dbStructure->CHARACTER_MAXIMUM_LENGTH;
            }
        }

        return $tables;
    }
}
