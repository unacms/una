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

    public function onDataAddAfter ($iAccountId, $iContentId)
    {
        if ($s = parent::onDataAddAfter($iAccountId, $iContentId))
            return $s;

        if (!($aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId)))
            return MsgBox(_t('_sys_txt_error_occured'));

        // send notification to all collaborators
        $oProfile = BxDolProfile::getInstance($aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_AUTHOR']]);
        $aCollaborators = $this->_oModule->_oDb->getCollaborators($aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ID']]);
        foreach ($aCollaborators as $iCollaborator => $iReadComments) {
            if ($iCollaborator == $oProfile->id())
                continue;
            sendMailTemplate('bx_cnv_new_message', 0, $iCollaborator, array(
                'SenderDisplayName' => $oProfile->getDisplayName(),
                'SenderUrl' => $oProfile->getUrl(),
                'Message' => $aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_TEXT']],
            ), BX_EMAIL_NOTIFY);
        }
            
        return '';
    }
}

/** @} */
