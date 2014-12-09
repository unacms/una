<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */
bx_import('BxDolIO');

define('BX_FORCE_AUTOUPDATE_MAX_CHANGED_FILES_PERCENT', 0.05);
define('BX_FORCE_USE_FTP_FILE_TRANSFER', false);

class BxDolInstallerUtils extends BxDolIO
{
    protected $_aNonHashableFiles = array();

    function __construct()
    {
        parent::__construct();
    }

    static public function isAllowUrlInclude()
    {
        if (version_compare(phpversion(), "5.2", ">") == 1) {
            $sAllowUrlInclude = ini_get('allow_url_include');
            return !($sAllowUrlInclude == 0);
        };
        return false;
    }

    static public function getModuleConfig($mixed)
    {
    	$sConfig = '';
    	if(is_array($mixed) && !empty($mixed['path']))
			$sConfig = BX_DIRECTORY_PATH_MODULES . $mixed['path'] . 'install/config.php';
		else if(is_string($mixed))
			$sConfig = $mixed;
		else 
			return array();

    	if(!file_exists($sConfig))
            return array();

        include($sConfig);

        return $aConfig;
    }

    static public function isModuleInstalled($sUri)
    {
        bx_import('BxDolModuleQuery');
        return BxDolModuleQuery::getInstance()->isModule($sUri);
    }

    /**
     * Set module for delayed uninstall
     */
    static public function setModulePendingUninstall($sUri, $bPendingUninstall = true)
    {
        bx_import('BxDolModuleQuery');
        return BxDolModuleQuery::getInstance()->setModulePendingUninstall($sUri, $bPendingUninstall);
    }

    /**
     * Check if module is pending for uninstall
     */
    static public function isModulePendingUninstall($sUri)
    {
        bx_import('BxDolModuleQuery');
        $a = BxDolModuleQuery::getInstance()->getModuleByUri($sUri);
        return $a['pending_uninstall'];
    }

    /**
     * Check all pending for uninstallation modules and uninstall them if no pending for deletion files are found
     */
    static public function checkModulesPendingUninstall()
    {
        bx_import('BxDolModuleQuery');
        $a = BxDolModuleQuery::getInstance()->getModules();
        foreach ($a as $aModule) {

            // after we make sure that all pending for deletion files are deleted
            if (!$aModule['pending_uninstall'] || BxDolStorage::isQueuedFilesForDeletion($aModule['name']))
                continue;

            // remove pending uninstall flag
            self::setModulePendingUninstall($aModule['uri'], false);

            // perform uninstallation
            bx_import('BxDolStudioInstallerUtils');
            $aResult = BxDolStudioInstallerUtils::getInstance()->perform($aModule['path'], 'uninstall');

            // send email nofitication
            $aTemplateKeys = array(
                'Module' => $aModule['title'],
                'Result' => _t('_Success'),
                'Message' => '',
            );

            if ($aResult['code'] > 0) {
                $aTemplateKeys['Result'] = _t('_Failed');
                $aTemplateKeys['Message'] = $aResult['message'];
            }

            bx_import('BxDolEmailTemplates');
            $aMessage = BxDolEmailTemplates::getInstance()->parseTemplate('t_DelayedModuleUninstall', $aTemplateKeys);
            sendMail (getParam('site_email'), $aMessage['Subject'], $aMessage['Body'], 0, array(), BX_EMAIL_SYSTEM);
        }
    }

    /**
     * Generate hash for module files.
     * @param $sPath module's root folder
     * @param $aFiles array to fill with files hashes
     */
    public function hashFiles($sPath, &$aFiles)
    {
        $aExcludes = array('.', '..', 'error_log', 'php.ini', '.DS_Store', 'Thumbs.db');
        if (file_exists($sPath) && is_dir($sPath) && ($rSource = opendir($sPath))) {
            while (($sFile = readdir($rSource)) !== false) {
                if ('.' == $sFile[0] || in_array($sFile, $aExcludes))
                    continue;
                
                if (in_array($this->filePathWithoutBase($sPath . $sFile), $this->_aNonHashableFiles))
                    continue;                

                if (is_dir($sPath . $sFile))
                    $this->hashFiles($sPath . $sFile . '/', $aFiles);
                else
                    $aFiles[] = $this->hashInfo($sPath . $sFile);
            }
            closedir($rSource);
        } elseif (file_exists($sPath) && is_file($sPath)) {
            $aFiles[] = $this->hashInfo($sPath);
        }
    }

    /** 
     * Check module's files hashes. For system files use @see BxDolInstallerHasher class.
     * @param $aFiles current files checksums 
     * @param $iModuleId module id
     * @return empty array on success, or array of files which checksum was changed 
     */
    public function hashCheck($aFiles, $iModuleId)
    {
        $oDb = bx_instance('BxDolStudioInstallerQuery');

        $aFilesOrig = $oDb->getModuleTrackFiles($iModuleId);
        $aFailesChanged = array();
        foreach ($aFiles as $aFile)
            if(!isset($aFilesOrig[$aFile['file']]) || $aFilesOrig[$aFile['file']]['hash'] != $aFile['hash'])
                $aFailesChanged[] = $aFile['file'];

		$fChangedPercent = 0;
        if(count($aFilesOrig) != 0)
            $fChangedPercent = count($aFailesChanged) / count($aFilesOrig);

        return array($aFailesChanged, $fChangedPercent);
    }
    
    protected function hashInfo($sPath)
    {
        return array(
            'file' => $this->filePathWithoutBase($sPath),
            'hash' => md5_file($sPath)
        );
    }

    protected function filePathWithoutBase($sPath)
    {
        return bx_ltrim_str($sPath, BX_DIRECTORY_PATH_ROOT);
    }
}

/** @} */
