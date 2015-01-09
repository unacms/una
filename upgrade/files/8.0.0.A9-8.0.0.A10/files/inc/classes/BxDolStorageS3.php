<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxDolStorage');

require_once(BX_DIRECTORY_PATH_PLUGINS . 'amazon-s3/S3.php');

/**
 * File storage in Amazon S3.
 * @see BxDolStorage
 */
class BxDolStorageS3 extends BxDolStorage
{
    protected $_s3;
    protected $_sBucket;
    protected $_sDomain;
    protected $_bSSL;
    protected $_bReducedRedundancy;

    /**
     * constructor
     */
    public function __construct($aObject)
    {
        parent::__construct($aObject);

        $sAccessKey = getParam('sys_storage_s3_access_key');
        $sSecretKey = getParam('sys_storage_s3_secret_key');
        $this->_s3 = new S3($sAccessKey, $sSecretKey);
        $this->_sBucket = getParam('sys_storage_s3_bucket');
        $this->_sDomain = getParam('sys_storage_s3_domain');

        $this->_bSSL = isset($this->_aParams['ssl']) && $this->_aParams['ssl'] ? true : false;
        $this->_bReducedRedundancy = isset($this->_aParams['reduced_redundancy']) && $this->_aParams['reduced_redundancy'] ? true : false;
    }

    /**
     * Get file url.
     * @param $iFileId file
     * @return file url
     */
    public function getFileUrlById($iFileId)
    {
        $aFile = $this->_oDb->getFileById($iFileId);
        if (!$aFile)
            return false;

        if ($aFile['private']) {
            $sFileLocation = $this->getObjectBaseDir($aFile['private']) . $aFile['path'];

            if ($this->_sDomain)
                return $this->_s3->getAuthenticatedURL($this->_sDomain, $sFileLocation, $this->_aObject['token_life'], true, $this->_bSSL);
            else
                return $this->_s3->getAuthenticatedURL($this->_sBucket, $sFileLocation, $this->_aObject['token_life'], false, $this->_bSSL);
        }

        return $this->getObjectBaseUrl($aFile['private']) . $aFile['path'];
    }

    /**
     * Set file private or public.
     */
    public function setFilePrivate($iFileId, $isPrivate = true)
    {
        $aFile = $this->_oDb->getFileById($iFileId);
        $sFileLocation = $this->getObjectBaseDir($aFile['private']) . $aFile['path'];
        if (($aACP = $this->_s3->getAccessControlPolicy($this->_sBucket, $sFileLocation)) === false) {
            $this->setErrorCode(BX_DOL_STORAGE_ERR_ENGINE_GET);
            return false;
        }

        if (!is_array($aACP['acl']) || !$aACP['acl']) {
            $this->setErrorCode(BX_DOL_STORAGE_ERR_ENGINE_GET);
            return false;
        }

        // check current permissions
        $aNewACP = $aACP;
        unset($aNewACP['acl']);
        $aNewACP['acl'] = array();
        $aGroupPublic = false;
        $aGroupPrivate = false;
        foreach ($aACP['acl'] as $r) {
            if ('Group' == $r['type']) {
                if (isset($r['uri']) && $r['uri'] == 'http://acs.amazonaws.com/groups/global/AllUsers')
                    $aGroupPublic = $r;
                elseif (isset($r['uri']) && $r['uri'] == 'http://acs.amazonaws.com/groups/global/AuthenticatedUsers')
                    $aGroupPrivate = $r;
                else
                    $aNewACP['acl'][] = $r;
            } else {
                $aNewACP['acl'][] = $r;
            }
        }

        // determine permissions changing

        $aGroupAdd = false;

        if ($isPrivate && (!$aGroupPrivate || $aGroupPublic)) {

            // make private
            $aGroupAdd = array (
                    'type' => 'Group',
                    'uri' => 'http://acs.amazonaws.com/groups/global/AuthenticatedUsers',
                    'permission' => 'READ',
                );

        } elseif (!$isPrivate && ($aGroupPrivate || !$aGroupPublic)) {

            // make public
            $aGroupAdd = array (
                    'type' => 'Group',
                    'uri' => 'http://acs.amazonaws.com/groups/global/AllUsers',
                    'permission' => 'READ',
                );

        }

        // change permission if necessary

        if ($aGroupAdd) {
            $aNewACP['acl'][] = $aGroupAdd;
            if (!$this->_s3->setAccessControlPolicy($this->_sBucket, $sFileLocation, $aNewACP)) {
                $this->setErrorCode(BX_DOL_STORAGE_ERR_ENGINE_GET);
                return false;
            }
        }

        return parent::setFilePrivate($iFileId, $isPrivate);
    }

    // ----------------

    protected function addFileToEngine($sTmpFile, $sLocalId, $sName, $isPrivate, $iProfileId)
    {
        $sMimeType = $this->getMimeTypeByFileName($sName);
        $sExt = $this->getFileExt($sName);
        $sPath = $this->genPath($sLocalId, $this->_aObject['levels']);
        $sRemoteNamePath = $sPath . $sLocalId . ($sExt ? '.' . $sExt : '');

        $aMetaHeaders = array();
        $aRequestHeaders = array (
            "Content-Type"  => $sMimeType,
        );
        if ($this->_iCacheControl > 0) {
            $aRequestHeaders = array_merge ($aRequestHeaders, array (
                "Cache-Control" => "max-age=" . ($isPrivate && $this->_iCacheControl > $this->_aObject['token_life'] ? $this->_aObject['token_life'] : $this->_iCacheControl),
            ));
        }

        $sStorageClass = $this->_bReducedRedundancy ? S3::STORAGE_CLASS_RRS : S3::STORAGE_CLASS_STANDARD;
        $sACL = $isPrivate ? S3::ACL_AUTHENTICATED_READ : S3::ACL_PUBLIC_READ;
        $aInputFile = $this->_s3->inputFile($sTmpFile);
        if (!$this->_s3->putObject($aInputFile, $this->_sBucket, $this->getObjectBaseDir($isPrivate) . $sRemoteNamePath, $sACL, $aMetaHeaders, $aRequestHeaders, $sStorageClass)) {
            $this->setErrorCode(BX_DOL_STORAGE_ERR_ENGINE_ADD);
            return false;
        }

        return true;
    }

    protected function deleteFileFromEngine($sFilePath, $isPrivate)
    {
        $sFileLocation = $this->getObjectBaseDir($isPrivate) . $sFilePath;

        if (!$this->_s3->deleteObject($this->_sBucket, $sFileLocation)) {
            $this->setErrorCode(BX_DOL_STORAGE_ERR_UNLINK);
            return false;
        }

        return true;
    }

    protected function genRemoteNamePath ($sPath, $sLocalId, $sExt)
    {
        return $sPath . $sLocalId . ($sExt ? '.' . $sExt : '');
    }

    protected function getObjectBaseDir ($isPrivate = false)
    {
        return $this->_aObject['object'] . '/';
    }

    protected function getObjectBaseUrl ($isPrivate = false)
    {
        $sProto = $this->_bSSL ? 'https://' : 'http://';
        return $sProto . $this->_sBucket . '.s3.amazonaws.com/' . $this->getObjectBaseDir($isPrivate);
    }
}

/** @} */
