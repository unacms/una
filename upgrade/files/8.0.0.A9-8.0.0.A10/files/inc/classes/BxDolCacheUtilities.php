<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

// TODO: check if it is used or maybe usefull for future use, also it needs some refactoring

class BxDolCacheUtilities extends BxDol
{
    function __construct ()
    {
        parent::__construct();
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
        $sFuncCacheObject = $sMode == 'clear' ? '_clearObject' : '_getSizeObject';
        $sFuncCacheFile = $sMode == 'clear' ? '_clearFile' : '_getSizeFile';

        $mixedResult = false;
        switch ($sCache) {
            case 'db':
            	if(getParam('sys_db_cache_enable') != 'on')
					break;

				$oCacheDb = BxDolDb::getInstance()->getDbCacheObject();
                $mixedResult = $this->$sFuncCacheObject($oCacheDb, 'db_');
                break;

            case 'template':
				if(getParam('sys_template_cache_enable') != 'on') 
					break;

				$oCacheTemplates = BxDolTemplate::getInstance()->getTemplatesCacheObject();
                $mixedResult = $this->$sFuncCacheObject($oCacheTemplates, BxDolStudioTemplate::getInstance()->getCacheFilePrefix($sCache));
                break;

            case 'css':
            	if(getParam('sys_template_cache_css_enable') != 'on')
            		break;

                $mixedResult = $this->$sFuncCacheFile(BxDolStudioTemplate::getInstance()->getCacheFilePrefix($sCache), BX_DIRECTORY_PATH_CACHE_PUBLIC);
                break;

            case 'js':
            	if(getParam('sys_template_cache_js_enable') != 'on')
            		break;

                $mixedResult = $this->$sFuncCacheFile(BxDolStudioTemplate::getInstance()->getCacheFilePrefix($sCache), BX_DIRECTORY_PATH_CACHE_PUBLIC);
                break;
        }

        return $mixedResult;
    }

    protected function _clearObject($oCache, $sPrefix)
    {
        if (!$oCache->removeAllByPrefix ($sPrefix))
            return array('code' => 1, 'message' => _t('_adm_dbd_err_c_clean_failed'));
        else
            return array('code' => 0, 'message' => _t('_adm_dbd_msg_c_clean_success'));
    }

    protected function _clearFile($sPrefix, $sPath)
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

    protected function _getSizeObject($oCache, $sPrefix)
    {
        return $oCache->getSizeByPrefix ($sPrefix);
    }

    protected function _getSizeFile($sPrefix, $sPath)
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
