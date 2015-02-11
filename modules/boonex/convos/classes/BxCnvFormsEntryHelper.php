<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Convos Convos
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Entry forms helper functions
 */
class BxCnvFormsEntryHelper extends BxBaseModTextFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }

    public function deleteData ($iContentId, $aContentInfo = false, $oProfile = null, $oForm = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if ($sErrorMsg = $this->_oModule->deleteConvoForever ($iContentId))
            return $sErrorMsg;

        if ($sResult = $this->onDataDeleteAfter ($aContentInfo[$CNF['FIELD_ID']], $aContentInfo, $oProfile))
            return $sResult;

        // create an alert
        bx_alert($this->_oModule->getName(), 'deleted', $aContentInfo[$CNF['FIELD_ID']]);

        return '';
    }
}

/** @} */
