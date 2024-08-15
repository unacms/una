<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

define('BX_DOL_AI_ASST_TYPE_PERMANENT', 1);
define('BX_DOL_AI_ASST_TYPE_TRANSIENT', 10);

class BxDolAIAssistant extends BxDol
{
    protected static $_sFileText = 'sys_asst_text.json';
    protected static $_sFileFaq = 'sys_asst_faq.json';

    protected $_oDb;   

    protected $_iId;
    protected $_aData;    

    public function __construct($aAssistant)
    {
        parent::__construct();

        if(empty($aAssistant) || !is_array($aAssistant))
            $this->_log("Unexpected value provided for the credentials");

        $this->_oDb = new BxDolAIQuery();

        $this->_iId = (int)$aAssistant['id'];
        $this->_aData = $aAssistant;
    }

    /**
     * Get assistant object instance by ID
     * @param $iId assistant ID
     * @return object instance or false on error
     */
    public static function getObjectInstance($iId)
    {
        $sPrefix = 'BxDolAIAssistant!';

        if(isset($GLOBALS['bxDolClasses'][$sPrefix . $iId]))
            return $GLOBALS['bxDolClasses'][$sPrefix . $iId];

        $aAssistant = BxDolAIQuery::getAssistantObject($iId);
        if(!$aAssistant)
            return false;

        $o = new BxDolAIAssistant($aAssistant);
        return ($GLOBALS['bxDolClasses'][$sPrefix . $iId] = $o);
    }

    public static function getName($sName)
    {
        return uriGenerate($sName, 'sys_agents_assistants', 'name', ['lowercase' => false]);
    }

    public static function getChatName($sName)
    {
        return uriGenerate($sName, 'sys_agents_assistants_chats', 'name', ['lowercase' => false]);
    }

    public static function pruning()
    {
        $oAi = BxDolAI::getInstance();

        if(getParam('sys_agents_asst_chats_trans_del') == 'on') {
            $aChats = $oAi->getAssistantChatsTransient(3600);
            foreach($aChats as $aChat)
                self::getObjectInstance($aChat['assistant_id'])->deleteChat($aChat);
        }
    }

    public function getModelObject()
    {
        return BxDolAI::getInstance()->getModelObject($this->_aData['model_id']);
    }

    public function getChatCmtsObject($iChatId)
    {
        return BxDolAI::getInstance()->getAssistantChatCmtsObject($iChatId);
    }

    public function getAskButton($sText)
    {
        $sTitle = _t('_sys_agents_assistants_txt_ask');
        if(!empty($sText))
            $sTitle .= ': ' . strmaxtextlen($sText, 16);

        return BxDolTemplate::getInstance()->parseButton($sTitle, [
            'class' => 'bx-btn sys-agents-ask',
            'onclick' => "javascrip:bx_agents_action(this, 'asst', 'ask', {id: " . $this->_iId . ", text: '" . $sText . "'})"
        ]);
    }
    
    public function getAskChat($sName = '', $sText = '', $oTemplate = false)
    {
        if(!$oTemplate)
            $oTemplate = BxDolTemplate::getInstance();

        $bName = !empty($sName);
        $bText = !empty($sText);

        $iChatId = 0;
        if($bName) {
            $aChat = $this->_oDb->getChatsBy(['sample' => 'name', 'name' => $sName]);
            if(!empty($aChat) && is_array($aChat))
                $iChatId = (int)$aChat['id'];
        }

        if(empty($iChatId)) {
            if(!$bName)
                $sName = self::getChatName($bText ? strmaxtextlen($sText, 8) : genRndPwd());

            $iChatId = $this->_oDb->insertChat([
                'name' => $sName,
                'type' => BX_DOL_AI_ASST_TYPE_TRANSIENT,
                'assistant_id' => $this->_iId, 
                'added' => time(),
            ]);
        }

        $sResult = '';
        if($iChatId !== false && ($oCmts = BxDolAI::getInstance()->getAssistantChatCmtsObject($iChatId, $oTemplate)) !== false) {
            $oCmts->setAllowDelete(false);

            if(!empty($sText))
                $oCmts->add([
                    'cmt_author_id' => bx_get_logged_profile_id(),
                    'cmt_parent_id' => 0,
                    'cmt_text' => $sText
                ]);

            $sResult = $oCmts->getCommentsBlock();
        }

        return $sResult;
    }

