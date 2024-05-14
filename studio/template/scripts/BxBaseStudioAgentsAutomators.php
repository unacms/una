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

    public function getJsObject()
    {
        return 'oBxDolStudioAgentsAutomators';
    }

    public function performActionAdd()
    {
        $sAction = 'add';

        $aForm = $this->_getForm($sAction);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = [];
            
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

            $sMessage = $oForm->getCleanValue('message');
            $bMessage = !empty($sMessage);
            $sMessageAdd = '';
            $sMessageResponse = '';

            if($bMessage)
                $aValsToAdd['messages'] = 1;

            $oAI = BxDolAI::getInstance();

            $iModel = $oForm->getCleanValue('model_id');
            $aModel = $this->_oDb->getModelsBy(['sample' => 'id', 'id' => $iModel]);
            if(!empty($aModel['params']))
                $aModel['params'] = json_decode($aModel['params'], true);

            $bIsValid = true;
            $sType = $oForm->getCleanValue('type');
            switch($sType) {
                case 'event':
                    $aMessages = [
                        ['role' => 'system', 'content' => file_get_contents(BX_DIRECTORY_PATH_ROOT. '/ai_alert_instructions.html' )],
                        ['role' => 'user', 'content' => $sMessage]
                    ];

                    $sResponse = $oAI->chat($aModel['url'], $aModel['model'], $aModel['key'], $aModel['params'], $aMessages);
                    if(trim($sResponse) != 'false') {
                        $sResponse = str_replace(['```json', '```'], '', $sResponse);
                        $aResponse = json_decode($sResponse, true);
                        $aValsToAdd = array_merge($aValsToAdd, [
                            'alert_unit' => $aResponse['alert_unit'],
                            'alert_action' => $aResponse['alert_action']
                        ]);
                        $sMessageAdd = $aResponse['trigger'];
                    }
                    else {
                        $oForm->aInputs['message']['err'] = _t('_sys_agents_automators_err_event_not_found');
                        $bIsValid = false;
                    }
                    break;

                case 'scheduler':
                    $aMessages = [
                        ['role' => 'system', 'content' => file_get_contents(BX_DIRECTORY_PATH_ROOT. '/ai_cron_instructions.html' )],
                        ['role' => 'user', 'content' => $sMessage]
                    ];

                    $sResponse = $oAI->chat($aModel['url'], $aModel['model'], $aModel['key'], $aModel['params'], $aMessages);
                    if(trim($sResponse) != 'false') {
                        $aValsToAdd['params'] = json_encode(['scheduler_time' => $aResponse]);
                    }
                    else {
                        $oForm->aInputs['message']['err'] = _t('_sys_agents_automators_err_event_not_found');
                        $bIsValid = false;
                    }
                    break;
            }

            if($bIsValid) {
                $aMessages = [
                    ['role' => 'system', 'content' => file_get_contents(BX_DIRECTORY_PATH_ROOT. '/ai_' . $sType . '_instructions.html' ) . file_get_contents(BX_DIRECTORY_PATH_ROOT. '/ai_common_instructions.html' )],
                    ['role' => 'user', 'content' => $sMessage . $sMessageAdd]
                ];

                $sResponse = $oAI->chat($aModel['url'], $aModel['model'], $aModel['key'], $aModel['params'], $aMessages);
                if(trim($sResponse) != 'false') {
                    $sResponse = str_replace(['```php', '```'], '', $sResponse);
                    $sMessageResponse = $sResponse;
                }
                else {
                    $oForm->aInputs['message']['err'] = _t('_sys_agents_automators_err_cannot_get_code');
                    $bIsValid = false;
                }
            }

            if($bIsValid) {
                if(($iId = $oForm->insert($aValsToAdd)) !== false) {
                    if(($oCmts = BxDolCmts::getObjectInstance($this->_sCmts, $iId)) !== null) {
                        if($bMessage)
                            $oCmts->addAuto([
                                'cmt_author_id' => bx_get_logged_profile_id(),
                                'cmt_parent_id' => 0,
                                'cmt_text' => $sMessage
                            ]);

                        if($sMessageResponse) {
                            sleep(1);

                            $oCmts->addAuto([
                                'cmt_author_id' => $this->_iProfileAi,
                                'cmt_parent_id' => 0,
                                'cmt_text' => $sMessageResponse
                            ]);

                            $aValsToAdd['messages']++;
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

        $aForm = $this->_getFormEdit($sAction, $aAutomator);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = [];
            
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

            if($oForm->update($iId, $aValsToAdd))
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

    public function getCode($isDisplayHeader = true)
    {
        return $this->_oTemplate->parseHtmlByName('agents_automator.html', [
            'content' => parent::getCode($isDisplayHeader),
            'js_object' => $this->getJsObject(),
            'page_url' => $this->_sUrlPage,
            'grid_object' => $this->_sObject,
            'params_divider' => $this->sParamsDivider
        ]);
    }

    protected function _getCellModelId($mixedValue, $sKey, $aField, $aRow)
    {
        $aModel = $this->_oDb->getModelsBy(['sample' => 'id', 'id' => $mixedValue]);
        if(!empty($aModel) && is_array($aModel))
            $mixedValue = $aModel['name'];

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

    protected function _getFormEdit($sAction, $aAutomator = [])
    {
        $aForm = $this->_getForm($sAction, $aAutomator);
        $aForm['form_attrs']['action'] .= '&id=' . $aAutomator['id'];

        unset($aForm['inputs']['message']);

        return $aForm;
    }

    protected function _getForm($sAction, $aAutomator = [])
    {
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
                    'value' => isset($aAutomator['model_id']) ? $aAutomator['model_id'] : 0,
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
                        'onchange' => $this->getJsObject() . '.onChangeType(this)',
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
