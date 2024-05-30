<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioAgentsProviders extends BxDolStudioAgentsProviders
{
    protected $_sUrlPage;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sUrlPage = BX_DOL_URL_STUDIO . 'agents.php?page=providers';
    }

    public function getPageJsObject()
    {
        return 'oBxDolStudioPageAgents';
    }

    public function performActionAdd()
    {
        $sAction = 'add';

        $aProvider = [];
        if(($iTypeId = bx_get('type_id')) !== false && $iTypeId > 0) {
            $aProvider = [
                'type_id' => (int)$iTypeId,
                'options' => $this->_oDb->getProviderOptionsBy(['sample' => 'provider_type_id', 'provider_type_id' => $iTypeId])
            ];
        }

        $aForm = $this->_getForm($sAction, $aProvider);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = ['profile_id' => bx_get_logged_profile_id(), 'added' => time(), 'active' => 1];

            if(($iId = $oForm->insert($aValsToAdd)) !== false) {
                if(!empty($aProvider['options']) && is_array($aProvider['options']))
                    foreach($aProvider['options'] as $aOption)
                        $this->_oDb->insertProviderValue([
                            'provider_id' => $iId, 
                            'option_id' => $aOption['id'],
                            'value' => $oForm->getCleanValue($aOption['name'])
                        ]);

                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            }
            else
                $aRes = ['msg' => _t('_sys_txt_error_occured')];

            return echoJson($aRes);
        }

        $sFormId = $oForm->getId();
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup_' . $sAction, _t('_sys_agents_providers_popup_add'), $this->_oTemplate->parseHtmlByName('agents_provider_form.html', [
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

        $oAIProvider = BxDolAI::getInstance()->getProviderObject($iId);
        if(!$oAIProvider)
            return echoJson([]);

        $aProviderInfo = $oAIProvider->getInfo();

        $aForm = $this->_getFormEdit($sAction, $aProviderInfo);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($iId) === false)
                return echoJson(['msg' => _t('_sys_txt_error_occured')]);

            if(!empty($aProviderInfo['options']) && is_array($aProviderInfo['options']))
                foreach($aProviderInfo['options'] as $aOption)
                    $this->_oDb->updateProviderValue([
                        'value' => $oForm->getCleanValue($aOption['name'])
                    ], [
                        'provider_id' => $iId, 
                        'option_id' => $aOption['id'],
                    ]);

            return echoJson(['grid' => $this->getCode(false), 'blink' => $iId]);
        } 

        $sFormId = $oForm->getId();
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup_' . $sAction, _t('_sys_agents_providers_popup_edit'), $this->_oTemplate->parseHtmlByName('agents_provider_form.html', [
            'form_id' => $sFormId,
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    public function performActionInfo()
    {
        $sAction = 'view';

        $iId = $this->_getId();

        $oAIProvider = BxDolAI::getInstance()->getProviderObject($iId);
        if(!$oAIProvider)
            return echoJson([]);

        $aProviderInfo = $oAIProvider->getInfo();

        $aForm = $this->_getFormView($sAction, $aProviderInfo);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker($aProviderInfo);

        $sFormId = $oForm->getId();
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup_' . $sAction, _t('_sys_agents_providers_popup_view'), $this->_oTemplate->parseHtmlByName('agents_provider_form.html', [
            'form_id' => $oForm->getId(),
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => true, 'removeOnClose' => true]]]);
    }

    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();

        $this->_oTemplate->addJs(['jquery.form.min.js', 'agents_automators.js']);

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
        $mixedResult = parent::_delete($mixedId);
        if($mixedResult)
            $this->_oDb->deleteProviderValues(['provider_id' => (int)$mixedId]);

        return $mixedResult;
    }

    protected function _getFormView($sAction, $aProvider = [])
    {
        $aForm = $this->_getForm($sAction, $aProvider);
        $aForm['params']['view_mode'] = 1;

        return $aForm;
    }
    
    protected function _getFormEdit($sAction, $aProvider = [])
    {
        $aForm = $this->_getForm($sAction, $aProvider);
        $aForm['form_attrs']['action'] .= '&id=' . $aProvider['id'];

        return $aForm;
    }

    protected function _getForm($sAction, $aProvider = [])
    {
        $bProvider = !empty($aProvider) && is_array($aProvider);

        $aForm = [
            'form_attrs' => [
                'id' => 'bx_std_agents_providers_' . $sAction,
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=sys_studio_agents_providers&a=' . $sAction,
                'method' => 'post',
            ],
            'params' => [
                'db' => [
                    'table' => 'sys_agents_providers',
                    'key' => 'id',
                    'submit_name' => 'do_submit',
                ],
            ],
            'inputs' => [
                'controls' => [
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

            ],
        ];
                    
        if($sAction == 'add' && !$bProvider) {
            $aForm['inputs'] = bx_array_insert_before([
                'type_id' => [
                    'type' => 'select',
                    'name' => 'type_id',
                    'caption' => _t('_sys_agents_providers_field_type_id'),
                    'info' => '',
                    'value' => isset($aProvider['type_id']) ? $aProvider['type_id'] : 0,
                    'values' => array_map(function($sTitle) {
                        return _t($sTitle);
                    }, ['' => '_sys_please_select'] + $this->_oDb->getProviderTypesBy(['sample' => 'all_pairs'])),
                    'required' => '1',
                    'db' => [
                        'pass' => 'Int',
                    ]
                ],
            ], $aForm['inputs'], 'controls');

            $aForm['inputs']['controls'][0] = array_merge($aForm['inputs']['controls'][0], [
                'name' => 'do_select',
                'value' => _t('_sys_select')
            ]);
        }
        else {
            $aInputsAdd = [
                'type_id' => [
                    'type' => 'hidden',
                    'name' => 'type_id',
                    'value' => isset($aProvider['type_id']) ? $aProvider['type_id'] : 0,
                    'db' => [
                        'pass' => 'Int',
                    ]
                ],
                'title' => [
                    'type' => 'text',
                    'name' => 'title',
                    'caption' => _t('_sys_agents_providers_field_title'),
                    'value' => isset($aProvider['title']) ? $aProvider['title'] : '',
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
            ];

            if(!empty($aProvider['options']) && is_array($aProvider['options']))
                foreach($aProvider['options'] as $aOption) {
                    if($aOption['type'] == 'value' && empty($aOption['value']))
                        continue;

                    $aInputsAdd[$aOption['name']] = [
                        'type' => $aOption['type'],
                        'name' => $aOption['name'],
                        'caption' => _t($aOption['title']),
                        'info' => _t($aOption['description']),
                        'value' => isset($aOption['value']) ? $aOption['value'] : '',
                        'checker' => !empty($aOption['check_type']) ? [
                            'func' => $aOption['check_type'],
                            'params' => !empty($aOption['check_params']) ? unserialize($aOption['check_params']) : '',
                            'error' => _t($aOption['check_error']),
                        ] : null,
                    ];
                }

            $aForm['inputs'] = bx_array_insert_before($aInputsAdd, $aForm['inputs'], 'controls');
        }

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
