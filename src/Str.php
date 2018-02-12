<?php
namespace LaraSupport;

use Illuminate\Support\Str as BaseStr;

class Str extends BaseStr
{
    const LAST = 'last';

    /**
     * @param $string
     * @param $search
     * @return array
     */
    public static function positions($string, $search)
    {
        return static::_position($string, $search);
    }

    /**
     * @param $string
     * @param $search
     * @return array
     */
    public static function ipositions($string, $search)
    {
        return static::_position($string, $search, true);
    }


    /**
     * @param $subject
     * @param $search
     * @param int $occurrence
     * @return bool|string
     */
    public static function after($subject, $search, $occurrence = 1)
    {
        if ($search == '') {
            return $subject;
        }

        $pos = strrpos($subject, $search);

        if ($pos === false) {
            return $subject;
        }

        return substr($subject, $pos + strlen($search));
    }

    /**
     * @param $subject
     * @param $search
     * @param string $occurrence
     * @return bool|string
     */
    public static function before($subject, $search, $occurrence = self::LAST)
    {
        if ($search == '') {
            return $subject;
        }

        $positions = static::positions($subject, $search);

        if ($occurrence == self::LAST) {
            $occurrence = getLastKey($positions);
        }

        if (empty($positions[$occurrence])) {
            return $subject;
        }

        return substr($subject, 0, $positions[$occurrence]);
    }

    /**
     * @param $subject
     * @param $searchStart
     * @param $searchEnd
     * @param int $occurentcStart
     * @param string $occurenceEnd
     * @return bool|string
     */
    public static function between($subject, $searchStart, $searchEnd, $occurentcStart = 1, $occurenceEnd = self::LAST)
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

    public static function wrap($string, $search, $left = '', $right = '')
    {
        return str_replace($search , $left . $search . $right , $string);
    }

    public static function iwrap($string, $search, $left = '', $right = '')
    {
        $positions = self::ipositions($string, $search);

        if (empty($positions)) {
            return $string;
        }

        $length = strlen($search);
        $positions = array_reverse($positions);
        foreach ($positions as $position) {
            $actual = substr($string, $position, $length);
            $actual = $left . $actual . $right;
            $string = substr_replace($string, $actual,  $position, $length);
            echo($string);
            echo PHP_EOL;
        }
        return $string;
    }


    /**
     * @param $string
     * @param $search
     * @param bool $caseSensitive
     * @return array
     */
    protected static function _position($string, $search, $caseSensitive = false)
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

}
