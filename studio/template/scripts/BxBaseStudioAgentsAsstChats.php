<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioAgentsAsstChats extends BxDolStudioAgentsAsstChats
{
    protected $_sUrlPage;
    protected $_sFieldName;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sUrlPage = BX_DOL_URL_STUDIO . 'agents.php?page=assistants&spage=chats&aid=' . $this->_iAssistantId;

        $this->_sFieldName = 'name';
    }

    public function getPageJsObject()
    {
        return 'oBxDolStudioPageAgents';
    }

    public function performActionAdd()
    {
        $sAction = 'add';

        $aForm = $this->_getForm($sAction);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = ['assistant_id' => $this->_iAssistantId, 'added' => time()];

            $sName = $oForm->getCleanValue($this->_sFieldName);
            $sName = BxDolAIAssistant::getChatName($sName);
            BxDolForm::setSubmittedValue($this->_sFieldName, $sName, $oForm->aFormAttrs['method']);

            if(($iId = $oForm->insert($aValsToAdd)) !== false)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            else
                $aRes = ['msg' => _t('_sys_txt_error_occured')];

            return echoJson($aRes);
        }

        $sFormId = $oForm->getId();
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup', _t('_sys_agents_assistants_chats_popup_add'), $this->_oTemplate->parseHtmlByName('agents_automator_form.html', [
            'form_id' => $sFormId,
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    public function performActionEdit()
    {
        $sAction = 'edit';

        $iId = $this->_getId();
        $aChat = $this->_oDb->getChatsBy(['sample' => 'id', 'id' => $iId]);

        $aForm = $this->_getFormEdit($sAction, $aChat);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = [];

            $sName = $oForm->getCleanValue($this->_sFieldName);
            if($aChat[$this->_sFieldName] != $sName) {
                $sName = BxDolAIAssistant::getChatName($sName);
                BxDolForm::setSubmittedValue($this->_sFieldName, $sName, $oForm->aFormAttrs['method']);
            }

            if($oForm->update($iId, $aValsToAdd) !== false)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            else
                $aRes = ['msg' => _t('_sys_txt_error_occured')];

            return echoJson($aRes);
        } 

        $sFormId = $oForm->getId();
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup', _t('_sys_agents_assistants_chats_popup_edit'), $this->_oTemplate->parseHtmlByName('agents_automator_form.html', [
            'form_id' => $sFormId,
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }
    
    public function performActionStore()
    {
        $sAction = 'store';

        $oAI = BxDolAI::getInstance();
                
        $iId = $this->_getId();
        $aChat = $this->_oDb->getChatsBy(['sample' => 'id', 'id' => $iId]);
        $aAssistant = $this->_oDb->getAssistantsBy(['sample' => 'id', 'id' => $aChat['assistant_id']]);

        $aComments = $oAI->getAssistantChatCmtsObject($iId)->getCommentsBy(['type' => 'object_id', 'object_id' => $iId]);

        $aMessages = [];
        foreach($aComments as $aComment)
            $aMessages[] = [
                'role' => (int)$aComment['cmt_author_id'] == $this->_iProfileIdAi ? "assistant" : "user",
                'content' => $aComment['cmt_text']
            ];
        
        $oAIModel = $oAI->getModelObject($aAssistant['model_id']);

        if(!empty($aChat['ai_file_id'])) {
            $aResponse = $oAIModel->callVectorStoresFilesDelete($aAssistant['ai_vs_id'], $aChat['ai_file_id']);
            if(!$aResponse)
                return echoJson([]);

            $aResponse = $oAIModel->callFilesDelete($aChat['ai_file_id']);
            if(!$aResponse)
                return echoJson([]);

            $this->_oDb->updateChats(['ai_file_id' => '', 'stored' => 0], ['id' => $iId]);
        }

        $aRes = [];
        if(($sFile = $aChat['name'] . '.json') && ($aFile = $oAIModel->callFiles(['content' => json_encode($aMessages), 'name' => $sFile, 'mime' => 'application/json'])) !== false) {
            if(($aResponse = $oAIModel->callVectorStoresFiles($aAssistant['ai_vs_id'], ['file_id' => $aFile['id']])) !== false) {
                $iNow = time();
                $this->_oDb->insertFile(['name' => $sFile, 'assistant_id' => $aAssistant['id'], 'added' => $iNow, 'ai_file_id' => $aFile['id'], 'locked' => 1]);
                $this->_oDb->updateChats(['ai_file_id' => $aFile['id'], 'stored' => $iNow], ['id' => $iId]);

                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            }
        }

        return echoJson($aRes);
    }

    public function performActionUnstore()
    {
        $sAction = 'unstore';

        $oAI = BxDolAI::getInstance();
                
        $iId = $this->_getId();
        $aChat = $this->_oDb->getChatsBy(['sample' => 'id', 'id' => $iId]);
        if(empty($aChat['ai_file_id'])) 
            return echoJson([]);

        $aAssistant = $this->_oDb->getAssistantsBy(['sample' => 'id', 'id' => $aChat['assistant_id']]);

        $oAIModel = $oAI->getModelObject($aAssistant['model_id']);
        if(!$oAIModel->callVectorStoresFilesDelete($aAssistant['ai_vs_id'], $aChat['ai_file_id']))
            return echoJson([]);

        if(!$oAIModel->callFilesDelete($aChat['ai_file_id']))
            return echoJson([]);

        $this->_oDb->deleteFiles(['ai_file_id' => $aChat['ai_file_id']]);
        $this->_oDb->updateChats(['ai_file_id' => '', 'stored' => 0], ['id' => $iId]);

        echoJson(['grid' => $this->getCode(false), 'blink' => $iId]);
    }

    protected function _getCellType($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t('_sys_agents_assistants_chats_txt_type_' . $mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellStored($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($mixedValue ? bx_time_js($mixedValue) : '', $sKey, $aField, $aRow);
    }

    protected function _getActionChat($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = [])
    {
    	$a['attr'] = array_merge($a['attr'], [
            "onclick" => "window.open('" . $this->_sUrlPage . "&cid=" . $aRow['id'] . "', '_self');"
    	]);

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
    
    protected function _getActionStore($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = [])
    {
        $aChat = $this->_oDb->getChatsBy(['sample' => 'id', 'id' => $aRow['id']]);
        if(!empty($aChat['ai_file_id'])) 
            return '';

        return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
    
    protected function _getActionUnstore($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = [])
    {
        $aChat = $this->_oDb->getChatsBy(['sample' => 'id', 'id' => $aRow['id']]);
        if(empty($aChat['ai_file_id'])) 
            return '';

        return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();

        $this->_oTemplate->addJs(['jquery.form.min.js']);

        $oForm = new BxTemplStudioFormView([]);
        $oForm->addCssJs();
    }
    
    protected function _isCheckboxDisabled($aRow)
    {
        return false;
    }

    protected function _getActionsDisabledBehavior($aRow)
    {
        return false;
    }

    protected function _delete ($mixedId)
    {
        $aChat = $this->_oDb->getChatsBy(['sample' => 'id', 'id' => (int)$mixedId]);

        $mixedResult = parent::_delete($mixedId);
        if($mixedResult)
            BxDolAIAssistant::getObjectInstance($aChat['assistant_id'])->deleteChat($aChat);

        return $mixedResult;
    }

    protected function _getFormEdit($sAction, $aChat = [])
    {
        $aForm = $this->_getForm($sAction, $aChat);
        $aForm['form_attrs']['action'] .= '&id=' . $aChat['id'];

        return $aForm;
    }

    protected function _getForm($sAction, $aChat = [])
    {
        $sJsObject = $this->getPageJsObject();

        $aForm = array(
            'form_attrs' => array(
                'id' => 'bx_std_agents_assistants_' . $sAction,
                'action' => BX_DOL_URL_ROOT . bx_append_url_params('grid.php', ['o' => 'sys_studio_agents_assistants_chats', 'a' => $sAction, 'aid' => $this->_iAssistantId]),
                'method' => 'post',
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_agents_assistants_chats',
                    'key' => 'id',
                    'submit_name' => 'do_submit',
                ),
            ),
            'inputs' => array(
                'name' => [
                    'type' => 'text',
                    'name' => 'name',
                    'required' => '1',
                    'caption' => _t('_sys_agents_assistants_chats_field_name'),
                    'value' => isset($aChat['name']) ? $aChat['name'] : '',
                    'required' => '1',
                    'checker' => [
                        'func' => 'Avail',
                        'params' => [],
                        'error' => _t('_sys_agents_form_field_err_enter'),
                    ],
                    'db' => [
                        'pass' => 'Xss',
                    ]
                ],
                'description' => [
                    'type' => 'textarea',
                    'name' => 'description',
                    'caption' => _t('_sys_agents_assistants_chats_field_description'),
                    'value' => isset($aChat['description']) ? $aChat['description'] : '',
                    'required' => '1',
                    'checker' => [
                        'func' => 'Avail',
                        'params' => [],
                        'error' => _t('_sys_agents_form_field_err_enter'),
                    ],
                    'db' => [
                        'pass' => 'Xss',
                    ]
                ],
                'submit' => array(
                    'type' => 'input_set',
                    0 => array (
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_sys_submit'),
                    ),
                    1 => array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_sys_close'),
                        'attrs' => array('class' => 'bx-def-margin-sec-left', 'onclick' => '$(\'.bx-popup-applied:visible\').dolPopupHide();'),
                    ),
                ),

            ),
        );

        return $aForm;
    }
    
    protected function _getId()
    {
        $aIds = bx_get('ids');
        if(!empty($aIds) && is_array($aIds))
            return array_shift($aIds);

        $iId = (int)bx_get('id');
        if(!$iId)
            return false;

        return $iId;
    }
}

/** @} */
