<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

define ('BX_DOL_STORAGE_DIR_RIGHTS', BX_DOL_DIR_RIGHTS);
define ('BX_DOL_STORAGE_FILE_RIGHTS', BX_DOL_FILE_RIGHTS);

/**
 * File storage in local folder.
 * @see BxDolStorage
 */
class BxDolStorageLocal extends BxDolStorage
{
    /**
     * constructor
     */
    public function __construct($aObject)
    {
        parent::__construct($aObject);
    }

    /**
     * Get file url.
     * @param $iFileId file
     * @return file url or false if file was not found
     */
    public function getFileUrlById($iFileId)
    {
        $aFile = $this->_oDb->getFileById($iFileId);
        if (!$aFile)
            return false;

        $sUrl =  $this->getObjectBaseUrl($aFile['private']) . $aFile['remote_id'] . '.' . $aFile['ext'];
        if ($aFile['private']) {
            $sToken = $this->_oDb->genToken($iFileId);
            $sUrl = bx_append_url_params($sUrl, array ('t' => $sToken));
        }
        return $sUrl;
    }

    /**
     * Start file fownloading by remote id. If file is private then token is checked.
     */
    public function download ($sRemoteId, $sToken = false)
    {
        $this->setErrorCode(BX_DOL_STORAGE_ERR_OK);

        $aFile = $this->_oDb->getFileByRemoteId($sRemoteId);
        if (!$aFile) {
            $this->setErrorCode(BX_DOL_STORAGE_ERR_FILE_NOT_FOUND);
            return false;
        }

        if ($aFile['private'] && !$this->_oDb->isTokenValid($aFile['id'], $sToken)) {
            $this->setErrorCode(BX_DOL_STORAGE_ERR_PERMISSION_DENIED);
            return false;
        }
        $sFileLocation = $this->getObjectBaseDir($aFile['private']) . $aFile['path'];
        if (!file_exists($sFileLocation)) {
            $this->setErrorCode(BX_DOL_STORAGE_ERR_FILE_NOT_FOUND);
            return false;
        }

        if (!bx_smart_readfile($sFileLocation, $aFile['file_name'], $aFile['mime_type'], $aFile['private'] && $this->_iCacheControl > $this->_aObject['token_life'] ? $this->_aObject['token_life'] : $this->_iCacheControl, $aFile['private'] ? 'private' : 'public')) {
            $this->setErrorCode(BX_DOL_STORAGE_ERR_ENGINE_GET);
            return false;
        }

        return parent::download($aFile);
    }

    // ----------------

    protected function addFileToEngine($sTmpFile, $sLocalId, $sName, $isPrivate, $iProfileId)
    {
        $sPath = $this->genPath($sLocalId, $this->_aObject['levels']);
        $sNewFileDir = $this->getObjectBaseDir($isPrivate) . $sPath;
        $sNewFilePath = $sNewFileDir . $sLocalId;

        $this->mkdir($sNewFileDir);
        if (!file_exists($sNewFileDir) || !is_writable($sNewFileDir)) {
            $this->setErrorCode(BX_DOL_STORAGE_ERR_FILESYSTEM_PERM);
            return false;
        }

        if (!copy($sTmpFile, $sNewFilePath)) {
            $this->setErrorCode(BX_DOL_STORAGE_ERR_ENGINE_ADD);
            return false;
        }

        if (!chmod ($sNewFilePath, BX_DOL_STORAGE_FILE_RIGHTS)) {
            unlink($sNewFilePath);
            $this->setErrorCode(BX_DOL_STORAGE_ERR_ENGINE_ADD);
            return false;
        }

        return true;
    }

    protected function deleteFileFromEngine($sFilePath, $isPrivate)
    {
        $sFileLocation = $this->getObjectBaseDir($isPrivate) . $sFilePath;

        if (!file_exists($sFileLocation))
            return true;

        if (!unlink($sFileLocation)) {
            $this->setErrorCode(BX_DOL_STORAGE_ERR_UNLINK);
            return false;
        }

        return true;
    }

    protected function getObjectBaseDir ($isPrivate = false)
    {
        return BX_DIRECTORY_STORAGE . $this->_aObject['object'] . '/';
    }

    protected function getObjectBaseUrl ($isPrivate = false)
    {
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('storage.php?o=' . $this->_aObject['object'] . '&f=');
    }

    protected function mkdir($sDirName, $sRights = false)
    {
        if (false == $sRights)
            $sRights = BX_DOL_STORAGE_DIR_RIGHTS;

        $aDirs = explode('/', $sDirName);
        $sDir='';
        foreach ($aDirs as $sPart) {
            $sDir .= $sPart.'/';
            if (!is_dir($sDir) && strlen($sDir) > 0 && !file_exists($sDir)) {
                mkdir($sDir);
                chmod($sDir, $sRights);
            }
        }
    }
}

/** @} */
