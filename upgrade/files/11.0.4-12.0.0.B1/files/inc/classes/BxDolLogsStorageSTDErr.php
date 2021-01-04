<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolLogsStorageSTDErr extends BxDolLogsStorageFolder implements iBxDolSingleton
{
    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolLogsStorageSTDErr();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * Write to log 
     * @param $oObject logs object 
     * @param $mixed string or array to log
     * @return true on success or false on error
     */
    public function add($oObject, $mixed)
    {
        if (!$mixed)
            return true;

        $s = $this->formatLogString($oObject, $mixed, true);

        if (!($fd = fopen('php://stderr', 'w')))
            return false;
        if (false === fwrite($fd, $s))
            return false;
        fclose($fd);

        return true;
    }

    /**
     * Not supported
     */
    public function get($oObject, $iLines, $sFilter = false)
    {
        return false;
    }

    /**
     * Check if filtering supported in `get` method
     */
    public function isFilterAvail()
    {
        return false;
    }

    /**
     * Check if `get` method available
     */
    public function isGetAvail()
    {
        return false;
    }
}

/** @} */
