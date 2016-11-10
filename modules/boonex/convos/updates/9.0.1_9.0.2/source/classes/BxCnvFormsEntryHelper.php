<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Convos Convos
 * @ingroup     UnaModules
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

    protected function _getProfileAndContentData ($iContentId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        
        list ($oProfile, $aContentInfo) = parent::_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return array($oProfile, $aContentInfo);

        $aCollaborators = $this->_oModule->_oDb->getCollaborators($aContentInfo[$CNF['FIELD_ID']]);
        if ($aCollaborators)
            $aContentInfo['recipients'] = array_keys($aCollaborators);

        return array($oProfile, $aContentInfo);
    }
}

/** @} */
