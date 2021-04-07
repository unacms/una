<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry menu
 */
class BxFilesMenuView extends BxBaseModTextMenuView
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_files';
        parent::__construct($aObject, $oTemplate);
    }

    public function setContentId($iContentId)
    {
        parent::setContentId($iContentId);

        $CNF = $this->_oModule->_oConfig->CNF;

        $aFile = $this->_oModule->getContentFile($this->_aContentInfo);
        $sFileExt = $aFile && !empty($aFile['ext']) ? $aFile['ext'] : '';

        if (!$aFile || !$aFile['private']) {
            $this->addMarkers(array(
                'file_download_token' => '0',
                'file_ext' => $sFileExt,
            ));
            return;
        }

        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        if (!$oStorage) {
            $this->addMarkers(array(
                'file_download_token' => '0',
                'file_ext' => $sFileExt,
            ));
            return;
        }

        $this->addMarkers(array(
            'file_download_token' => $oStorage->genToken($aFile['id']),
            'file_ext' => $sFileExt,
        ));
    }

}

/** @} */
