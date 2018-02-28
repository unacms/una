<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseFile Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModFilesUploaderHTML5 extends BxTemplUploaderHTML5
{
    protected $MODULE;
    protected $_oModule;

    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);
        $this->_oModule = BxDolModule::getInstance($this->MODULE);
    }

    protected function getGhostTemplateVars($aFile, $iProfileId, $iContentId, $oStorage, $oImagesTranscoder)
    {
        $sTitle = $this->_oModule->_oDb->getFileTitle($aFile['id']);
        $a = array(
            'file_title' => $sTitle ? $sTitle : $aFile['file_name']
        );
        $a['file_title_attr'] = bx_html_attribute($a['file_title']);
        return $a;
    }

    protected function isUseTranscoderForPreview($oImagesTranscoder, $aFile)
    {
        if (!$oImagesTranscoder)
            return false;

        if (in_array($aFile['ext'], array('jpg', 'jpeg', 'png', 'gif', /* when ImageMagick is used - 'tif', 'tiff', 'bmp', 'ico', 'psd' */)) && (is_a($oImagesTranscoder, 'BxDolTranscoderImage') || is_a($oImagesTranscoder, 'BxDolTranscoderProxy')))
            return true;
        
        return false;
    }

    protected function isAdmin ($iContentId = 0)
    {
        return $this->_oModule->_isModerator (false);
    }
}

/** @} */
