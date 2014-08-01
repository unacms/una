<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolIO');

class BxDolInstallerUtils extends BxDolIO
{
    protected $_aNonHashableFiles = array();

    function __construct()
    {
        parent::__construct();
    }

    static public function isXsltEnabled()
    {
        if (((int)phpversion()) >= 5) {
            if (class_exists ('DOMDocument') && class_exists ('XsltProcessor'))
                return true;
        } else {
            if (function_exists('domxml_xslt_stylesheet_file'))
                return true;
            elseif (function_exists ('xslt_create'))
                return true;
        }
        return false;
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

    public function hashFiles($sPath, &$aFiles)
    {
        if (file_exists($sPath) && is_dir($sPath) && ($rSource = opendir($sPath))) {
            while (($sFile = readdir($rSource)) !== false) {
                if ($sFile == '.' || $sFile =='..' || $sFile[0] == '.')
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

    protected function hashInfo($sPath)
    {
        return array(
            'file' => $this->filePathWithoutBase($sPath),
            'hash' => md5(file_get_contents($sPath))
        );
    }

    protected function filePathWithoutBase($sPath)
    {
        return bx_ltrim_str($sPath, BX_DIRECTORY_PATH_ROOT);
    }
}

/** @} */
