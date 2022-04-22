<?php

namespace ChargeBee;

abstract class AbstractChargeBee
{
    const extensions = ['json'];

    public static $verifyCaCerts = true;

    public function subscription() {
        return null;
    }

    public static function getVerifyCaCerts()
    {
        return self::$verifyCaCerts;
    }

    public static function setVerifyCaCerts($verify)
    {
        self::$verifyCaCerts = $verify;
    }

    public static function getCaCertPath()
    {
        return dirname(__FILE__) . '/ssl/ca-certs.crt';
    }
}