    public function deleteChat($mixedChat)
    {
        $aChat = is_array($mixedChat) ? $mixedChat : $this->_oDb->getChatsBy(['sample' => 'id', 'id' => (int)$mixedChat]);
        if(empty($aChat) || !is_array($aChat))
            return false;

        $oAIModel = $this->getModelObject();

        if(($oCmts = $this->getChatCmtsObject($aChat['id'])) !== false)
            $oCmts->onObjectDelete();

        if(!empty($aChat['ai_file_id'])) {
            $oAIModel->callVectorStoresFilesDelete($this->_aData['ai_vs_id'], $aChat['ai_file_id']);
            $oAIModel->callFilesDelete($aChat['ai_file_id']);
        }

        return true;
    }

    public function processActionAddKnowledge()
    {
        $sAction = 'add_knowledge';
        $oTemplate = BxDolTemplate::getInstance();

        $aForm = $this->_getForm($sAction, ['id' => $this->_iId]);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $oAIModel = $this->getModelObject();

            $sType = $oForm->getCleanValue('type');

            $sFile = self::${'_sFile' . ucfirst($sType)};
            $aFile = $this->_oDb->getFilesBy(['sample' => 'assistant_id', 'assistant_id' => $this->_iId, 'name' => $sFile]);

            $aContent = [];
            if(!empty($aFile) && is_array($aFile) && !empty($aFile['ai_file_id'])) {
                $sContent = $oAIModel->callFilesRetrieveContent($aFile['ai_file_id']);
                $aContent = json_decode($sContent, true);

                if(!$oAIModel->callVectorStoresFilesDelete($this->_aData['ai_vs_id'], $aFile['ai_file_id']))
                    return echoJson([]);

                if(!$oAIModel->callFilesDelete($aFile['ai_file_id']))
                    return echoJson([]);

                $this->_oDb->deleteFiles(['id' => $aFile['id']]);
            }

            switch($sType) {
                case 'text':
                    $aContent[] = bx_process_input($oForm->getCleanValue('text'));
                    break;

                case 'faq':
                    $aContent[] = [
                        'question' => bx_process_input($oForm->getCleanValue('faq_q')),
                        'answer' => bx_process_input($oForm->getCleanValue('faq_a'))
                    ];
                    break;
            }

            $aFileResponse = $oAIModel->callFiles(['content' => json_encode($aContent), 'name' => $sFile, 'mime' => 'application/json']);
            if(!$aFileResponse)
                return echoJson([]);

            if(!$oAIModel->callVectorStoresFiles($this->_aData['ai_vs_id'], ['file_id' => $aFileResponse['id']]))
                return echoJson([]);

            $this->_oDb->insertFile([
                'name' => $sFile, 
                'assistant_id' => $this->_iId, 
                'added' => time(), 
                'ai_file_id' => $aFileResponse['id'], 
                'ai_file_size' => $aFileResponse['bytes']
            ]);

            return echoJson(['msg' => _t('_sys_agents_assistants_msg_knowledge_added')]);
        }

