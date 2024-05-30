<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioAgentsAutomators extends BxDolStudioAgentsAutomators
{
    protected $_sUrlPage;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sUrlPage = BX_DOL_URL_STUDIO . 'agents.php?page=automators';
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
            $aValsToAdd = ['added' => time()];

            $iProfileId = $oForm->getCleanValue('profile_id');
            if(empty($iProfileId)) {
                $iProfileId = (int)getParam('sys_agents_profile');
                if(empty($iProfileId))
                    $iProfileId = current(bx_srv('system', 'get_options_agents_profile', [false], 'TemplServices'))['key'];

                $aValsToAdd['profile_id'] = $iProfileId;
            }

            $sSchedulerTime = $oForm->getCleanValue('scheduler_time');
            if(!empty($sSchedulerTime))
                $aValsToAdd['params'] = json_encode(['scheduler_time' => $sSchedulerTime]);

            $oAI = BxDolAI::getInstance();
  
            $iModel = $oForm->getCleanValue('model_id');
            $sType = $oForm->getCleanValue('type');
            $aProviders = $oForm->getCleanValue('providers');
            $sMessage = $oForm->getCleanValue('message');
            if(!empty($aProviders) && is_array($aProviders))
                $sMessage .= $oAI->getAutomatorInstruction('providers', $aProviders);
            $bMessage = !empty($sMessage);
            $sMessageAdd = '';
            $sMessageResponse = '';

            $oAIModel = $oAI->getModelObject($iModel);

            $bIsValid = true;
            $aResponseInit = [];
            switch($sType) {
                case BX_DOL_AI_AUTOMATOR_EVENT:
                    if(($aResponseInit = $oAIModel->getResponseInit($sType, $sMessage)) !== false) {
                        $oAIModel->setParams($aResponseInit['params']);

                        $sMessageAdd = $aResponseInit['params']['trigger'];

                        $aValsToAdd = array_merge($aValsToAdd, $aResponseInit);
                        if(!empty($aValsToAdd['params']) && is_array($aValsToAdd['params']))
                            $aValsToAdd['params'] = json_encode($aValsToAdd['params']);
                    }
                    else {
                        $oForm->aInputs['message']['error'] = _t('_sys_agents_automators_err_event_not_found');
                        $bIsValid = false;
                    }
                    break;

                case BX_DOL_AI_AUTOMATOR_SCHEDULER:
                case BX_DOL_AI_AUTOMATOR_WEBHOOK:
                    if(($aResponseInit = $oAIModel->getResponseInit($sType, $sMessage)) !== false) {
                        $oAIModel->setParams($aResponseInit['params']);

                        $aValsToAdd = array_merge($aValsToAdd, $aResponseInit);
                        if(!empty($aValsToAdd['params']) && is_array($aValsToAdd['params']))
                            $aValsToAdd['params'] = json_encode($aValsToAdd['params']);
                    }
                    else {
                        $oForm->aInputs['message']['error'] = _t('_sys_agents_automators_err_event_not_found');
                        $bIsValid = false;
                    }
                    break;
            }

            if($bIsValid) {
                if(($sResponse = $oAIModel->getResponse($sType, $sMessage . $sMessageAdd, $oAIModel->getParams())) !== false) {
                    $sMessageResponse = $sResponse;
                }
                else {
                    $oForm->aInputs['message']['error'] = _t('_sys_agents_automators_err_cannot_get_code');
                    $bIsValid = false;
                }
            }

            if($bIsValid) {
                if(($iId = $oForm->insert($aValsToAdd)) !== false) {
                    $aProviders = $oForm->getCleanValue('providers');
                    if(!empty($aProviders) && is_array($aProviders))
                        foreach($aProviders as $iProviderId)
                            $this->_oDb->insertAutomatorProvider([
                                'automator_id' => $iId, 
                                'provider_id' => $iProviderId
                            ]);

                    if(($oCmts = BxDolCmts::getObjectInstance($this->_sCmts, $iId)) !== null) {
                        if($bMessage) {
                            $aResult = $oCmts->addAuto([
                                'cmt_author_id' => bx_get_logged_profile_id(),
                                'cmt_parent_id' => 0,
                                'cmt_text' => $sMessage
                            ]);

                            if(!empty($aResult['id']))
                                $this->_oDb->updateAutomators(['message_id' => $aResult['id']], ['id' => $iId]);
                        }

                        if($sMessageResponse) {
                            sleep(1);

                            $oCmts->addAuto([
                                'cmt_author_id' => $this->_iProfileIdAi,
                                'cmt_parent_id' => 0,
                                'cmt_text' => $sMessageResponse
                            ]);
                        }
                    }

                    $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
                }
                else
                    $aRes = ['msg' => _t('_sys_txt_error_occured')];

                return echoJson($aRes);
            }
        }

        $sFormId = $oForm->getId();
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup', _t('_sys_agents_automators_popup_add'), $this->_oTemplate->parseHtmlByName('agents_automator_form.html', [
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
        $aAutomator = $this->_oDb->getAutomatorsBy(['sample' => 'id', 'id' => $iId]);
        $aAutomator['providers'] = $this->_oDb->getAutomatorsBy(['sample' => 'providers_by_id_pairs', 'id' => $iId]);

        $aForm = $this->_getFormEdit($sAction, $aAutomator);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = [];

            $aProvidersIds = $oForm->getCleanValue('providers_ids');
            $bProvidersIds = !empty($aProvidersIds) && is_array($aProvidersIds);
            $aProvidersValues = $oForm->getCleanValue('providers');
            $bProvidersValues = !empty($aProvidersValues) && is_array($aProvidersValues);

            //--- Remove deleted
            if(!empty($aAutomator['providers']) && is_array($aAutomator['providers']))
                $this->_oDb->deleteAutomatorProvidersById(array_diff(array_keys($aAutomator['providers']), $aProvidersIds));

            //--- Update existed
            if($bProvidersIds)
                foreach($aProvidersIds as $iIndex => $iApId)
                    $this->_oDb->updateAutomatorProvider(['provider_id' => (int)$aProvidersValues[$iIndex]], ['id' => (int)$iApId]);

            //--- Add new
            $iProvidersIds = $bProvidersIds ? count($aProvidersIds) : 0;
            $iProvidersValues = $bProvidersValues ? count($aProvidersValues) : 0;
            if($iProvidersValues > $iProvidersIds) {
                $aProvidersValues = array_slice($aProvidersValues, $iProvidersIds);
                foreach($aProvidersValues as $iProvidersValue)
                    $this->_oDb->insertAutomatorProvider([
                        'automator_id' => $iId,
                        'provider_id' => (int)$iProvidersValue,
                    ]);
            }

            $iProfileId = $oForm->getCleanValue('profile_id');
            if(empty($iProfileId)) {
                $iProfileId = (int)getParam('sys_agents_profile');
                if(empty($iProfileId))
                    $iProfileId = current(bx_srv('system', 'get_options_agents_profile', [false], 'TemplServices'))['key'];

                $aValsToAdd['profile_id'] = $iProfileId;
            }

            $sSchedulerTime = $oForm->getCleanValue('scheduler_time');
            if(!empty($sSchedulerTime))
                $aValsToAdd['params'] = json_encode(['scheduler_time' => $sSchedulerTime]);

            if($oForm->update($iId, $aValsToAdd) !== false)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            else
                $aRes = ['msg' => _t('_sys_txt_error_occured')];

            return echoJson($aRes);
        } 

        $sFormId = $oForm->getId();
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup', _t('_sys_agents_automators_popup_edit'), $this->_oTemplate->parseHtmlByName('agents_automator_form.html', [
            'form_id' => $sFormId,
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    protected function _getCellSwitcher ($mixedValue, $sKey, $aField, $aRow)
    {
        if(empty($aRow['code']) || $aRow['status'] != BX_DOL_AI_AUTOMATOR_STATUS_READY)
            return parent::_getCellDefault('', $sKey, $aField, $aRow);

        return parent::_getCellSwitcher ($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellModelId($mixedValue, $sKey, $aField, $aRow)
    {
        $aModel = $this->_oDb->getModelsBy(['sample' => 'id', 'id' => $mixedValue]);
        if(!empty($aModel) && is_array($aModel))
            $mixedValue = $aModel['title'];

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellProfileId($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(BxDolProfile::getInstanceMagic($mixedValue)->getDisplayName(), $sKey, $aField, $aRow);
    }

    protected function _getCellType($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t('_sys_agents_automators_field_type_' . $mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellData($sKey, $aField, $aRow)
    {
        if($sKey == 'message_id' && ($iCmtId = (int)$aRow[$sKey]) != 0 && ($oCmts = BxDolCmts::getObjectInstance($this->_sCmts, $aRow['id'])) !== null) {
            $aCmt = $oCmts->getCommentSimple($iCmtId);
            if(!empty($aCmt) && is_array($aCmt))
                $aRow[$sKey] = $aCmt['cmt_text'];
        }

        return parent::_getCellData($sKey, $aField, $aRow);
    }

    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellStatus($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t('_sys_agents_automators_field_status_' . $mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getActionTune($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = [])
    {
    	$a['attr'] = array_merge($a['attr'], [
            "onclick" => "window.open('" . $this->_sUrlPage . '&id=' . $aRow['id'] . "', '_self');"
    	]);

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
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
            $this->_oDb->deleteAutomatorProviders(['automator_id' => (int)$mixedId]);

        return $mixedResult;
    }

    protected function _getFormEdit($sAction, $aAutomator = [])
    {
        $aForm = $this->_getForm($sAction, $aAutomator);
        $aForm['form_attrs']['action'] .= '&id=' . $aAutomator['id'];

        unset($aForm['inputs']['type']);
        unset($aForm['inputs']['message']);

        return $aForm;
    }

    protected function _getForm($sAction, $aAutomator = [])
    {
        $sJsObject = $this->getPageJsObject();

        $sType = isset($aAutomator['type']) ? $aAutomator['type'] : 'event';

        if(!empty($aAutomator['params']))
            $aAutomator['params'] = json_decode($aAutomator['params'], true);

        $aForm = array(
            'form_attrs' => array(
                'id' => 'bx_std_agents_automators_' . $sAction,
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=sys_studio_agents_automators&a=' . $sAction,
                'method' => 'post',
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_agents_automators',
                    'key' => 'id',
                    'submit_name' => 'do_submit',
                ),
            ),
            'inputs' => array(
                'model_id' => [
                    'type' => 'select',
                    'name' => 'model_id',
                    'caption' => _t('_sys_agents_automators_field_model_id'),
                    'info' => '',
                    'value' => isset($aAutomator['model_id']) ? $aAutomator['model_id'] : BxDolAI::getInstance()->getDefaultModel(),
                    'values' => $this->_oDb->getModelsBy(['sample' => 'all_pairs']),
                    'required' => '1',
                    'db' => [
                        'pass' => 'Int',
                    ]
                ],
                'profile_id' => [
                    'type' => 'select',
                    'name' => 'profile_id',
                    'caption' => _t('_sys_agents_automators_field_profile_id'),
                    'info' => _t('_sys_agents_automators_field_profile_id_inf'),
                    'value' => isset($aAutomator['profile_id']) ? $aAutomator['profile_id'] : 0,
                    'values' => bx_srv('system', 'get_options_agents_profile', [], 'TemplServices'),
                    'required' => '0',
                    'db' => [
                        'pass' => 'Int',
                    ]
                ],
                'type' => [
                    'type' => 'select',
                    'name' => 'type',
                    'caption' => _t('_sys_agents_automators_field_type'),
                    'info' => '',
                    'value' => $sType,
                    'values' => [
                        'event' => _t('_sys_agents_automators_field_type_event'),
                        'scheduler' => _t('_sys_agents_automators_field_type_scheduler'),
                        'webhook' => _t('_sys_agents_automators_field_type_webhook'),
                    ],
                    'attrs' => [
                        'onchange' => $this->getPageJsObject() . '.onChangeAutomatorType(this)',
                    ],
                    'required' => '1',
                    'db' => [
                        'pass' => 'Xss',
                    ]
                ],
                'alert_unit' => [
                    'type' => 'hidden',
                    'name' => 'alert_unit',
                    'caption' => _t('_sys_agents_automators_field_alert_unit'),
                    'value' => isset($aAutomator['alert_unit']) ? $aAutomator['alert_unit'] : '',
                    'tr_attrs' => [
                        'style' => $sType != 'event' ? 'display:none' : ''
                    ],
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'alert_action' => [
                    'type' => 'hidden',
                    'name' => 'alert_action',
                    'caption' => _t('_sys_agents_automators_field_alert_action'),
                    'value' => isset($aAutomator['alert_action']) ? $aAutomator['alert_action'] : '',
                    'tr_attrs' => [
                        'style' => $sType != 'event' ? 'display:none' : ''
                    ],
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'scheduler_time' => [
                    'type' => 'hidden',
                    'name' => 'scheduler_time',
                    'caption' => _t('_sys_agents_automators_field_scheduler_time'),
                    'value' => isset($aAutomator['params']['scheduler_time']) ? $aAutomator['params']['scheduler_time'] : '',
                    'tr_attrs' => [
                        'style' => $sType != 'scheduler' ? 'display:none' : ''
                    ],
                ],
                'providers' => [
                    'type' => 'custom',
                    'name' => 'providers',
                    'caption' => _t('_sys_agents_automators_field_providers'),
                    'value' => '',
                ],
                'message' => [
                    'type' => 'textarea',
                    'name' => 'message',
                    'caption' => _t('_sys_agents_automators_field_message'),
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
        
        if($aForm['inputs']['providers']) {
            $oForm = new BxTemplFormView([]);

            $sProviders = 'providers';
            $aProviders = array_map(function($sTitle) {
                return _t($sTitle);
            }, ['' => '_sys_please_select'] + $this->_oDb->getProviderBy(['sample' => 'all_pairs', 'active' => 1]));

            $aTmplVarsProviders = array();
            if(!empty($aAutomator['providers']) && is_array($aAutomator['providers'])) {
                foreach($aAutomator['providers'] as $iApId => $iProviderId) {
                    $aInputSelect = [
                        'type' => 'select',
                        'name' => 'providers[]',
                        'values' => $aProviders,
                        'value' => (int)$iProviderId,
                        'attrs' => [
                            'class' => 'bx-def-margin-sec-top-auto'
                        ]
                    ];
                    $sInput = $oForm->genInput($aInputSelect);

                    $aInputHidden = [
                        'type' => 'hidden',
                        'name' => 'providers_ids[]',
                        'value' => (int)$iApId,
                    ];
                    $sInput .= $oForm->genInput($aInputHidden);

                    $aTmplVarsProviders[] = ['js_object' => $sJsObject, 'input_select' => $sInput];
                }
            }
            else  {
                $aInputSelect = [
                    'type' => 'select',
                    'name' => 'providers[]',
                    'values' => $aProviders,
                    'value' => '',
                    'attrs' => [
                        'class' => 'bx-def-margin-sec-top-auto'
                    ]
                ];

                $aTmplVarsProviders = [
                    ['js_object' => $sJsObject, 'input_select' => $oForm->genInput($aInputSelect)],
                ];
            }
        
            $aInputButton = [
                'type' => 'button',
                'name' => 'providers_add',
                'value' => _t('_sys_agents_automators_field_providers_add'),
                'attrs' => [
                    'class' => 'bx-def-margin-sec-top',
                    'onclick' => $sJsObject . ".providerAdd(this, '" . $sProviders . "');"
                ]
            ];

            $aForm['inputs']['providers']['content'] = $this->_oTemplate->parseHtmlByName('agents_automator_form_providers.html', [
                'bx_repeat:providers' => $aTmplVarsProviders,
                'btn_add' => $oForm->genInputButton($aInputButton)
            ]);
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
