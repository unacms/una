<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxDolIO extends BxDol
{
    function __construct()
    {
        parent::__construct();
    }

    public static function isRealOwner()
    {
    	if(defined('BX_DOL_CRON_EXECUTE'))
    		trigger_error('Function can\'t be called under cron', E_USER_ERROR);

		$sName = time() . rand(0, 999999999);
		$sFilePath = BX_DIRECTORY_PATH_TMP . $sName . '.txt';
		if(!$rHandler = fopen($sFilePath, 'w'))
            return false;

		if(!fwrite($rHandler, $sName))
            return false;

		fclose($rHandler);

		$bResult = fileowner(BX_DIRECTORY_PATH_INC . 'utils.inc.php') === fileowner($sFilePath);
		@unlink($sFilePath);

		return $bResult;
    }

    public static function isExecutable($sFile)
    {
        clearstatcache();

        $aPathInfo = pathinfo(__FILE__);
        $sFile = $aPathInfo['dirname'] . '/../../' . $sFile;

        return (is_file($sFile) && is_executable($sFile));
    }

    public static function isWritable($sFile)
    {
        clearstatcache();

        $aPathInfo = pathinfo(__FILE__);
        $sFile = $aPathInfo['dirname'] . '/../../' . $sFile;

        return is_readable($sFile) && is_writable($sFile);
    }

    public static function getPermissions($sFileName)
    {
        $sPath = isset($GLOBALS['logged']['admin']) && $GLOBALS['logged']['admin'] ? BX_DIRECTORY_PATH_ROOT : '../';

        clearstatcache();
        $hPerms = @fileperms($sPath . $sFileName);
        if($hPerms == false) return false;
        $sRet = substr( decoct( $hPerms ), -3 );
        return $sRet;
    }
}

/** @} */
