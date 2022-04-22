<?php

namespace ChargeBee;

use ChargeBee\ChargeBee\Environment;

class ChargeBee extends AbstractChargeBee
{
    public function __construct($site, $apiKey)
    {
        $this->checkExtentions();

        Environment::configure($site, $apiKey);
    }

    public function getEnvironment() {
        return Environment::defaultEnv();
    }

    public function checkExtentions() {
        foreach (self::extensions AS $e) {
            if (!extension_loaded($e)) {
                throw new \Exception('ChargeBee requires the ' . $e . ' extension.');
            }
        }
    }
}
