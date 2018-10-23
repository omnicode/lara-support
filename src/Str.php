<?php

namespace LaraSupport;

use Illuminate\Support\Str as BaseStr;

class Str extends BaseStr
{
    const LAST = 'last';

    /**
     * @param $string
     * @param $search
     * @param bool $caseSensitive
     * @return array
     */
    public static function positions($string, $search, $caseSensitive = false)
    {
        $lastPos = 0;
        $positions = [];
        $length = strlen($search);
        $number = 1;
        $method = $caseSensitive ? 'stripos' : 'strpos';

        while (($lastPos = $method($string, $search, $lastPos))!== false) {
            $positions[$number++] = $lastPos;
            $lastPos = $lastPos + $length;
        }

        return $positions;
    }

    /**
     * @param $subject
     * @param $search
     * @param int $occurrence
     * @param $caseSensitive
     * @return bool|string
     */
    public static function after($subject, $search, $occurrence = 1, $caseSensitive = true)
    {
        if ($search == '') {
            return $subject;
        }

        $positions = static::positions($subject, $search, $caseSensitive);

        if ($occurrence == self::LAST) {
            $occurrence = Arr::lastKey($positions);
        }

        if (empty($positions[$occurrence])) {
            return false;
        }

        return substr($subject, $positions[$occurrence] + strlen($search));
    }

    /**
     * @param $subject
     * @param $search
     * @param string $occurrence
     * @param bool $caseSensitive
     * @return bool|string
     */
    public static function before($subject, $search, $occurrence = self::LAST, $caseSensitive = true)
    {
        if ($search == '') {
            return $subject;
        }

        $positions = static::positions($subject, $search, $caseSensitive);

        if ($occurrence == self::LAST) {
            $occurrence = Arr::lastKey($positions);
        }

        if (empty($positions[$occurrence])) {
            return false;
        }

        return substr($subject, 0, $positions[$occurrence]);
    }

    /**
     * @param $subject
     * @param $searchStart
     * @param $searchEnd
     * @param int $occurentceStart
     * @param string $occurenceEnd
     * @return bool|string
     */
    public static function between($subject, $searchStart, $searchEnd, $occurentceStart = 1, $occurenceEnd = self::LAST, $caseSensitive = false)
    {
        if ($searchStart == '' || $searchEnd == '') {
            return $subject;
        }

        $posStart = strpos($subject, $searchStart);
        $posEnd = strpos($subject, $searchEnd);

        if ($posStart === false || $posEnd === false) {
            return $subject;
        }

        $posStart += strlen($searchStart);
        return substr($subject, $posStart, $posEnd - $posStart);
    }

    /**
     * @param $string
     * @param $search
     * @param string $left
     * @param string $right
     * @param int $occurence
     * @param bool $caseSensitive
     * @return mixed
     */
    public static function wrap($string, $search, $left = '', $right = '', $occurence = 1, $caseSensitive = false)
    {
        if (!$caseSensitive) {
            return str_replace($search , $left . $search . $right , $string);
        }

        $positions = self::positions($string, $search, $caseSensitive);

        if (empty($positions)) {
            return $string;
        }

        $length = strlen($search);
        $positions = array_reverse($positions);

        foreach ($positions as $position) {
            $actual = substr($string, $position, $length);
            $actual = $left . $actual . $right;
            $string = substr_replace($string, $actual,  $position, $length);
        }

        return $string;
    }

    /**
     * @param $text
     * @param bool $double
     * @param null $charset
     * @return array|string
     */
    public static function h($text, $double = true, $charset = null)
    {
        if (is_string($text)) {
            //optimize for strings
        } elseif (is_array($text)) {
            $texts = [];
            foreach ($text as $k => $t) {
                $texts[$k] = h($t, $double, $charset);
            }
            return $texts;
        } elseif (is_object($text)) {
            if (method_exists($text, '__toString')) {
                $text = (string)$text;
            } else {
                $text = '(object)' . get_class($text);
            }
        } elseif (is_bool($text)) {
            return $text;
        }

        static $defaultCharset = false;

        if ($defaultCharset === false) {
            $defaultCharset = mb_internal_encoding();
            if ($defaultCharset === null) {
                $defaultCharset = 'UTF-8';
            }
        }

        if (is_string($double)) {
            $charset = $double;
        }

        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, ($charset) ? $charset : $defaultCharset, $double);
    }

    /**
     * returns given amount of characters counting backwards
     *
     * @param string $str
     * @param int $count
     * @return string
     */
    public static function lastChars($str, $count = 1)
    {
        return mb_substr($str, -$count, $count);
    }

    /**
     * create slug from string
     *
     * @param string $str
     * @param string $symbol
     * @return string - e.g. in word1-word2-word3 format
     */
    public static function createSlug($str = "", $symbol = "-")
    {
        // if not english
        $regex = '/^[ -~]+$/';
        if (!preg_match($regex, $str)) {
            $str = transliterator_transliterate('Any-Latin;Latin-ASCII;', $str);
        }

        $str = mb_strtolower($str);
        $str = str_replace("'", "", $str);
        $str = str_replace('"', "", $str);
        $str = str_replace(".", $symbol, $str);
        $str = str_replace("\\", $symbol, $str);
        $str = str_replace("/", $symbol, $str);
        $str = preg_replace("/[~\:;\,\?\s\(\)\'\"\[\]\{\}#@&%\$\!\^\+\*=\!\<\>\|?`]/", $symbol, trim($str));

        // everything but letters and numbers
        $str = preg_replace('/(.)\\1{2,}/', '$1', $str);

        // letters replace only with 2+ repetition
        $str = preg_replace("/[-]{2,}/", $symbol, $str);
        $str = rtrim($str, $symbol);

        return mb_strtolower($str);
    }

    /**
     * @param $val
     * @return string
     */
    public static function _humanize($val)
    {
        $val = str_replace("_", "", $val);
        $matches = preg_split('/(?=[A-Z])/', $val);
        return trim(implode(" ", $matches));
    }

    /**
     * returns the short string based on $length if string's length is more than $length
     *
     * @param string $str
     * @param number $length
     * @param bool $raw
     * @return string
     */
    public static function shorten($str = '', $length = null, $raw = false)
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
