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
                'additional' => Str::after($column->Type, ')') ? Str::after($column->Type, ')') : null
            ];
            if (Str::between($column->Type, '(', ')') != $column->Type) {
                $columnsInfo[$column->Field]['length'] = Str::between($column->Type, '(', ')');
            }
        }

        return $columnsInfo;
    }

}