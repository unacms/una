<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry forms helper functions
 */
class BxBaseModGeneralFormsEntryHelper extends BxDolProfileForms
{
    protected $_oModule;

    /**
     * 'Ajax Mode' determines the format of response. If it's TRUE the response 
     * (a form or an error appeared during form creation) should be returned 
     * as text during initial loading, while all other responses appeared after 
     * form submit should be arrays, which are ready to path to echoJson.
     */
    protected $_bAjaxMode;

    /**
     * Use absolute Action URL in generated form object. 
     * It's needed in Ajax Mode.
     */
    protected $_bAbsoluteActionUrl;

    protected $_bDynamicMode;

    public function __construct($oModule)
    {
        parent::__construct();
        $this->_oModule = $oModule;

        $this->_bDynamicMode = false;

        $this->_bAjaxMode = false;
        $mixedAjaxMode = bx_get('ajax_mode');
        if($mixedAjaxMode !== false)
            $this->setAjaxMode($mixedAjaxMode);

        $this->_bAbsoluteActionUrl = false;
        $mixedAbsoluteActionUrl = bx_get('absolute_action_url');
        if($mixedAbsoluteActionUrl !== false)
            $this->setAbsoluteActionUrl($mixedAbsoluteActionUrl);
    }

    public function setAjaxMode($bAjaxMode)
    {
        $this->_bAjaxMode = (bool)$bAjaxMode;
        if($this->_bAjaxMode)
            $this->setDynamicMode(true);
    }

    public function setAbsoluteActionUrl($bAbsoluteActionUrl)
    {
        $this->_bAbsoluteActionUrl = (bool)$bAbsoluteActionUrl;
    }

    public function setDynamicMode($bDynamicMode)
    {
        $this->_bDynamicMode = (bool)$bDynamicMode;
    }

    public function getObjectStorage()
    {
        return BxDolStorage::getObjectInstance($this->_oModule->_oConfig->CNF['OBJECT_STORAGE']);
    }

