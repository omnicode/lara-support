<?php

namespace LaraSupport;

use Illuminate\Support\Facades\DB;

class LaraDB
{
    /**
     * @return array
     */
    public static  function getTables()
    {
        $tablesInDb = DB::select('SHOW TABLES');
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
    public static function getColumnsFullInfo($table)
    {
        $columns = DB::select('show columns from ' . $table);
        $columnsInfo = [];

        foreach ($columns as $column) {
            $columnsInfo[$column->Field] = [
                'type' => Str::before($column->Type, '('),
                'is_nullable' => $column->Null ? true : false,
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
    public static function getDBStructure()
    {
        $query = sprintf("SELECT * FROM information_schema.columns WHERE table_schema ='%s'", env('DB_DATABASE'));
        $dbStructures = DB::select($query);
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