        $sFormId = $oForm->getId();
        $sContent = BxTemplFunctions::getInstance()->popupBox($sFormId . '_popup', _t('_sys_agents_assistants_popup_add_knowledge'), $oTemplate->parseHtmlByName('agents_form.html', [
            'form_id' => $sFormId,
            'form' => $oForm->getCode(true),
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    public function processActionAsk()
    {
        $sAction = 'ask';
        $oTemplate = BxDolTemplate::getInstance();

        $sText = bx_get('text');
        if($sText !== false)
            $sText = bx_process_input($sText);

        $iChatId = $this->_oDb->insertChat([
            'name' => self::getChatName(strmaxtextlen($sText, 8)),
            'type' => BX_DOL_AI_ASST_TYPE_TRANSIENT,
            'assistant_id' => $this->_iId, 
            'added' => time(),
        ]);

        $sPopupContent = '';
        if($iChatId !== false && ($oCmts = BxDolAI::getInstance()->getAssistantChatCmtsObject($iChatId, $oTemplate)) !== false) {
            $oCmts->setAllowDelete(false);
            $oCmts->add([
                'cmt_author_id' => bx_get_logged_profile_id(),
                'cmt_parent_id' => 0,
                'cmt_text' => $sText
            ]);

            $sPopupContent = $oCmts->getCommentsBlock();
        }

        $sPopupId = 'bx_agents_assistants_ask';
        $sPopupContent = BxTemplFunctions::getInstance()->popupBox($sPopupId, _t('_sys_agents_assistants_popup_ask'), $oTemplate->parseHtmlByName('agents_popup.html', [
            'content' => $sPopupContent
        ]));

        return echoJson(['popup' => ['html' => $sPopupContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    protected function _getForm($sAction, $aAssistant = [])
    {
        $aForm = [
            'form_attrs' => [
                'id' => 'bx_agents_assistants_' . $sAction,
                'action' => BX_DOL_URL_ROOT . bx_append_url_params('agents.php', ['t' => 'asst', 'a' => $sAction]),
                'method' => 'post',
            ],
            'params' => [
                'db' => [
                    'submit_name' => 'do_submit',
                ],
            ],
            'inputs' => [],
        ];

        switch($sAction) {
            case 'add_knowledge';
                $aForm['inputs'] = [
                    'id' => [
                        'type' => 'hidden',
                        'name' => 'id',
                        'value' => $aAssistant['id'],
                    ],
                    'type' => [
                        'type' => 'select',
                        'name' => 'type',
                        'caption' => _t('_sys_agents_assistants_field_kwg_type'),
                        'value' => '',
                        'values' => [
                            ['key' => 'text', 'value' => _t('_sys_agents_assistants_field_kwg_type_text')],
                            ['key' => 'faq', 'value' => _t('_sys_agents_assistants_field_kwg_type_faq')]
                        ],
                        'attrs' => ['onchange' => "javascript:bx_aa_ak_type_change(this)"],
                    ],
                    'text' => [
                        'type' => 'textarea',
                        'name' => 'text',
                        'caption' => _t('_sys_agents_assistants_field_kwg_text'),
                        'value' => '',
                        'tr_attrs' => ['df' => '1', 'dt' => 'text']
                    ],
                    'faq_q' => [
                        'type' => 'text',
                        'name' => 'faq_q',
                        'caption' => _t('_sys_agents_assistants_field_kwg_faq_q'),
                        'value' => '',
                        'tr_attrs' => ['df' => '1', 'dt' => 'faq', 'style' => 'display:none']
                    ],
                    'faq_a' => [
                        'type' => 'textarea',
                        'name' => 'faq_a',
                        'caption' => _t('_sys_agents_assistants_field_kwg_faq_a'),
                        'value' => '',
                        'tr_attrs' => ['df' => '1', 'dt' => 'faq', 'style' => 'display:none']
                    ],
                    'submit' => [
                        'type' => 'input_set',
                        0 => [
                            'type' => 'submit',
                            'name' => 'do_submit',
                            'value' => _t('_sys_submit'),
                        ],
                        1 => [
                            'type' => 'reset',
                            'name' => 'close',
                            'value' => _t('_sys_close'),
                            'attrs' => ['class' => 'bx-def-margin-sec-left', 'onclick' => '$(\'.bx-popup-applied:visible\').dolPopupHide();'],
                        ],
                    ],
                ];
                break;
        }

        return $aForm;
    }
}
