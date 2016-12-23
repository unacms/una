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
 * Create/Edit entry form
 */
class BxCnvFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_convos';
        parent::__construct($aInfo, $oTemplate);

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addJsTranslation(array(
            '_bx_cnv_draft_saving_error',
            '_bx_cnv_draft_saved_success',
        ));
    }

    public function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = $this->_oModule->_oConfig->CNF;
        if ($bRet = parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges)) {
            $iFolder = $this->_oModule->_oDb->getConversationFolder($iContentId, bx_get_logged_profile_id());

            $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
            if ($aContentInfo && bx_get_logged_profile_id() == $aContentInfo[$CNF['FIELD_AUTHOR']]) // allow to edit participants for author only
                $this->_updateParticipants ($iContentId, $iFolder, $iFolder == BX_CNV_FOLDER_DRAFTS ? true : false);
        }

        return $bRet;
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $aValsToAdd['last_reply_timestamp'] = time();
        $aValsToAdd['last_reply_profile_id'] = bx_get_logged_profile_id();

        $bSaveToDrafts = bx_get('draft_save');
        $iContentId = bx_get('draft_id');
        $bDraft = $iContentId ? BX_CNV_FOLDER_DRAFTS == $this->_oModule->_oDb->getConversationFolder($iContentId, bx_get_logged_profile_id()) : false;

        if ($iContentId) {

            if (!$bDraft)
                return 0;

            if (!parent::update ($iContentId, $aValsToAdd, $isIgnore))
                return 0;

        } else {
            $iContentId = parent::insert ($aValsToAdd, $isIgnore);
            if (!$iContentId)
                return 0;
        }

        if ($bSaveToDrafts) {

            if (!$bDraft)
                $this->_oModule->_oDb->conversationToFolder($iContentId, BX_CNV_FOLDER_DRAFTS, bx_get_logged_profile_id(), 0);

            // draft is saved via ajax call only, upon successfull draft saving content id is returned
            echo $iContentId;
            exit;

        } else {

            // check for spam
            $bSpam = false;
            bx_alert('system', 'check_spam', 0, getLoggedId(), array('is_spam' => &$bSpam, 'content' => $this->getCleanValue('text'), 'where' => $this->MODULE));
            $iFolder = $bSpam ? BX_CNV_FOLDER_SPAM : BX_CNV_FOLDER_INBOX;

            $this->_updateParticipants ($iContentId, $iFolder, $bDraft);
        }

        return $iContentId;
    }

    protected function _updateParticipants ($iContentId, $iFolder, $bDraft) 
    {
        $aRecipientsOld = $this->_oModule->_oDb->getCollaborators($iContentId);

        // place conversation to "inbox" (or "spam" - in case of spam) folder
        $aRecipients = array_unique(array_merge($this->getCleanValue('recipients'), array(bx_get_logged_profile_id())), SORT_NUMERIC);
        foreach ($aRecipients as $iProfile) {
            $oProfile = BxDolProfile::getInstance($iProfile);
            if (!$oProfile)
                continue;

            if ($bDraft && $oProfile->id() == bx_get_logged_profile_id())
                $this->_oModule->_oDb->moveConvo($iContentId, $oProfile->id(), $iFolder);
            else
                $this->_oModule->_oDb->conversationToFolder($iContentId, $iFolder, $oProfile->id(), $oProfile->id() == bx_get_logged_profile_id() ? 0 : -1);
        }

        // remove participants
        foreach ($aRecipientsOld as $iProfileId => $iCount) {
            if (!in_array($iProfileId, $aRecipients))
                $this->_oModule->_oDb->removeCollaborator($iContentId, $iProfileId);
        }
    }

    protected function genCustomInputRecipients ($aInput)
    {
        $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . "modules/?r=convos/ajax_get_recipients";
        return $this->genCustomInputUsernamesSuggestions($aInput);
    }

    protected function genCustomInputSubmitText ($aInput)
    {
        $aVars = array();
        return $this->_oModule->_oTemplate->parseHtmlByName('form_submit_text.html', $aVars);
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = $this->_oModule->_oConfig->CNF;
        if ($aValues && bx_get_logged_profile_id() != $aValues[$CNF['FIELD_AUTHOR']]) { // unset some fields for non author
            unset($this->aInputs[$CNF['FIELD_ALLOW_EDIT']]);
            unset($this->aInputs['recipients']);
        }

        if ($iContentId = bx_get('draft_id')) { // if adding from draft, fill in existing fields info
            $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
            if ($aContentInfo)
                $aValues = array_merge($aContentInfo, $aValues);
        }

        if ($sProfilesIds = bx_get('profiles')) { // if writing directly to some recipients, pre-fill them
            $a = explode(',', $sProfilesIds);
            $a = array_unique(BxDolFormCheckerHelper::passInt($a));
            if ($a)
                $aValues['recipients'] = array_merge(empty($aValues['recipients']) ? array() : $aValues['recipients'], $a);            
        }

        $sEt = bx_get('et');
        if($sEt !== false) { // if 'attachable email template' was specified, process it
            $aEt = unserialize(base64_decode(urldecode($sEt)));

            if(!empty($aEt) && is_array($aEt) && !empty($aEt['name'])) {
                $sName = bx_process_input($aEt['name']);
                $aParams = isset($aEt['params']) ? bx_process_input($aEt['params']) : array();

                $sAet = getParam('sys_email_attachable_email_templates');
                if(strpos($sAet, $sName) !== false) {
                    $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate($sName, $aParams);
                    if(!empty($aTemplate) && is_array($aTemplate)) {
                        if(isset($this->aInputs[$CNF['FIELD_TITLE']]) && empty($this->aInputs[$CNF['FIELD_TITLE']]['value']))
                            $this->aInputs[$CNF['FIELD_TITLE']]['value'] = $aTemplate['Subject'];

                        if(isset($this->aInputs[$CNF['FIELD_TEXT']]) && empty($this->aInputs[$CNF['FIELD_TEXT']]['value']))
                            $this->aInputs[$CNF['FIELD_TEXT']]['value'] = $aTemplate['Body'];
                    }
                }
            }
            
        }

        return parent::initChecker ($aValues, $aSpecificValues);
    }

    function isValid ()
    {
        if (bx_get('draft_save')) // form is always valid when saving to drafts
            return true;
        return parent::isValid ();
    }

    public function processFiles ($sFieldFile, $iContentId = 0, $isAssociateWithContent = false)
    {
        if (!$isAssociateWithContent && bx_get('draft_id')) // when draft is already saved then db update is called but we still need to do association since it's draft
            $isAssociateWithContent = true; // TODO: if edit mode will be added, then this functionality maybe reconsidered
         
        return parent::processFiles ($sFieldFile, $iContentId, $isAssociateWithContent);
    }
}

/** @} */
