<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
 *
 * @{
 */

class BxAlbumsUploaderCrop extends BxTemplUploaderCrop
{
    protected $_oModule;

    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);
        $this->_oModule = BxDolModule::getInstance('bx_albums');
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
}

/** @} */
