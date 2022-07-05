<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolLogsStorageFolder extends BxDolFactory implements iBxDolSingleton
{
    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolLogsStorageFolder();

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

        $sFile = BX_DIRECTORY_PATH_LOGS . $oObject->getObjectName() . '.log';

        $s = $this->formatLogString($oObject, $mixed);

        $bNewFile = !file_exists($sFile);
        $bRet = file_put_contents($sFile, $s, FILE_APPEND) ? true : false;
        if ($bNewFile && $bRet)
            chmod($sFile, BX_DOL_FILE_RIGHTS);
        return $bRet;
    }

    protected function formatLogString($oObject, $mixed, $bIncludeObjectName = false)
    {
        $s = date('M d H:i:s');

        if ($bIncludeObjectName)
            $s .= ' ' . $oObject->getObjectName();

        $s .= ' [' . (int)bx_get_logged_profile_id() . '] ';

        if (!empty($_SERVER['REQUEST_URI']))
            $s .= $_SERVER['REQUEST_URI'];
        elseif (defined('BX_DOL_CRON_EXECUTE'))
            $s .= 'CRON';
        else
            $s .= 'EMPTY';

        $s .= ' ' . trim(is_array($mixed) ? print_r($mixed, true) : $mixed);

        $s .= "\n";

        return $s;
    }

    /**
     * Get logs
     * @param $oObject logs object
     * @param $iLines number of lines from the tail
     * @param $sFilter filter lines which include this phrase
     * @return array of strings
     */
    public function get($oObject, $iLines, $sFilter = false)
    {
        $sFile = BX_DIRECTORY_PATH_LOGS . $oObject->getObjectName() . '.log';
        $iSize = filesize($sFile);
        if (!($fd = fopen($sFile, 'r+')))
            return false;
        $iPos = $iSize;
        $n = 0;
        while ($n < $iLines+1 && $iPos > 0) {
            if (-1 == fseek($fd, $iPos))
                break;
            if (false === ($s = fread($fd, 1)))
                break;
            if ($s === "\n")
                ++$n;
            $iPos--;
        }
        $a = array();
        for ($i = 0; $i < $iLines; $i++) {
            if (false === ($s = fgets($fd)))
                break;
            if (!$sFilter || false !== stripos($s, $sFilter))
                array_push($a, $s);
        }
        fclose($fd);
        return $a;
    }

    /**
     * Check if filtering supported in `get` method
     */
    public function isFilterAvail()
    {
        return true;
    }

    /**
     * Check if `get` method available
     */
    public function isGetAvail()
    {
        return true;
    }
}

/** @} */
