<?php
namespace App\Core\Helpers;

class Object
{
    /**
     * Get obj attribute/execute method, if empty or non-existence: null
     *
     * @param $item
     * @param $obj
     * @param $checkOnly
     * @return mixed
     */
    public static function get($item, $obj, $checkOnly = false)
    {
        if (empty($item) || empty($obj)) {
            return null;
        }

        $reflection = new \ReflectionClass($obj);

        if ($reflection->hasProperty($item) || isset($obj->{$item})) {
            if ($checkOnly !== false) {
                return true;
            }

            return $obj->{$item};
        }

        if (($reflection->hasMethod($item) || method_exists($obj, $item)) &&
            !empty($obj->{$item}())
            ) {
                if ($checkOnly !== false) {
                    return true;
                }

                return $obj->{$item}();
        }

        return null;
    }
}
