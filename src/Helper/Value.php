<?php

namespace Shopgate\ConnectSdk\Helper;

class Value
{
    private static $workarounds = ['empty' => 'checkEmpty', 'isset' => 'checkIsset'];

    /**
     * Make the elvis operator (?:) useful on array or object properties.
     *
     * Doing this produces an undefined index notice if 'method' is not set at all in $options:
     * $method = $options['method'] ?: 'get';
     *
     * Instead, you'd have to do this, which is rather clumsy and redundant:
     * $method = !empty($options['method']) ? $options['method'] : 'get';
     *
     * This is easier to understand:
     * $method = Value::elvis($options['method'], 'get');
     *
     * @param mixed $value
     * @param mixed $alt
     * @param callable $checkFunction
     * @param bool $negate
     * @return mixed
     */
    public static function elvis($value, $alt, $checkFunction = 'empty', $negate = true)
    {
        if (in_array($checkFunction, array_keys(self::$workarounds))) {
            $checkFunction = [self::class, self::$workarounds[$checkFunction]];
        }

        $checkResult = $checkFunction($value);
        if ($negate) $checkResult = !$checkResult;

        return $checkResult ? $value : $alt;
    }

    /**
     * Adds a value into every sub-array or object of a list.
     *
     * Example:
     * spreadIntoArray(
     *   [
     *     'a' => [],
     *     'b' => ['key' => 'value']
     *   ],
     *   'newValue',
     *   'newKey'
     * );
     *
     * Result:
     * [
     *   'a' => ['newKey' => 'newValue'],
     *   'b' => ['key' => 'value', 'newKey' => 'newValue']
     * ]
     *
     * @param array[]|\stdClass[] $array
     * @param string $value
     * @param string $key
     * @return array[]|\stdClass[]
     */
    public static function addValue($array, $value, $key)
    {
        return array_map(function ($originalValue) use ($value, $key) {
            if (is_array($originalValue)) $originalValue[$key] = $value;
            if (is_object($originalValue)) $originalValue->{$key} = $value;

            return $originalValue;
        }, $array);
    }

    /**
     * Converts boolean values of an array into "true" or "false" respectively.
     *
     * The conversion preserves keys and only handles top-level values.
     *
     * @param array $array
     * @return array
     */
    public static function arrayBool2String($array)
    {
        foreach ($array as &$value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
        }

        return $array;
    }

    private static function checkEmpty($value)
    {
        return empty($value);
    }

    private static function checkIsset($value)
    {
        return isset($value);
    }
}
