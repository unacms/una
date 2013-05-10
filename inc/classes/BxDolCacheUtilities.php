<?php

/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxDolCacheUtilities extends BxDolMistake
{
    protected $oMemberMenu;
    protected $oCacheMemberMenu;
    protected $oCachePb;
    protected $oCacheDb;
    protected $oCacheTemplates;

    function __construct ()
    {
        parent::BxDolMistake();

        // member menu
        bx_import('BxDolMemberMenu');
        $this->oMemberMenu = new BxDolMemberMenu();
        $this->oCacheMemberMenu = $this->oMemberMenu->getCacheObject();

        // page blocks
        bx_import('BxDolPageViewAdmin');
        $oPageViewCacher = new BxDolPageViewCacher ('', '');
        $this->oCachePb = $oPageViewCacher->getBlocksCacheObject ();

        // DB
        $this->oCacheDb = $GLOBALS['MySQL']->getDbCacheObject();

        // templates
        $this->oCacheTemplates = $GLOBALS['oSysTemplate']->getTemplatesCacheObject();
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

    function _action($sCache, $sMode = 'clear')
    {
        $sFuncCacheObject = ('clear' == $sMode ? '_clearCacheObject' : '_getSizeCacheObject');
        $sFuncCacheFile = ('clear' == $sMode ? '_clearCache' : '_getSizeCache');

        switch ($sCache) {

            case 'member_menu':
                $mixedResult = $this->$sFuncCacheObject ($this->oCacheMemberMenu, $this->oMemberMenu->sMenuMemberKeysCache);
                break;

            case 'pb':
                $mixedResult = $this->$sFuncCacheObject ($this->oCacheDb, 'pb_');
                break;

            case 'users':
                $mixedResult = $this->$sFuncCacheFile('user', BX_DIRECTORY_PATH_CACHE);
                break;

            case 'db':
                $mixedResult = $this->$sFuncCacheObject ($this->oCacheDb, 'db_');
                break;

            case 'template':
                $mixedResult = $this->$sFuncCacheObject ($this->oCacheTemplates, $GLOBALS['oSysTemplate']->_sCacheFilePrefix);
                break;

            case 'css':
                $mixedResult = $this->$sFuncCacheFile($GLOBALS['oSysTemplate']->_sCssCachePrefix, BX_DIRECTORY_PATH_CACHE_PUBLIC);
                break;

            case 'js':
                $mixedResult = $this->$sFuncCacheFile($GLOBALS['oSysTemplate']->_sJsCachePrefix, BX_DIRECTORY_PATH_CACHE_PUBLIC);
                break;
        }

        return $mixedResult;
    }

    function _clearCacheObject($oCache, $sPrefix)
    {
        if ('db_' == $sPrefix)
            $GLOBALS['MySQL']->oParams->clearCache();
        elseif ($this->oMemberMenu->sMenuMemberKeysCache == $sPrefix)
            $this->oMemberMenu->deleteMemberMenuCaches();

        if (!$oCache->removeAllByPrefix ($sPrefix))
            return array('code' => 1, 'message' => _t('_adm_txt_dashboard_cache_clean_failed'));
        else
            return array('code' => 0, 'message' => _t('_adm_txt_dashboard_cache_clean_success'));
    }
    function _getSizeCacheObject($oCache, $sPrefix)
    {
        return $oCache->getSizeByPrefix ($sPrefix);
    }

    function _clearCache($sPrefix, $sPath)
    {
        if (!($rHandler = opendir($sPath)))
            return array('code' => 1, 'message' => _t('_adm_txt_dashboard_cache_clean_failed'));

        $l = strlen($sPrefix);
        while (($sFile = readdir($rHandler)) !== false)
            if (0 == strncmp($sFile, $sPrefix, $l))
                @unlink($sPath . $sFile);

        closedir($rHandler);

        return array('code' => 0, 'message' => _t('_adm_txt_dashboard_cache_clean_success'));
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
