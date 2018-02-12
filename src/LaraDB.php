<?php

namespace LaraSupport;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use Illuminate\Filesystem\Filesystem;


class LaraDB
{

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

    public static function getColumnsFullInfo($table)
    {
//        $columns = DB::select('show columns from ' . $table);
//        foreach ($columns as $value) {
//            echo "'" . $value->Field . "' => '" . $value->Type . "|" . ( $value->Null == "NO" ? 'required' : '' ) ."', <br/>" ;
//        }
//        $columns = DB::select('show columns from ' . $table);
//
    }


    protected function greateTableTemplate($table)
    {
//        $columns = Schema::getColumnListing($table);
//        unset($columns[array_search('id', $columns)]);
//        return implode(PHP_EOL, $columns);
    }
}