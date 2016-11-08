<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Sites Sites
 * @ingroup     UnaModules
 *
 * @{
 */

class BxSitesLog extends BxDol
{
    protected $sPath;
    protected $sLogDateFormat;

    function __construct($sPath)
    {
        parent::__construct();
        $this->sLogDateFormat = 'm.d.y H:i:s';
        $this->sPath = $sPath;
    }

    function log($mixedValue)
    {
        $rHandle = fopen($this->sPath, 'a');
        if(!$rHandle)
            return;

        if(is_array($mixedValue) || is_object($mixedValue)) {
            ob_start();
            print_r($mixedValue);
            $sValue = ob_get_contents();
            ob_end_clean();
            fwrite($rHandle, "$sValue\n");
        } else {
            $mixedValue = str_replace('{date}', date($this->sLogDateFormat), $mixedValue);

            fwrite($rHandle, "$mixedValue\n");
        }

        fclose($rHandle);
    }
}

/** @} */
