<?php

namespace ChargeBee\ChargeBee;

use Exception;

class Util
{
    public static function toCamelCaseFromUnderscore($str)
    {
        $func = function ($c) {
            return strtoupper($c[1]);
        };

        return preg_replace_callback('/_([a-z])/', $func, $str);
    }

    public static function toUnderscoreFromCamelCase($str)
    {
        $func = function ($c) {
            return "_" . strtolower($c[1]);
        };

        if (preg_match('/_/', $str)) {
            return $str;
        }

        return preg_replace_callback('/([A-Z])/', $func, $str);
    }

    public static function serialize($value, $prefix = null, $idx = null, $jsonKeys = null, $level = 0)
    {
        if ($value && !is_array($value)) {
            throw new Exception("only arrays are allowed as value");
        }

        $serialized = [];
        foreach ($value as $k => $v) {
            $keysInCamelCase = self::toCamelCaseFromUnderscore($k);
            $shouldJsonEncode = (isset($jsonKeys[$k]) || isset($jsonKeys[$keysInCamelCase])) && $jsonKeys[$keysInCamelCase] === $level;
            if ($shouldJsonEncode) {
                $usK = self::toUnderscoreFromCamelCase($k);
                $key = (!is_null($prefix) ? $prefix : '') .
                    (!is_null($prefix) ? '[' . $usK . ']' : $usK) .
                    (!is_null($idx) ? '[' . $idx . ']' : '');
                $serialized[$key] = is_string($v)?$v:json_encode($v);
            } else if (is_array($v) && !is_int($k)) {
                $tempPrefix = (!is_null($prefix)) ? $prefix . '[' . self::toUnderscoreFromCamelCase($k) . ']' : self::toUnderscoreFromCamelCase($k);
                $serialized = array_merge($serialized, self::serialize($v, $tempPrefix, null, $jsonKeys, $level + 1));
            } elseif (is_array($v) && is_int($k)) {
                $serialized = array_merge($serialized, self::serialize($v, $prefix, $k, $jsonKeys, $level));
            } else {
                $usK = self::toUnderscoreFromCamelCase($k);  
                $key = (!is_null($prefix) ? $prefix : '') . (!is_null($prefix) ? '[' . $usK . ']' : $usK) . (!is_null($idx) ? '[' . $idx . ']' : '');
                $serialized[$key] = self::asString($v);
            }
        }
        return $serialized;
    }

    public static function formatJsonKeysAsSnakeCase($value, $maxDepth = 1000, $currentDepth = 0){
        if ($value && !is_array($value)) {
            throw new Exception("only arrays are allowed as value");
        }

        if ($currentDepth > $maxDepth) {
            throw new Exception("Maximum recursion depth exceeded");
        }

        $serialized = [];

        foreach ($value as $k => $v) {
            $underscoreKey = self::toUnderscoreFromCamelCase($k);

            if(is_array($v)){
                $serialized[$underscoreKey] = self::formatJsonKeysAsSnakeCase($v, $maxDepth, $currentDepth+1);
            }else{
                $serialized[$underscoreKey] = self::asString($v);
            }
        }
        return $serialized;
    }
    public static function asString($value)
    {
        if (is_null($value)) {
            return '';
        } elseif (is_bool($value)) {
            return ($value) ? 'true' : 'false';
        } else {
            return (string)$value;
        }
    }

    public static function encodeURIPath()
    {
        $uriPaths = "";

        foreach (func_get_args() as $arg) {
            $arg = trim($arg);

            if ($arg == null || strlen($arg) < 1) {
                throw new Exception("Id cannot be null or empty");
            }

            $uriPaths .= "/" . implode('/', array_map('rawurlencode', explode('/', $arg)));
        }

        return $uriPaths;
    }
}
