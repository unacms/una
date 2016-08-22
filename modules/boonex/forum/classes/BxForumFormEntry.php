<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Forum Forum
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxForumFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_forum';
        parent::__construct($aInfo, $oTemplate);

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addJs(array('jquery.form.min.js'));
        $oTemplate->addJsTranslation(array(
            '_bx_forum_draft_saving_error',
            '_bx_forum_draft_saved_success',
        ));
    }

    public function insert($aValsToAdd = array(), $isIgnore = false)
    {
    	$CNF = $this->_oModule->_oConfig->CNF;

        $aValsToAdd['lr_timestamp'] = time();
        $aValsToAdd['lr_profile_id'] = bx_get_logged_profile_id();

        $iDraftId = (int)bx_get('draft_id');
        $bDraftSave = (int)bx_get('draft_save') == 1; //--- draft is saved via ajax call only, upon successfull draft saving content id is returned

        $bDraft = false;
        if($iDraftId) {
        	$aDraftInfo = $this->_oModule->_oDb->getContentInfoById($iDraftId);
        	if(!empty($aDraftInfo[$CNF['FIELD_STATUS']]) && $aDraftInfo[$CNF['FIELD_STATUS']] == 'draft')
        		$bDraft = true;

            if(!$bDraft)
            	return 0;

			if(!$bDraftSave)
        		$aValsToAdd[$CNF['FIELD_STATUS']] = 'active';

            if(!parent::update($iDraftId, $aValsToAdd, $isIgnore))
				return 0;
        } 
        else {
        	if($bDraftSave)
        		$aValsToAdd[$CNF['FIELD_STATUS']] = 'draft';

            $iDraftId = parent::insert($aValsToAdd, $isIgnore);
            if(!$iDraftId)
				return 0;
        }

        if($bDraftSave) {
            echo $iDraftId;
            exit;
        }

        return $iDraftId;
    }

    protected function genCustomInputSubmitText ($aInput)
    {
        $aVars = array();
        return $this->_oModule->_oTemplate->parseHtmlByName('form_submit_text.html', $aVars);
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
    	$iContentId = bx_get('draft_id');
        if($iContentId) { // if adding from draft, fill in existing fields info
            $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
            if($aContentInfo)
				$aValues = array_merge($aContentInfo, $aValues);
        }

        return parent::initChecker ($aValues, $aSpecificValues);
    }

    function isValid ()
    {
        if(bx_get('draft_save')) // form is always valid when saving to drafts
            return true;

        return parent::isValid ();
    }
}

/** @} */
