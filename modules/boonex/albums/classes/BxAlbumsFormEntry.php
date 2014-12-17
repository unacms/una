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

bx_import('BxBaseModTextFormEntry');

/**
 * Create/Edit entry form
 */
class BxAlbumsFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_albums';
        parent::__construct($aInfo, $oTemplate);
    }

    protected function _associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId)
    {
        parent::_associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId);

        $this->_oModule->_oDb->associateFileWithContent ($iContentId, $iFileId, $this->getCleanValue('title-' . $iFileId));
    }

    function _deleteFile ($iFileId)
    {
        if (!($bRet = parent::_deleteFile ($iFileId)))
            return false;

        $this->_oModule->_oDb->deassociateFileWithContent ($iFileId);

        return true;
    }
}

/** @} */
