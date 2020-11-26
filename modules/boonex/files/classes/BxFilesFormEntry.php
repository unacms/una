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
 * Create/Edit entry form
 */
class BxFilesFormEntry extends BxBaseModFilesFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_files';
        parent::__construct($aInfo, $oTemplate);
    }

    protected function _associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId, $sPictureField = '')
    {
        parent::_associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId, $sPictureField);
        $this->_oModule->_oDb->updateFileId($iContentId, $iFileId);
    }

    public function delete ($iContentId, $aContentInfo = array()) {
        $this->_oModule->_oDb->deleteFileBookmarks($iContentId);
        return parent::delete ($iContentId, $aContentInfo);
    }
}

/** @} */
