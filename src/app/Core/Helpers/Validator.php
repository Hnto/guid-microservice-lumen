<?php
namespace App\Core\Helpers;

class Validator
{
    /**
     * Validate variable against callback
     *
     * @param $var
     * @param $callback
     * @param bool $default
     * @return bool|mixed - given var or default
     */
    public static function callback($var, $callback, $default = false)
    {
        if (!is_callable($callback)) {
            return $default;
        }

        if ($callback($var) !== true) {
            return $default;
        }

        return $var;
    }

    /**
     * Validate if give variable is not empty and return default if empty
     *
     * @param $var
     * @param bool $default
     */
    public static function available($var, $default = null) {
        if (empty($var)) {
            return $default;
        }

        return $var;
    }
}
