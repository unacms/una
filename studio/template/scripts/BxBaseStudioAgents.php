<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioAgents extends BxDolStudioAgents
{
    protected $sSubpageUrl;
    protected $aPageJsOptions;
    protected $aMenuItems;
    protected $aGridObjects;

    public function __construct($sPage = '')
    {
        parent::__construct($sPage);

        $this->sSubpageUrl = BX_DOL_URL_STUDIO . 'agents.php?page=';

        $this->aPageJs = array_merge($this->aPageJs, ['agents.js']);
        $this->aPageCss = array_merge($this->aPageCss, ['cmts.css', 'agents.css']);

        $this->sPageJsClass = 'BxDolStudioPageAgents';
        $this->sPageJsObject = 'oBxDolStudioPageAgents';
        $this->aPageJsOptions = [
            'sActionUrl' => BX_DOL_URL_STUDIO . 'agents.php',
            'sPageUrl' => $this->sSubpageUrl
        ];

        $this->aMenuItems = [
            BX_DOL_STUDIO_AGENTS_TYPE_SETTINGS => ['icon' => 'mi-cog.svg', 'icon_bg' => true],
            BX_DOL_STUDIO_AGENTS_TYPE_PROVIDERS => ['icon' => 'mi-agt-providers.svg', 'icon_bg' => true],
            BX_DOL_STUDIO_AGENTS_TYPE_HELPERS => ['icon' => 'mi-agt-helpers.svg', 'icon_bg' => true],
            BX_DOL_STUDIO_AGENTS_TYPE_ASSISTANTS => ['icon' => 'mi-agt-assistants.svg', 'icon_bg' => true],
            BX_DOL_STUDIO_AGENTS_TYPE_AUTOMATORS => ['icon' => 'mi-agt-automators.svg', 'icon_bg' => true],
        ];

        $this->aGridObjects = [
            BX_DOL_STUDIO_AGENTS_TYPE_AUTOMATORS => 'sys_studio_agents_automators',
            BX_DOL_STUDIO_AGENTS_TYPE_PROVIDERS => 'sys_studio_agents_providers',
            BX_DOL_STUDIO_AGENTS_TYPE_ASSISTANTS => 'sys_studio_agents_assistants',
            BX_DOL_STUDIO_AGENTS_TYPE_ASSISTANTS . '_chats' => 'sys_studio_agents_assistants_chats',
            BX_DOL_STUDIO_AGENTS_TYPE_ASSISTANTS . '_files' => 'sys_studio_agents_assistants_files',
            BX_DOL_STUDIO_AGENTS_TYPE_HELPERS => 'sys_studio_agents_helpers',
        ];
    }

    public function getPageJsCode($aOptions = [], $bWrap = true)
    {
        return parent::getPageJsCode(array_merge($aOptions, $this->aPageJsOptions), $bWrap);
    }

    public function getPageMenu($aMenu = [], $aMarkers = [])
    {
        $sJsObject = $this->getPageJsObject();

        $aMenu = [];
        foreach($this->aMenuItems as $sMenuItem => $aItem)
            $aMenu[] = array_merge($aItem, [
                'name' => $sMenuItem,
                'link' => $this->sSubpageUrl . $sMenuItem,
                'title' => _t('_adm_lmi_cpt_' . $sMenuItem),
                'selected' => $sMenuItem == $this->sPage
            ]);

        return parent::getPageMenu($aMenu);
    }

    protected function getSettings()
    {
        $oOptions = new BxTemplStudioOptions(BX_DOL_STUDIO_STG_TYPE_DEFAULT, [
            'agents_general',
        ]);

        $this->aPageCss = array_merge($this->aPageCss, $oOptions->getCss());
        $this->aPageJs = array_merge($this->aPageJs, $oOptions->getJs());

        return $oOptions->getCode();
    }

    protected function getAutomators()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $this->aPageJsOptions['sPageUrl'] .= 'automators';

        if(($iId = bx_get('id')) !== false) {
            $this->aPageJsOptions = array_merge($this->aPageJsOptions, [
                'sPageUrl' => $this->sSubpageUrl . 'automators&id=' . $iId,
                'sActionUrlCmts' => bx_append_url_params(BX_DOL_URL_ROOT . 'cmts.php', [
                    'sys' => $sCmts,
                    'id' => $iId
                ])
            ]);

            if(($oCmts = BxDolAI::getInstance()->getAutomatorCmtsObject($iId, $oTemplate)) !== false)
                return $oCmts->getCommentsBlock();
            else
                return MsgBox(_t('_error occured'));
        }

        return $this->getGrid($this->aGridObjects[BX_DOL_STUDIO_AGENTS_TYPE_AUTOMATORS]);
    }
    
    protected function getHelpers()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();
        
        $this->aPageJsOptions['sPageUrl'] .= 'helpers';

        if(($iId = bx_get('id')) !== false) {
            $this->aPageJsOptions = array_merge($this->aPageJsOptions, [
                'sPageUrl' => $this->sSubpageUrl . 'helpers&id=' . $iId,
            ]);
            
            $aHelper = BxDolAI::getInstance()->getHelperById($iId);

            $aForm = $this->_getHelpersForm('tune', $aHelper);
            $oForm = new BxTemplFormView($aForm);
            $oForm->initChecker();

            if($oForm->isSubmittedAndValid()) {
                if($oForm->update($iId) !== false) {
                    $sMessage = $oForm->getCleanValue('message');
                    $oForm->aInputs['result']['value'] = BxDolAI::callHelper($iId, $sMessage);
                }
            }

            return $oForm->getCode();
        }

        return $this->getGrid($this->aGridObjects[BX_DOL_STUDIO_AGENTS_TYPE_HELPERS]);
    }

    protected function getAssistants()
    {
        $oAi = BxDolAI::getInstance();
        $oTemplate = BxDolStudioTemplate::getInstance();
        
        $this->aPageJsOptions['sPageUrl'] .= 'assistants';

        $sSubPage = '';
        if(($sSubPage = bx_get('spage')) !== false)
            $sSubPage = bx_process_input($sSubPage, BX_DATA_TEXT);

        $iAssistantId = 0;
        if(($iAssistantId = bx_get('aid')) !== false)
            $iAssistantId = bx_process_input($iAssistantId, BX_DATA_INT);

        $iChatId = 0;
        if(($iChatId = bx_get('cid')) !== false)
            $iChatId = bx_process_input($iChatId, BX_DATA_INT);

        if($iAssistantId && $iChatId) {
            $sCmts = 'sys_agents_assistants_chats';

            $this->aPageJsOptions = array_merge($this->aPageJsOptions, [
                'sPageUrl' => $this->sSubpageUrl . 'assistants&aid=' . $iAssistantId . '&cid=' . $iChatId,
                'sActionUrlCmts' => bx_append_url_params(BX_DOL_URL_ROOT . 'cmts.php', [
                    'sys' => $sCmts,
                    'id' => $iChatId
                ])
            ]);

            if(($oCmts = $oAi->getAssistantChatCmtsObject($iChatId, $oTemplate)) !== false)
                return $oCmts->getCommentsBlock();
            else
                return MsgBox(_t('_error occured'));
        }
        else if($iAssistantId) {
            $aResult = [];

            $aAssistant = $oAi->getAssistantById($iAssistantId);
            if(!empty($aAssistant) && is_array($aAssistant))
                $aResult[] = $oTemplate->parseHtmlByName('agents_assistant_info.html', [
                    'assistant_name' => $aAssistant['name'],
                    'assistant_info' => $aAssistant['description'],
                    'bx_if:show_chat' => [
                        'condition' => false,
                        'content' => [
                            'chat_name' => '',
                            'chat_info' => '',
                        ]
                    ],
                    'url_back' => $this->aPageJsOptions['sPageUrl']
                ]);
            
            switch($sSubPage) {
                case 'chats':
                    $aResult[] = $this->getGrid($this->aGridObjects[BX_DOL_STUDIO_AGENTS_TYPE_ASSISTANTS . '_chats']);
                    break;

                case 'files':
                    $aResult[] = $this->getGrid($this->aGridObjects[BX_DOL_STUDIO_AGENTS_TYPE_ASSISTANTS . '_files']);
                    break;
            }

            return $aResult;
        }

        return $this->getGrid($this->aGridObjects[BX_DOL_STUDIO_AGENTS_TYPE_ASSISTANTS]);
    }

    protected function getProviders()
    {
        $this->aPageJsOptions = array_merge($this->aPageJsOptions, [
            'sPageUrl' => $this->sSubpageUrl . 'providers',
            'sActionUrlGrid' => bx_append_url_params(BX_DOL_URL_ROOT . 'grid.php', [
                'o' => 'sys_studio_agents_providers'
            ])
        ]);

        return $this->getGrid($this->aGridObjects[BX_DOL_STUDIO_AGENTS_TYPE_PROVIDERS]);
    }

    protected function getGrid($sObjectName, $bObject = false)
    {
        $oGrid = BxDolGrid::getObjectInstance($sObjectName);
        if(!$oGrid)
            return '';

        return $bObject ? $oGrid : $oGrid->getCode();
    }
    
    protected function _getHelpersForm($sAction, $aHelper = [])
    {
        $aForm = array(
            'form_attrs' => array(
                'id' => 'bx_std_agents_helpers_' . $sAction,
                'action' => $this->aPageJsOptions['sPageUrl'],
                'method' => 'post',
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_agents_helpers',
                    'key' => 'id',
                    'submit_name' => 'do_submit',
                ),
            ),
            'inputs' => [
                'prompt' => [
                    'type' => 'textarea',
                    'name' => 'prompt',
                    'caption' => _t('_sys_agents_helpers_field_prompt'),
                    'value' => isset($aHelper['prompt']) ? $aHelper['prompt'] : '',
                    'required' => '1',
                    'checker' => [
                        'func' => 'Avail',
                        'params' => [],
                        'error' => _t('_sys_agents_helpers_field_prompt_err'),
                    ],
                    'db' => [
                        'pass' => 'Xss',
                    ]
                ],
                'message' => [
                    'type' => 'textarea',
                    'name' => 'message',
                    'caption' => _t('_sys_agents_helpers_field_message'),
                    'value' => '',
                    'required' => '1',
                    'checker' => [
                        'func' => 'Avail',
                        'params' => [],
                        'error' => _t('_sys_agents_helpers_field_message_err'),
                    ],
                ],
                'result' => [
                    'type' => 'textarea',
                    'name' => 'result',
                    'caption' => _t('_sys_agents_helpers_field_result'),
                    'value' => '',
                    'attrs' => [
                        'disabled' => 'disabled'
                    ]
                ],
                'submit' => [
                    'type' => 'submit',
                    'name' => 'do_submit',
                    'value' => _t('_sys_submit'),
                ],
            ],
        );

        return $aForm;
    }
}

/** @} */
