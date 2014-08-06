<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

class BxDolUpgrader extends BxDol
{
    protected $_sUrlVersionCheck = 'http://rss.boonex.com/u/';
    protected $_sError = false;

    public function prepare()
    {
        $this->setError(false);
        $iUmaskSave = umask(0);

        while(true) {

            $aVersionUpdateInfo = $this->getVersionUpdateInfo();
            if (null === $aVersionUpdateInfo) {
                $this->setError(_t('_sys_upgrade_get_version_info_failed'));
                break;
            }

            if (!$this->isUpgradeAvailable($aVersionUpdateInfo))
                break;

            // TODO: check db version and files version (files version file need to be added)

            // TODO: add setting opton to force update even if some of the files were modified (3%-5%)
            $aFailedFiles = $this->checkFilesChecksums ();
            if (!empty($aFailedFiles)) {
                $this->setError(_t('_sys_upgrade_files_checksum_failed', implode(',', $aFailedFiles)));
                break;
            }

            if (!($sPatchPath = $this->downloadPatch ($aVersionUpdateInfo))) {
                $this->setError(_t('_sys_upgrade_patch_download_failed'));
                break;
            }

            if (!$this->isPatchChecksumCorrect ($sPatchPath, $aVersionUpdateInfo)) {
                $this->deleteUpgradePatch($sPatchPath);
                $this->setError(_t('_sys_upgrade_patch_checksum_failed'));
                break;
            }

            if (!($sUnpackedPath = $this->unpackPatch ($sPatchPath, true))) {
                $this->deleteUpgradePatch($sPatchPath);
                $this->setError(_t('_sys_upgrade_patch_unpack_failed'));
                break;
            }            

            $this->deleteUpgradePatch($sPatchPath);

            if (!$this->isValidPatch ($sUnpackedPath, $aVersionUpdateInfo)) {
                $this->deleteUpgradeFolder($sPatchPath);
                $this->setError(_t('_sys_upgrade_patch_invalid'));
                break;
            }

            break;
        }

        umask($iUmaskSave);

        // TODO: check error and set transient cron for the real update of files and db!

        echo 'Err:' . $this->getError() . "\n";
    }

    public function getVersionUpdateInfo ()
    {
        $s = bx_file_get_contents($this->_sUrlVersionCheck, array ('v' => bx_get_ver()));
        if (!$s)
            return null;

        $a = json_decode($s, true);
        if (!isset($a['latest_version']))
            return null;

        return $a;
    }

    public function isUpgradeAvailable ($a)
    {
        if (1 == version_compare(strtolower($a['latest_version']), strtolower(bx_get_ver())) && isset($a['patch']))
            return true;
        return false;
    }

    protected function downloadPatch ($a)
    {
        if (!isset($a['patch']['url']))
            return false;

        if (!($f = fopen($a['patch']['url'], "rb")))
            return false;

        $sTmpFile = BX_DIRECTORY_PATH_TMP . 'patch_' . bx_get_ver() . '_' . $a['patch']['ver'] . '.zip';
        if (file_exists($sTmpFile) && !unlink($sTmpFile))
            return false;        

        $sRet = false;
        if (false !== $sTmpFile && false !== file_put_contents($sTmpFile, $f))
            $sRet = $sTmpFile;

        fclose($f);

        return $sRet;
    }

    protected function isPatchChecksumCorrect ($sPatchPath, $aVersionUpdateInfo)
    {
        return md5_file($sPatchPath) == $aVersionUpdateInfo['patch']['md5'];
    }

    protected function unpackPatch ($sPatchPath)
    {
        $sTmpFolder = $this->getTmpFolderFromZip($sPatchPath);
        if (file_exists($sTmpFolder) && !bx_rrmdir($sTmpFolder))
            return false;
        
        $oZip = new ZipArchive();
        if ($oZip->open($sPatchPath) !== true)
            return false;

        $sRootFolder = $oZip->numFiles > 0 ? $oZip->getNameIndex(0) : false;
        if (!$sRootFolder || !mkdir($sTmpFolder) || !$oZip->extractTo($sTmpFolder))
            $sRootFolder = false;

        $oZip->close();

        return $sRootFolder ? $sTmpFolder . '/' . trim($sRootFolder, '/') . '/' : false;
    }

    protected function checkFilesChecksums ()
    {
        $oHasher = bx_instance('BxDolInstallerHasher');
        return $oHasher->checkSystemFilesHash();
    }

    protected function isValidPatch ($sUnpackedPath, $aVersionUpdateInfo)
    {
        $sCheckFilePath =  $sUnpackedPath . 'upgrade/files/' . $this->normalizeVersion(bx_get_ver()) . '-' . $this->normalizeVersion($aVersionUpdateInfo['patch']['ver']) . '/check.php';
        return file_exists($sCheckFilePath);
    }

    public function getError()
    {
        return $this->_sError;
    }

    protected function setError($s)
    {
        $this->_sError = $s;
    }
    
    protected function normalizeVersion($s)
    {
        return str_replace(array('-', '_', ' '), '.', $s);
    }

    protected function getTmpFolderFromZip($sPatchPath)
    {
        return preg_replace('/\.zip$/', '', $sPatchPath);
    }

    protected function deleteUpgradeFolder($sPatchPath)
    {
        @bx_rrmdir($this->getTmpFolderFromZip($sPatchPath));
    }

    protected function deleteUpgradePatch($sPatchPath)
    {
        @unlink($sPatchPath);
    }
}

/** @} */
