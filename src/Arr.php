<?php

namespace LaraSupport;

use Illuminate\Support\Arr as BaseArr;

class Arr extends BaseArr
{
    /**
     * returns array with options for select box
     *
     * @param $min
     * @param $max
     * @param int $step
     * @return array
     */
    public static function range($min, $max, $step = 1)
    {
        return array_combine(range($min, $max, $step), range($min, $max, $step));
    }


     /**
     * returns the first key of the array
     *
     * @param array $array
     * @return mixed
     */
    public static function firstKey(array $array = [])
    {
        reset($array);
        return key($array);
    }

    /**
     * returns the last key of the array
     *
     * @param array $array
     * @return mixed
     */
    public static function lastKey(array $array)
    {
        $array = array_reverse($array, true);
        reset($array);
        return key($array);
    }

    /**
     * unsets array's items by value
     *
     * @param array $array - the original array
     * @param array|string - the value or array of values to be unset
     * @return array - the processed array
     */
    public static function unset(array $array, $values = [])
    {
        $values = (array) $values;
        return array_diff($array, $values);
    }

    /**
     * case-insensitive array_unique
     *
     * @param array
     * @return array
     * @link http://stackoverflow.com/a/2276400/932473
     */
    public static function iUnique(array $array)
    {
        $lowered = array_map('mb_strtolower', $array);
        return array_intersect_key($array, array_unique($lowered));
    }

    /**
     * case-insensitive in_array
     *
     * @param string $needle
     * @param array $haystack
     * @return bool
     *
     * @link http://us2.php.net/manual/en/function.in-array.php#89256
     * @link https://stackoverflow.com/a/2166524
     * @link https://stackoverflow.com/a/2166522
     */
    public static function inArrayI($needle, $haystack)
    {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }

    /**
     * check if array's keys are all numeric
     *
     * @param array
     * @return bool
     * @link https://codereview.stackexchange.com/q/201/32948
     */
    public static function isNumeric($array)
    {
        foreach ($array as $k => $v) {
            if (!is_int($k)) {
                return false;
            }
        }

        return true;
    }

}