    public function getObjectFormAdd ($sDisplay = false)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        if(false === $sDisplay)
            $sDisplay = $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'];

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $sDisplay, $this->_oModule->_oTemplate);
        if($this->_bAjaxMode)
            $oForm->setAjaxMode($this->_bAjaxMode);

        if($this->_bAbsoluteActionUrl)
            $this->_setAbsoluteActionUrl('add', $oForm);

        return $oForm; 
    }

    public function getObjectFormEdit ($sDisplay = false)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        if(false === $sDisplay)
            $sDisplay = $CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT'];

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $sDisplay, $this->_oModule->_oTemplate);
        if($this->_bAjaxMode)
            $oForm->setAjaxMode($this->_bAjaxMode);

        if($this->_bAbsoluteActionUrl)
            $this->_setAbsoluteActionUrl('edit', $oForm);

        return $oForm;
    }

    public function getObjectFormView ($sDisplay = false)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        if (false === $sDisplay)
            $sDisplay = $CNF['OBJECT_FORM_ENTRY_DISPLAY_VIEW'];

        return BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $sDisplay, $this->_oModule->_oTemplate);
    }

    public function getObjectFormDelete ($sDisplay = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (false === $sDisplay)
            $sDisplay = $CNF['OBJECT_FORM_ENTRY_DISPLAY_DELETE'];

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $sDisplay, $this->_oModule->_oTemplate);
        if($this->_bAjaxMode)
            $oForm->setAjaxMode($this->_bAjaxMode);

        if($this->_bAbsoluteActionUrl)
            $this->_setAbsoluteActionUrl('delete', $oForm);

        return $oForm;
    }

    public function viewDataEntry ($iContentId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_sys_txt_error_entry_is_not_defined'));

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedView($aContentInfo)))
            return MsgBox($sMsg);

        $oForm = $this->getObjectFormView();
        if (!$oForm)
            return '';

        $oForm->initChecker($aContentInfo);

        if(!empty($CNF['FIELD_TEXT']) &&  !$oForm->isInputVisible($CNF['FIELD_TEXT']))
            return '';

        return $this->_oModule->_oTemplate->entryText($aContentInfo);
    }

    public function addData ($iProfile, $aValues, $sDisplay = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        // check and display form
        $oForm = $this->getObjectFormAdd($sDisplay);
        if (!$oForm)
            return array('code' => 1, 'message' => '_sys_txt_error_occured');

        $oForm->aFormAttrs['method'] = BX_DOL_FORM_METHOD_SPECIFIC;
        $oForm->aParams['csrf']['disable'] = true;
        if(!empty($oForm->aParams['db']['submit_name'])) {            
            $sSubmitName = false;
            if (is_array($oForm->aParams['db']['submit_name'])) {
                foreach ($oForm->aParams['db']['submit_name'] as $sVal) {
                    if (isset($oForm->aInputs[$sVal])) {
                        $sSubmitName = $sVal;
                        break;
                    }
                }
            } 
            else {
                $sSubmitName = $oForm->aParams['db']['submit_name'];
            }
            if ($sSubmitName && isset($oForm->aInputs[$sSubmitName]))
                $aValues[$sSubmitName] = $oForm->aInputs[$sSubmitName]['value'];
        }

        $oForm->initChecker(array(), $aValues);
        if (!$oForm->isSubmittedAndValid()) {
            $aErrors = array();
            array_walk($oForm->aInputs, function($aInput, $sKey) use (&$aErrors) {
                if(!empty($aInput['error']))
                    $aErrors[$sKey] = $aInput['error'];
            });

            return array('code' => 2, 'message' => '_sys_txt_error_occured', 'errors' => $aErrors);
        }

        // insert data into database
        $aValsToAdd = array ();
        if(isset($CNF['FIELD_AUTHOR']))
            $aValsToAdd[$CNF['FIELD_AUTHOR']] = $iProfile;

        $iContentId = $oForm->insert($aValsToAdd);
        if (!$iContentId) {
            if (!$oForm->isValid())
                return array('code' => 2, 'message' => '_sys_txt_error_occured');
            else
                return array('code' => 3, 'message' => '_sys_txt_error_entry_creation');
        }

        $sResult = $this->onDataAddAfter(BxDolProfile::getInstance($iProfile)->getAccountId(), $iContentId);
        if($sResult)
            return array('code' => 4, 'message' => $sResult);

        list($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);

        /*
         * Process metas.
         * Note. It's essential to process metas a the very end, 
         * because all data related to an entry should be already
         * processed and are ready to be passed to alert. 
         */
        $this->_oModule->processMetasAdd($iContentId);

        /*
         * Create alert about the completed action.
         */
        $this->_oModule->alertAfterAdd($aContentInfo);

        return array('code' => 0, 'message' => '', 'content' => $aContentInfo);
    }

    public function addDataForm ($sDisplay = false, $sCheckFunction = false)
    {
        if (!$sCheckFunction)
            $sCheckFunction = 'checkAllowedAdd';

        $CNF = &$this->_oModule->_oConfig->CNF;

        $bAsJson = false;

        // get form object
        $oForm = $this->getObjectFormAdd($sDisplay);
        if (!$oForm)
            return $this->prepareResponse(MsgBox(_t('_sys_txt_error_occured')), $bAsJson, 'msg');

        $bAsJson = $this->_bAjaxMode && $oForm->isSubmitted();

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->$sCheckFunction())) {
            $oProfile = BxDolProfile::getInstance();
            if ($oProfile && ($aProfileInfo = $oProfile->getInfo()) && $aProfileInfo['type'] == 'system' && is_subclass_of($this->_oModule, 'BxBaseModProfileModule') && $this->_oModule->serviceActAsProfile()) // special check for system profile is needed, because of incorrect error message
                return $this->prepareResponse(MsgBox(_t('_sys_txt_access_denied')), $bAsJson, 'msg');
            else
                return $this->prepareResponse(MsgBox($sMsg), $bAsJson, 'msg');
        }

        // check and display form
        $oForm->initChecker();
        if (!$oForm->isSubmittedAndValid())
            return $this->prepareResponse($oForm->getCode($this->_bDynamicMode), $bAsJson, 'form', array(
            	'form_id' => $oForm->getId()
            ));

        // insert data into database
        $aValsToAdd = array ();
        $iContentId = $oForm->insert ($aValsToAdd);
        if (!$iContentId) {
            if (!$oForm->isValid())
                return $this->prepareResponse($oForm->getCode($this->_bDynamicMode), $bAsJson, 'form', array(
                    'form_id' => $oForm->getId()
                ));
            else
                return $this->prepareResponse(MsgBox(_t('_sys_txt_error_entry_creation')), $bAsJson, 'msg');
        }

        $sResult = $this->onDataAddAfter (getLoggedId(), $iContentId);
        if ($sResult)
            return $this->prepareResponse($sResult, $bAsJson, 'msg');

        list($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);

        /*
         * Process metas.
         * Note. It's essential to process metas a the very end, 
         * because all data related to an entry should be already
         * processed and are ready to be passed to alert. 
         */
        $this->_oModule->processMetasAdd($iContentId);

        // Perform ACL action
        $this->_oModule->$sCheckFunction(true);

        // Create alert about the completed action.
        $this->_oModule->alertAfterAdd($aContentInfo);

        // Redirect
        $this->redirectAfterAdd($aContentInfo);
    }

    public function redirectAfterAdd($aContentInfo, $sUrl = '')
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        if ($sUrl == '')
    	   $sUrl = 'page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']];
        
        bx_alert($this->_oModule->getName(), 'redirect_after_add', 0, false, array(
            'ajax_mode' => $this->_bAjaxMode,
            'content' => $aContentInfo,
            'override_result' => &$sUrl,
        ));

        if($this->_bAjaxMode) {
            echoJson($this->prepareResponse($sUrl, $this->_bAjaxMode, 'redirect'));
            exit;
        }
        else
            $this->_redirectAndExit($sUrl);
    }

    public function editDataForm ($iContentId, $sDisplay = false, $sCheckFunction = false, $bErrorMsg = true)
    {
        if (!$sCheckFunction)
            $sCheckFunction = 'checkAllowedEdit';

        $CNF = &$this->_oModule->_oConfig->CNF;

        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return $bErrorMsg ? MsgBox(_t('_sys_txt_error_entry_is_not_defined')) : '';

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->$sCheckFunction($aContentInfo)))
            return $bErrorMsg ? MsgBox($sMsg) : '';

        // check and display form
        $oForm = $this->getObjectFormEdit($sDisplay);
        if (!$oForm)
            return $bErrorMsg ? MsgBox(_t('_sys_txt_error_occured')) : '';

        $aSpecificValues = array();        
        if (!empty($CNF['OBJECT_METATAGS'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
            if ($oMetatags->locationsIsEnabled())
                $aSpecificValues = $oMetatags->locationGet($iContentId, empty($CNF['FIELD_LOCATION_PREFIX']) ? '' : $CNF['FIELD_LOCATION_PREFIX']);
        }
        $oForm->initChecker($aContentInfo, $aSpecificValues);

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        // update data in the DB
        $aTrackTextFieldsChanges = null;

        $this->onDataEditBefore ($aContentInfo[$CNF['FIELD_ID']], $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm);

        if (!$oForm->update ($aContentInfo[$CNF['FIELD_ID']], array(), $aTrackTextFieldsChanges)) {
            if (!$oForm->isValid())
                return $oForm->getCode();
            else
                return MsgBox(_t('_sys_txt_error_entry_update'));
        }

        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        $sResult = $this->onDataEditAfter ($aContentInfo[$CNF['FIELD_ID']], $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm);
        if ($sResult)
            return $sResult;

        /*
         * Process metas.
         * Note. It's essential to process metas a the very end, 
         * because all data related to an entry should be already
         * processed and are ready to be passed to alert. 
         */
        $this->_oModule->processMetasEdit($iContentId, $oForm);

        // Perform ACL action
        $this->_oModule->$sCheckFunction($aContentInfo, true);

        // Create alert about the completed action.
        $this->_oModule->alertAfterEdit($aContentInfo);
        
        // Redirect
        $this->redirectAfterEdit($aContentInfo);
    }

    protected function redirectAfterEdit($aContentInfo)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sUrl = 'page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']];
        bx_alert($this->_oModule->getName(), 'redirect_after_edit', 0, false, array(
            'content' => $aContentInfo,
            'override_result' => &$sUrl,
        ));

        $this->_redirectAndExit($sUrl);
    }

    public function deleteDataForm ($iContentId, $sDisplay = false, $sCheckFunction = false)
    {
        if (!$sCheckFunction)
            $sCheckFunction = 'checkAllowedDelete';
        
        $CNF = &$this->_oModule->_oConfig->CNF;

        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_sys_txt_error_entry_is_not_defined'));

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->$sCheckFunction($aContentInfo)))
            return MsgBox($sMsg);

        // check and display form
        $oForm = $this->getObjectFormDelete($sDisplay);
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $oForm->initChecker($aContentInfo);

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        if ($sError = $this->deleteData($aContentInfo[$CNF['FIELD_ID']], $aContentInfo, $oProfile, $oForm))
            return MsgBox($sError);

        // perform action
        $this->_oModule->$sCheckFunction($aContentInfo, true);

        // redirect
        $this->redirectAfterDelete($aContentInfo);
    }

    protected function redirectAfterDelete($aContentInfo)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sUrl = $CNF['URL_HOME'];
        $aMarkers = array(
            'account_id' => getLoggedId(),
            'profile_id' => bx_get_logged_profile_id(),
        );

        bx_alert($this->_oModule->getName(), 'redirect_after_delete', 0, false, array(
            'content' => $aContentInfo,
            'markers' => &$aMarkers,
            'override_result' => &$sUrl,
        ));

        $this->_redirectAndExit($sUrl, true, $aMarkers);
    }

    /**
     * Delete data entry
     * @param $iContentId entry id
     * @param $oForm optional content info array
     * @param $aContentInfo optional content info array
     * @param $oProfile optional content author profile
     * @return error string on error or empty string on success
     */
    public function deleteData ($iContentId, $aContentInfo = false, $oProfile = null, $oForm = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!$aContentInfo || !$oProfile)
            list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);

        if (!$aContentInfo)
            return _t('_sys_txt_error_entry_is_not_defined');

        if (!$oForm)
            $oForm = $this->getObjectFormDelete();

        if (!$oForm->delete ($aContentInfo[$CNF['FIELD_ID']], $aContentInfo))
            return _t('_sys_txt_error_entry_delete');

        // remove data from nested forms 
        $aNestedForms = BxDolFormQuery::getNestedFormObjects($oForm->aParams['object']);
        foreach ($aNestedForms as $aNestedForm) {
            BxDolFormQuery::deleteDataFromNestedForm($aNestedForm['table'], $iContentId);
        }
        
        if ($sResult = $this->onDataDeleteAfter ($aContentInfo[$CNF['FIELD_ID']], $aContentInfo, $oProfile))
            return $sResult;

        // create an alert
        bx_alert($this->_oModule->getName(), 'deleted', $aContentInfo[$CNF['FIELD_ID']], false, array(
            'content' => &$aContentInfo
        ));

        return '';
    }

    public function viewDataForm ($iContentId, $sDisplay = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        // get content data and profile info
        list ($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);
        if (!$aContentInfo)
            return MsgBox(_t('_sys_txt_error_entry_is_not_defined'));

        // check access
        if ($sMsg = $this->_processPermissionsCheckForViewDataForm ($aContentInfo, $oProfile))
            return MsgBox($sMsg);

        // get form
        $oForm = $this->getObjectFormView($sDisplay);
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        // process metatags
        if (!empty($CNF['OBJECT_METATAGS'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
            if ($oMetatags->keywordsIsEnabled()) {
                $aFields = $oMetatags->metaFields($aContentInfo, $CNF, $CNF['OBJECT_FORM_ENTRY_DISPLAY_VIEW']);
                $oForm->setMetatagsKeywordsData($iContentId, $aFields, $oMetatags);
            }
        }        

        // display profile
        $oForm->initChecker($aContentInfo);
        return $oForm->getCode();
    }

    protected function _processPermissionsCheckForViewDataForm ($aContentInfo, $oProfile)
    {
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedView($aContentInfo)))
            return $sMsg;

        return '';
    }

    public function onDataDeleteAfter ($iContentId, $aContentInfo, $oProfile)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(($oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW'])) !== false)
            $oPrivacy->deleteGroupCustomByContentId($iContentId);

        bx_audit(
            $iContentId, 
            $this->_oModule->getName(), 
            '_sys_audit_action_detete_content',  
            $this->_oModule->_prepareAuditParams($aContentInfo)
        );
        
        return '';
    }

    public function onDataEditBefore ($iContentId, $aContentInfo, &$aTrackTextFieldsChanges, &$oProfile, &$oForm)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($CNF['FIELD_STATUS']) && isset($aContentInfo[$CNF['FIELD_STATUS']]) && $aContentInfo[$CNF['FIELD_STATUS']] == 'failed')
            $oForm->addTrackFields($CNF['FIELD_STATUS'], $aContentInfo);
    }

    public function onDataEditAfter ($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        /*
         * Load update data.
         */
        list($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);

        if(isset($CNF['FIELD_PHOTO']))
            $oForm->processFiles ($CNF['FIELD_PHOTO'], $iContentId, false);

        if(isset($CNF['FIELD_STATUS']) && ($aTrackResult = $oForm->isTrackFieldChanged($CNF['FIELD_STATUS'], true)) !== false)
            if($aTrackResult['old'] == 'failed' && $aTrackResult['new'] == 'active')
                $this->_oModule->alertAfterAdd($aContentInfo);

        if(isset($CNF['FIELD_ALLOW_VIEW_TO']) && !empty($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]) && ($oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW'])) !== false)
            $oPrivacy->reassociateGroupCustomWithContent($oProfile->id(), $iContentId, (int)$aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]);
        
        bx_audit(
            $iContentId, 
            $this->_oModule->getName(), 
            '_sys_audit_action_edit_content',  
            $this->_oModule->_prepareAuditParams($aContentInfo)
        );
        
        return '';
    }

    public function onDataAddAfter ($iAccountId, $iContentId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        list($oProfile, $aContentInfo) = $this->_getProfileAndContentData($iContentId);

        if(($oForm = $this->getObjectFormAdd()) !== false) {
            if(isset($CNF['FIELD_PHOTO']))
                $oForm->processFiles($CNF['FIELD_PHOTO'], $iContentId, true);
        }

        if(isset($CNF['FIELD_ALLOW_VIEW_TO']) && !empty($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]) && ($oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW'])) !== false)
            $oPrivacy->associateGroupCustomWithContent($oProfile->id(), $iContentId, (int)$aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]);

        bx_audit(
            $iContentId, 
            $this->_oModule->getName(), 
            '_sys_audit_action_add_content',  
            $this->_oModule->_prepareAuditParams($aContentInfo)
        );
        
        return '';
    }

    protected function prepareCustomRedirectUrl($s, $aContentInfo)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        $oProfile = BxDolProfile::getInstanceByContentAndType($aContentInfo[$CNF['FIELD_ID']], $this->_oModule->getName());

        $aMarkers = array(
            '{profile_id}',
            '{content_id}',
            '{module}',
        );
        $aReplacements = array(
            $oProfile ? $oProfile->id() : 0,
            $aContentInfo[$CNF['FIELD_ID']],
            $this->_oModule->getName(),
        );
        $s = str_replace($aMarkers, $aReplacements, $s);

        $s = bx_absolute_url(BxDolPermalinks::getInstance()->permalink($s));
        
        return $s;
    }
    
    protected function prepareResponse($mixedResponse, $bAsJson = false, $sKey = 'msg', $aAdditional = array())
    {
    	if(!$bAsJson)
            return $mixedResponse;

        $aResponse = array(
            $sKey => $mixedResponse,
            '_dt' => 'json'
        );

        if(!empty($aAdditional) && is_array($aAdditional))
            $aResponse = array_merge($aResponse, $aAdditional);

        return $aResponse;
    }
    
    protected function _setAbsoluteActionUrl($sType, &$oForm)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sKeyUri = 'URI_' . strtoupper($sType) . '_ENTRY';
        if(empty($CNF[$sKeyUri]))
            return;

        $oForm->setAbsoluteActionUrl(bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF[$sKeyUri])));
    }
}

/** @} */
