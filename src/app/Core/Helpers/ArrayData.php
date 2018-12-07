<?php
namespace App\Core\Helpers;


class ArrayData
{

    /**
     * Randomize and slice an array
     *
     * @param array $data
     * @param int $min
     * @param int $max
     *
     * @return array
     */
    public static function randomSlice(array $data = [], $min = 0, $max = 0)
    {
        shuffle($data);

        $data = array_slice($data, $min, $max);

        return $data;
    }

    /**
     * Create data array
     *
     * @param mixed $data
     * @param string $key
     * @return array
     */
    public static function data(array $data = [], $key = 'data')
    {
        return [
            $key => $data
        ];
    }

    /**
     * @param array $data
     * @param $key
     * @return array
     */
    public static function extractFrom(array $data = [], $searchBy = 'data')
    {
        $return = [];

        $key = 0;
        foreach($data as $value) {
            if (!array_key_exists($searchBy, $value)) {
                continue;
            }

            $return[$key] = $value[$searchBy];

            unset($value[$searchBy]);

            $return[$key] = array_merge(
                $return[$key],
                $value
            );

            $key++;
        }

        return $return;
    }

    /**
     * Transform an array to sub arrays by key
     *
     * @param array $data
     * @param string $key
     * @param string $searchBy
     * @return array
     */
    public static function transformToSubByKey(array $data = [], $key = 'data', $searchBy = 'data')
    {
        $transformed = [];

        if (empty($searchBy)) {
            return [];
        }

        if (!empty($searchBy) && !array_key_exists($searchBy, $data)) {
            return [];
        }

        if (!is_array($data[$searchBy])) {
            return [];
        }

        foreach($data[$searchBy] as $itemKey => $itemValue) {

            $transformed[$itemKey]['type'] = $key;
            $transformed[$itemKey]['data'] = $itemValue;
        }

        return $transformed;
    }
    
    /**
     * Get value from array by key or return default if non-existent
     *
     * @param array $data
     * @param string $key
     * @param mixed $default
     * @return mixed
     */ 
    public static function get(array $data = [], $key, $default = null)
    {
        if (array_key_exists($data, $key)) {
            return $data[$key];
        }

        return $default;
    }

}
