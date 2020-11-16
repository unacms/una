<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolLogsStoragePHPLog extends BxDolLogsStorageFolder implements iBxDolSingleton
{
    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolLogsStoragePHPLog();

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

        return error_log($s, 0);
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
