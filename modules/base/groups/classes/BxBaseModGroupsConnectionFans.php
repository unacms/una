<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Groups Groups
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxBaseModGroupsConnectionFans extends BxTemplConnection
{
    protected $_sModule;
    protected $_oModule;

    protected $_bQuestionnaire;

    protected $_bBan;
    protected $_oBanConnection;

    public function __construct($aObject)
    {
        parent::__construct($aObject);

        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->_bQuestionnaire = false;

        $this->_bBan = false;
        $this->_oBanConnection = null;
    }

    public function init()
    {
        if($this->_bBan)
            $this->_oBanConnection = BxDolConnection::getObjectInstance('sys_profiles_bans');
    }

    public function getModule()
    {
        return $this->_sModule;
    }

    public function checkAllowedAddConnection($iInitiator, $iContent, $isPerformAction = false, $isMutual = false, $isInvertResult = false, $isSwap = false, $isCheckExists = true)
    {
        if($this->_bBan && $this->_oBanConnection->isConnected($iContent, $iInitiator))
            return _t('_sys_txt_access_denied');

        return $this->checkAllowedConnect ($iInitiator, $iContent, $isPerformAction, $isMutual, $isInvertResult, $isSwap, $isCheckExists);
    }

    public function actionRemove($iContent = 0, $iInitiator = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aResult = parent::actionRemove($iContent, $iInitiator);
        if($this->_bQuestionnaire && $aResult['err'] == false) {
            if(!$iContent)
                $iContent = bx_process_input($_POST['id'], BX_DATA_INT);

            $aContentInfo = $this->_oModule->_oDb->getContentInfoByProfileId($iContent);
            if(!empty($aContentInfo) && is_array($aContentInfo))
                $this->_oModule->_oDb->deleteAnswersProfileId($aContentInfo[$CNF['FIELD_ID']], $iInitiator ? $iInitiator : bx_get_logged_profile_id());
        }

        return $aResult;
    }

    public function actionReject ($iInitiator = 0, $iContent = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aResult = parent::actionReject($iInitiator, $iContent);
        if($this->_bQuestionnaire && $aResult['err'] == false) {
            if(!$iContent)
                $iContent = bx_process_input($_POST['id'], BX_DATA_INT);

            $aContentInfo = $this->_oModule->_oDb->getContentInfoByProfileId($iContent);
            if(!empty($aContentInfo) && is_array($aContentInfo))
                $this->_oModule->_oDb->deleteAnswersProfileId($aContentInfo[$CNF['FIELD_ID']], $iInitiator ? $iInitiator : bx_get_logged_profile_id());
        }

        return $aResult;
    }

    public function hasQuestionnaire($iContentProfileId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        
        if(!$this->_bQuestionnaire || empty($CNF['FIELD_JOIN_CONFIRMATION']))
            return false;

        $aContentInfo = $this->_oModule->_oDb->getContentInfoByProfileId($iContentProfileId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return false;

        return (int)$aContentInfo[$CNF['FIELD_JOIN_CONFIRMATION']] != 0 && $this->_oModule->_oDb->hasQuestions($aContentInfo[$CNF['FIELD_ID']]);
    }
    
    public function isQuestionnaireAnswered($iContentProfileId, $iProfileId = 0)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();
        if(!$iProfileId)
            return false;

        if(!$this->_bQuestionnaire || empty($CNF['FIELD_JOIN_CONFIRMATION']))
            return false;

        $aContentInfo = $this->_oModule->_oDb->getContentInfoByProfileId($iContentProfileId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return false;

        return (int)$aContentInfo[$CNF['FIELD_JOIN_CONFIRMATION']] != 0 && $this->_oModule->_oDb->areQuestionsAnswered($aContentInfo[$CNF['FIELD_ID']], $iProfileId);
    }

    public function getQuestionnaireForm($sAction, $iContentProfileId, $aParams = [])
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        
        if(!$this->_bQuestionnaire)
            return false;

        if(empty($aParams['request']) || !is_array($aParams['request']))
            $aParams['request'] = [];

        $aForm = [
            'form_attrs' => [
                'id' => $this->_oModule->getName() . '_questionnaire',
                'action' => BX_DOL_URL_ROOT . bx_append_url_params($this->_oModule->_oConfig->getBaseUri() . 'get_questionnaire', array_merge([
                    'o' => $this->_sObject, 
                    'a' => $sAction, 
                    'cpi' => $iContentProfileId
                ], $aParams['request']))
            ],
            'params' => [
                'db' => [
                    'table' => $CNF['TABLE_ANSWERS'],
                    'key' => '',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ],
                'module' => $this->_sModule,
                'object' => $this->_sModule . '_questionnaire',
                'display' => $this->_sModule . '_questionnaire_answer',
                'view_mode' => 0,
            ],
            'inputs' => []
        ];

        $aQuestions = $this->_oModule->_oDb->getQuestions(['sample' => 'content_pid', 'content_pid' => $iContentProfileId]);
        if(empty($aQuestions) || !is_array($aQuestions))
            return false;

        foreach($aQuestions as $aQuestion) {
            $sName = 'question_' . $aQuestion['id'];

            $aForm['inputs'][$sName] = [
                'type' => 'text',
                'name' => $sName,
                'caption' => $aQuestion['question'],
                'value' => '',
                'required' => '1',
                'checker' => [
                    'func' => 'Avail',
                    'params' => [],
                    'error' => _t($CNF['T']['form_qnr_field_qn_err']),
                ],
                'db' => [
                    'pass' => 'Xss',
                ],
            ];
        }

        $aForm['inputs']['controls'] = [
            'name' => 'controls',
            'type' => 'input_set', [
                'type' => 'submit',
                'name' => 'do_submit',
                'value' => _t('_Submit'),
            ], [
                'type' => 'reset',
                'name' => 'close',
                'value' => _t('_Cancel'),
                'attrs' => [
                    'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                    'class' => 'bx-def-margin-sec-left',
                ],
            ]
        ];

    	return new BxTemplFormView($aForm);
    }

    protected function _checkAllowedConnectInitiator ($oInitiator, $isPerformAction = false)
    {
        if(!bx_srv($oInitiator->getModule(), 'act_as_profile'))
            return CHECK_ACTION_RESULT_ALLOWED;

        return parent::_checkAllowedConnectInitiator($oInitiator, $isPerformAction);
    }

    public function _checkAllowedConnectContent ($oContent)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(bx_srv($oContent->getModule(), 'act_as_profile'))
            return CHECK_ACTION_RESULT_ALLOWED;

        if(!empty($CNF['OBJECT_PRIVACY_VIEW']) && ($oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW'])) !== false) {
            $iContentId = $oContent->getContentId();
            $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

            if(in_array($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']], array_merge($oPrivacy->getPartiallyVisiblePrivacyGroups(), ['s'])))
                return CHECK_ACTION_RESULT_ALLOWED;
        }

        return parent::_checkAllowedConnectContent($oContent);
    }
}

/** @} */
