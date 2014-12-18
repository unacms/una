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

bx_import ('BxTemplUploaderHTML5');
bx_import('BxDolModule');

class BxAlbumsUploaderHTML5 extends BxTemplUploaderHTML5
{
    protected $_oModule;

    public function __construct ($aObject, $sStorageObject, $sUniqId)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId);
        $this->_oModule = BxDolModule::getInstance('bx_albums');
    }

    protected function getGhostTemplateVars($aFile, $iProfileId, $iContentId, $oStorage, $oImagesTranscoder)
    {
        return array('file_title' => $this->_oModule->_oDb->getFileTitle($aFile['id']));
    }
}

/** @} */
