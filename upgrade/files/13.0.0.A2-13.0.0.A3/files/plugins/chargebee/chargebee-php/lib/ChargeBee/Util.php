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

        return preg_replace_callback('/([A-Z])/', $func, $str);
    }

    public static function serialize($value, $prefix = null, $idx = null)
    {
        if ($value && !is_array($value)) {
            throw new Exception("only arrays are allowed as value");
        }

        $serialized = [];
        foreach ($value as $k => $v) {
            if (is_array($v) && !is_int($k)) {
                $serialized = array_merge($serialized, self::serialize($v, self::toUnderscoreFromCamelCase($k)));
            } elseif (is_array($v) && is_int($k)) {
                $serialized = array_merge($serialized, self::serialize($v, $prefix, $k));
            } else {
                $usK = self::toUnderscoreFromCamelCase($k);
                $key = (!is_null($prefix)?$prefix:'').(!is_null($prefix)?'['.$usK.']':$usK).(!is_null($idx)?'['.$idx.']':'');
                $serialized[$key] = self::asString($v);
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
