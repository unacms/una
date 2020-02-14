<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

// TODO: check if it is used or maybe usefull for future use, also it needs some refactoring

class BxDolCacheUtilities extends BxDol
{
    protected $_aCacheTypes = array();

    public function __construct ()
    {
        parent::__construct();

        $this->_aCacheTypes = array(
            'db' => array('option' => 'sys_db_cache_enable'),
            'template' => array('option' => 'sys_template_cache_enable'),
            'less' => array(),
            'css' => array('option' => 'sys_template_cache_css_enable'),
            'js' => array('option' => 'sys_template_cache_js_enable'),
            'custom' => array(),
        );
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

    function isEnabled($sCache)
    {
        if(!isset($this->_aCacheTypes[$sCache]))
            return false;

        return !isset($this->_aCacheTypes[$sCache]['option']) || getParam($this->_aCacheTypes[$sCache]['option']) == 'on';
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
        $mixedResult = false;
        if(!$this->isEnabled($sCache))
            return $mixedResult;

        $bClear = $sMode == 'clear';

        $sFuncCacheObject = $bClear ? '_clearObject' : '_getSizeObject';
        $sFuncCacheFile = $bClear ? '_clearFile' : '_getSizeFile';

        $oTemplate = BxDolStudioTemplate::getInstance();
        switch ($sCache) {
            case 'db':
                $oCacheDb = BxDolDb::getInstance()->getDbCacheObject();
                $mixedResult = $this->$sFuncCacheObject($oCacheDb, 'db_');
                break;

            case 'template':
                $oCacheTemplates = $oTemplate->getTemplatesCacheObject();
                $mixedResult = $this->$sFuncCacheObject($oCacheTemplates, $oTemplate->getCacheFilePrefix($sCache));
                break;

            case 'less':
                $mixedResult = $this->$sFuncCacheFile($oTemplate->getCacheFilePrefix($sCache), BX_DIRECTORY_PATH_CACHE_PUBLIC);
                break;

            case 'css':
                if($bClear)
                    $this->clear('less');

                $mixedResult = $this->$sFuncCacheFile($oTemplate->getCacheFilePrefix($sCache), BX_DIRECTORY_PATH_CACHE_PUBLIC);
                break;

            case 'js':
                $mixedResult = $this->$sFuncCacheFile($oTemplate->getCacheFilePrefix($sCache), BX_DIRECTORY_PATH_CACHE_PUBLIC);
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
            if (0 === strncmp($sFile, $sPrefix, $l))
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
            if (0 === strncmp($sFile, $sPrefix, $l))
                $iSize += filesize($sPath . $sFile);

        closedir($rHandler);

        return $iSize;
    }
}

/** @} */
