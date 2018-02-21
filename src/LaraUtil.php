<?php

namespace LaraSupport;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class LaraUtil
{

    /**
     * returns the relation type as string (without namespace)
     *
     * @param Object $relation
     * @return mixed
     * @throws \Exception
     */
    public static function getRelationType($relation = null)
    {
        if (!is_object($relation)) {
            throw new \Exception('Invalid Relation');
        }

        $array = array_reverse(explode("\\", get_class($relation)));
        return reset($array);
    }


    /**
     * checks if the given table exists - and caches the result
     *
     * @param $table
     * @return mixed
     */
    public static function hasTable($table)
    {
        $minutes = Config::get('lara_util.cache.time');

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
    public static function hasColumn($table, $column)
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
    public static function getFullColumns($columns = [], $table, $prefix = '')
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
            if (is_string($column)) {
                if (!stristr($column, '.')) {
                    $origCol = $column;
                    $column = $table . '.' . str_replace($table . '.', '', $column);

                    if ($prefix) {
                        if (is_bool($prefix)) {
                            $column .= ' as ' . $column;
                        } else {
                            $column .= ' as ' . ($prefix . '_' . $origCol);
                        }
                    }
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
     * @param $password
     * @return bool|string
     */
    public static function hashPassword($password)
    {
        // hash to remove initial bcrypt restriction
        // @link https://security.stackexchange.com/a/6627/38200
        $password = hash('sha256', $password);
        $cost = Config::get('lara_util.security.password.cost');
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
        return encrypt($hash);
    }


    /**
     * @param $password
     * @param $passwordHash
     * @return bool
     * @throws \Exception
     */
    public static function verifyPassword($password, $passwordHash)
    {
        try {
            $hash = decrypt($passwordHash);
        } catch (DecryptException $e) {
            return false;
        }

        $password = hash('sha256', $password);
        return password_verify($password, $hash);
    }

}
