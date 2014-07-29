<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

// TODO: check if it is used or maybe usefull for future use, also it needs some refactoring

class BxDolCacheUtilities extends BxDol
{
    protected $oCacheDb;
    protected $oCacheTemplates;

    function __construct ()
    {
        parent::__construct();

        // DB
        $this->oCacheDb = BxDolDb::getInstance()->getDbCacheObject();

        // templates
        $this->oCacheTemplates = BxDolTemplate::getInstance()->getTemplatesCacheObject();
    }

	/**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolCacheUtilities();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    function clear($sCache)
    {
        return $this->_action($sCache, 'clear');
    }

    function size($sCache, $isFormatted = false)
    {
        $iSize = $this->_action($sCache, 'size');
        return $isFormatted ? sprintf("%.2f", $iSize / 1024 / 1024) : $iSize;
    }

    protected function _action($sCache, $sMode = 'clear')
    {
        $sFuncCacheObject = $sMode == 'clear' ? '_clearCacheObject' : '_getSizeCacheObject';
        $sFuncCacheFile = $sMode == 'clear' ? '_clearCache' : '_getSizeCache';

        switch ($sCache) {
            case 'users':
                $mixedResult = $this->$sFuncCacheFile('user', BX_DIRECTORY_PATH_CACHE);
                break;

            case 'db':
                $mixedResult = $this->$sFuncCacheObject ($this->oCacheDb, 'db_');
                break;

            case 'template':
                $mixedResult = $this->$sFuncCacheObject ($this->oCacheTemplates, BxDolStudioTemplate::getInstance()->getCacheFilePrefix($sCache));
                break;

            case 'css':
                $mixedResult = $this->$sFuncCacheFile(BxDolStudioTemplate::getInstance()->getCacheFilePrefix($sCache), BX_DIRECTORY_PATH_CACHE_PUBLIC);
                break;

            case 'js':
                $mixedResult = $this->$sFuncCacheFile(BxDolStudioTemplate::getInstance()->getCacheFilePrefix($sCache), BX_DIRECTORY_PATH_CACHE_PUBLIC);
                break;
        }

        return $mixedResult;
    }

    function _clearCacheObject($oCache, $sPrefix)
    {
        if (!$oCache->removeAllByPrefix ($sPrefix))
            return array('code' => 1, 'message' => _t('_adm_dbd_err_c_clean_failed'));
        else
            return array('code' => 0, 'message' => _t('_adm_dbd_msg_c_clean_success'));
    }

    function _getSizeCacheObject($oCache, $sPrefix)
    {
        return $oCache->getSizeByPrefix ($sPrefix);
    }

    function _clearCache($sPrefix, $sPath)
    {
        if (!($rHandler = opendir($sPath)))
            return array('code' => 1, 'message' => _t('_adm_dbd_err_c_clean_failed'));

        $l = strlen($sPrefix);
        while (($sFile = readdir($rHandler)) !== false)
            if (0 == strncmp($sFile, $sPrefix, $l))
                @unlink($sPath . $sFile);

        closedir($rHandler);

        return array('code' => 0, 'message' => _t('_adm_dbd_msg_c_clean_success'));
    }

    function _getSizeCache($sPrefix, $sPath)
    {
        if (!($rHandler = opendir($sPath)))
            return 0;

        $iSize = 0;
        $l = strlen($sPrefix);
        while (($sFile = readdir($rHandler)) !== false)
            if (0 == strncmp($sFile, $sPrefix, $l))
                $iSize += filesize($sPath . $sFile);

        closedir($rHandler);

        return $iSize;
    }
}

/** @} */
