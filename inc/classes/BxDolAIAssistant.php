<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

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

    public function getModelObject()
    {
        return BxDolAI::getInstance()->getModelObject($this->_aData['model_id']);
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
