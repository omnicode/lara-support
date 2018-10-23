<?php

/***********************************************************************************
 *                              String helpers                                     *
 ***********************************************************************************/

if (!function_exists('h')) {
    /**
     * Convenience method for htmlspecialchars.
     *
     * @param string|array|object $text Text to wrap through htmlspecialchars. Also works with arrays, and objects.
     *    Arrays will be mapped and have all their elements escaped. Objects will be string cast if they
     *    implement a `__toString` method. Otherwise the class name will be used.
     * @param bool $double Encode existing html entities.
     * @param string|null $charset Character set to use when escaping. Defaults to config value in `mb_internal_encoding()`
     * or 'UTF-8'.
     * @return string Wrapped text.
     * @link http://book.cakephp.org/3.0/en/core-libraries/global-constants-and-functions.html#h
     */
    function h($text, $double = true, $charset = null)
    {
        return \LaraSupport\Str::h($text, $double, $charset);
    }
}

if (!function_exists('last_chars')) {
    /**
     * returns given amount of characters counting backwards
     *
     * @param string $str
     * @param int $count
     * @return string
     */
    function last_chars($str, $count = 1)
    {
        return \LaraSupport\Str::lastChars($str, $count);
    }
}


if (!function_exists('create_slug')) {
    /**
     * create slug from string
     *
     * @param string $str
     * @param string $symbol
     * @return string - e.g. in word1-word2-word3 format
     */
    function create_slug($str = "", $symbol = "-")
    {
        return \LaraSupport\Str::createSlug($str, $symbol);
    }
}

if (!function_exists('_humanize')) {
    /**
     * @param $val
     * @return string
     */
    function _humanize($val)
    {
        $val = str_replace("_", "", $val);
        $matches = preg_split('/(?=[A-Z])/', $val);
        return trim(implode(" ", $matches));
    }
}


if (!function_exists('shorten')) {
    /**
     * returns the short string based on $length if string's length is more than $length
     *
     * @param string $str
     * @param number $length
     * @param bool $raw
     * @return string
     */
    function shorten($str = '', $length = null, $raw = false)
    {
        if ($length === null) {
            $length = defined('_PHP_UTIL_SHORTEN_LENGTH') ? _PHP_UTIL_SHORTEN_LENGTH : 50;
        }

        if (mb_strlen($str) > $length) {
            $shortStr = mb_substr($str, 0, $length) . "...";

            if ($raw) {
                return h($shortStr);
            }
        } else {
            return h($str);
        }

        return '<span title="' . h(str_ireplace("/", "", $str)) . '">' . h($shortStr) . '</span>';
    }
}

/***********************************************************************************
 *                              Array helpers                                      *
 ***********************************************************************************/

if (!function_exists('get_range')) {
    /**
     * returns array with options for select box
     *
     * @param $min
     * @param $max
     * @param int $step
     * @return array
     */
    function get_range($min, $max, $step = 1)
    {
        return \LaraSupport\Arr::range($min, $max, $step);
    }
}


if (!function_exists('first_key')) {
    /**
     * returns the first key of the array
     *
     * @param array $array
     * @return mixed
     */
    function first_key(array $array = [])
    {
        return \LaraSupport\Arr::firstKey($array);
    }
}

if (!function_exists('last_key')) {
    /**
     * returns the last key of the array
     *
     * @param array $array
     * @return mixed
     */
    function get_last_key(array $array)
    {
        return \LaraSupport\Arr::lastKey($array);
    }
}

if (!function_exists('array_unset')) {
    /**
     * unsets array's items by value
     *
     * @param array $array - the original array
     * @param array|string - the value or array of values to be unset
     * @return array - the processed array
     */
    function array_unset(array $array, $values = [])
    {

        return \LaraSupport\Arr::unset($array, $values);
    }
}

if (!function_exists('array_i_unique')) {
    /**
     * case-insensitive array_unique
     *
     * @param array
     * @return array
     * @link http://stackoverflow.com/a/2276400/932473
     */
    function array_i_unique(array $array)
    {
        return \LaraSupport\Arr::iUnique($array);
    }
}

if (!function_exists('in_array_i')) {
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
    function in_array_i($needle, $haystack)
    {
        return \LaraSupport\Arr::inArrayI($needle, $haystack);
    }
}

if (!function_exists('is_numeric_array')) {
    /**
     * check if array's keys are all numeric
     *
     * @param array
     * @return bool
     * @link https://codereview.stackexchange.com/q/201/32948
     */
    function is_numeric_array($array)
    {
        return \LaraSupport\Arr::isNumeric($array);
    }
}
