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
    protected $_sFieldName;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sUrlPage = BX_DOL_URL_STUDIO . 'agents.php?page=automators';

        $this->_sFieldName = 'name';
    }

    public function getPageJsObject()
    {
        return 'oBxDolStudioPageAgents';
    }

    public function performActionAdd()
    {
        $sAction = 'add';

        $oAI = BxDolAI::getInstance();

        $aForm = $this->_getForm($sAction);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = [
                'added' => time()
            ];

            $sName = $oForm->getCleanValue($this->_sFieldName);
            $sName = $this->_getAutomatorName($sName);
            BxDolForm::setSubmittedValue($this->_sFieldName, $sName, $oForm->aFormAttrs['method']);

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
  
            $iModel = $oForm->getCleanValue('model_id');
            $sType = $oForm->getCleanValue('type');
            $aProviders = $oForm->getCleanValue('providers');
            $bProviders = !empty($aProviders) && is_array($aProviders);
            $aHelpers = $oForm->getCleanValue('helpers');
            $bHelpers = !empty($aHelpers) && is_array($aHelpers);
            $aAssistants = $oForm->getCleanValue('assistants');
            $bAssistants = !empty($aAssistants) && is_array($aAssistants);
            $sMessage = $oForm->getCleanValue('message');
            $bMessage = !empty($sMessage);
            $sMessageAdd = '';
            $sMessageResponse = '';

            $oAIModel = $oAI->getModelObject($iModel);

            $sInstructions = $oAI->getAutomatorInstruction('profile', $iProfileId);
            if($bProviders)
                $sInstructions .= $oAI->getAutomatorInstruction('providers', $aProviders);
            if($bHelpers)
                $sInstructions .= $oAI->getAutomatorInstruction('helpers', $aHelpers);
            if($bAssistants)
                $sInstructions .= $oAI->getAutomatorInstruction('assistants', $aAssistants);

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
                if(($sResponse = $oAIModel->getResponse($sType, $sMessage . $sInstructions . $sMessageAdd, $oAIModel->getParams())) !== false) {
                    $sMessageResponse = $sResponse;
                }
                else {
                    $oForm->aInputs['message']['error'] = _t('_sys_agents_automators_err_cannot_get_code');
                    $bIsValid = false;
                }
            }

            if($bIsValid) {
                if(($iId = $oForm->insert($aValsToAdd)) !== false) {
                    if($bProviders)
                        foreach($aProviders as $iProviderId)
                            $this->_oDb->insertAutomatorProvider([
                                'automator_id' => $iId, 
                                'provider_id' => $iProviderId
                            ]);

                    if($bHelpers)
                        foreach($aHelpers as $iHelperId)
                            $this->_oDb->insertAutomatorHelper([
                                'automator_id' => $iId, 
                                'helper_id' => $iHelperId
                            ]);

                    if($bAssistants)
                        foreach($aAssistants as $iAssistantId)
                            $this->_oDb->insertAutomatorAssistant([
                                'automator_id' => $iId, 
                                'assistant_id' => $iAssistantId
                            ]);

                    if(($oCmts = BxDolCmts::getObjectInstance($this->_sCmts, $iId)) !== null) {
                        if($bMessage) {
                            $iProfileId = bx_get_logged_profile_id();

                            $aResult = $oCmts->addAuto([
                                'cmt_author_id' => $iProfileId,
                                'cmt_parent_id' => 0,
                                'cmt_text' => $sMessage
                            ]);

                            if(!empty($aResult['id']))
                                $this->_oDb->updateAutomators(['message_id' => $aResult['id']], ['id' => $iId]);

                            sleep(1);
                            $oCmts->addAuto([
                                'cmt_author_id' => $iProfileId,
                                'cmt_parent_id' => 0,
                                'cmt_text' => $sInstructions
                            ]);
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

        $oAI = BxDolAI::getInstance();

        $iId = $this->_getId();
        $aAutomator = $oAI->getAutomator($iId);
        $aAutomator['providers'] = $this->_oDb->getAutomatorsBy(['sample' => 'providers_by_id_pairs', 'id' => $iId]);
        $aAutomator['helpers'] = $this->_oDb->getAutomatorsBy(['sample' => 'helpers_by_id_pairs', 'id' => $iId]);

        $aForm = $this->_getFormEdit($sAction, $aAutomator);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = [];

            $sName = $oForm->getCleanValue($this->_sFieldName);
            if($aAutomator[$this->_sFieldName] != $sName) {
                $sName = $this->_getAutomatorName($sName);
                BxDolForm::setSubmittedValue($this->_sFieldName, $sName, $oForm->aFormAttrs['method']);
            }

            /**
             * Process Providers
             */
            $aProvidersIds = $oForm->getCleanValue('providers_ids');
            $bProvidersIds = !empty($aProvidersIds) && is_array($aProvidersIds);
            $aProvidersValues = $oForm->getCleanValue('providers');
            $bProvidersValues = !empty($aProvidersValues) && is_array($aProvidersValues);

            //--- Providers: Remove deleted
            if(!empty($aAutomator['providers']) && is_array($aAutomator['providers']))
                $this->_oDb->deleteAutomatorProvidersById(array_diff(array_keys($aAutomator['providers']), $bProvidersIds ? $aProvidersIds : []));

            //--- Providers: Update existed
            if($bProvidersIds)
                foreach($aProvidersIds as $iIndex => $iApId)
                    $this->_oDb->updateAutomatorProvider(['provider_id' => (int)$aProvidersValues[$iIndex]], ['id' => (int)$iApId]);

            //--- Providers: Add new
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

            /**
             * Process Helpers
             */
            $aHelpersIds = $oForm->getCleanValue('helpers_ids');
            $bHelpersIds = !empty($aHelpersIds) && is_array($aHelpersIds);
            $aHelpersValues = $oForm->getCleanValue('helpers');
            $bHelpersValues = !empty($aHelpersValues) && is_array($aHelpersValues);

            //--- Helpers: Remove deleted
            if(!empty($aAutomator['helpers']) && is_array($aAutomator['helpers']))
                $this->_oDb->deleteAutomatorHelpersById(array_diff(array_keys($aAutomator['helpers']), $bHelpersIds ? $aHelpersIds : []));

            //--- Helpers: Update existed
            if($bHelpersIds)
                foreach($aHelpersIds as $iIndex => $iAhId)
                    $this->_oDb->updateAutomatorHelper(['helper_id' => (int)$aHelpersValues[$iIndex]], ['id' => (int)$iAhId]);

            //--- Helpers: Add new
            $iHelpersIds = $bHelpersIds ? count($aHelpersIds) : 0;
            $iHelpersValues = $bHelpersValues ? count($aHelpersValues) : 0;
            if($iHelpersValues > $iHelpersIds) {
                $aHelpersValues = array_slice($aHelpersValues, $iHelpersIds);
                foreach($aHelpersValues as $iHelpersValue)
                    $this->_oDb->insertAutomatorHelper([
                        'automator_id' => $iId,
                        'helper_id' => (int)$iHelpersValue,
                    ]);
            }

            /**
             * Process Assistants
             */
            $aAssistantsIds = $oForm->getCleanValue('assistants_ids');
            $bAssistantsIds = !empty($aAssistantsIds) && is_array($aAssistantsIds);
            $aAssistantsValues = $oForm->getCleanValue('assistants');
            $bAssistantsValues = !empty($aAssistantsValues) && is_array($aAssistantsValues);

            //--- Assistants: Remove deleted
            if(!empty($aAutomator['assistants']) && is_array($aAutomator['assistants']))
                $this->_oDb->deleteAutomatorAssistantsById(array_diff(array_keys($aAutomator['assistants']), $bAssistantsIds ? $aAssistantsIds : []));

            //--- Assistants: Update existed
            if($bAssistantsIds)
                foreach($aAssistantsIds as $iIndex => $iAhId)
                    $this->_oDb->updateAutomatorAssistant(['assistant_id' => (int)$aAssistantsValues[$iIndex]], ['id' => (int)$iAhId]);

            //--- Assistants: Add new
            $iAssistantsIds = $bAssistantsIds ? count($aAssistantsIds) : 0;
            $iAssistantsValues = $bAssistantsValues ? count($aAssistantsValues) : 0;
            if($iAssistantsValues > $iAssistantsIds) {
                $aAssistantsValues = array_slice($aAssistantsValues, $iAssistantsIds);
                foreach($aAssistantsValues as $iAssistantsValue)
                    $this->_oDb->insertAutomatorAssistant([
                        'automator_id' => $iId,
                        'helper_id' => (int)$iAssistantsValue,
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

            if($oForm->update($iId, $aValsToAdd) !== false) {
                if(($oCmts = BxDolCmts::getObjectInstance($this->_sCmts, $iId)) !== null) {
                    $sInstructions = $oAI->getAutomatorInstruction('profile', $iProfileId);

                    $aProviders = $this->_oDb->getAutomatorsBy(['sample' => 'providers_by_id_pairs', 'id' => $iId]);
                    if(!empty($aProviders) && is_array($aProviders))
                        $sInstructions .= $oAI->getAutomatorInstruction('providers', array_values($aProviders));

                    $aHelpers = $this->_oDb->getAutomatorsBy(['sample' => 'helpers_by_id_pairs', 'id' => $iId]);
                    if(!empty($aHelpers) && is_array($aHelpers))
                        $sInstructions .= $oAI->getAutomatorInstruction('helpers', array_values($aHelpers));

                    $aAssistants = $this->_oDb->getAutomatorsBy(['sample' => 'assistants_by_id_pairs', 'id' => $iId]);
                    if(!empty($aAssistants) && is_array($aAssistants))
                        $sInstructions .= $oAI->getAutomatorInstruction('assistants', array_values($aAssistants));

                    $oCmts->addAuto([
                        'cmt_author_id' => $iProfileId,
                        'cmt_parent_id' => 0,
                        'cmt_text' => $sInstructions
                    ]);

                    if(($sResponse = $oAI->getModelObject($aAutomator['model_id'])->getResponse($aAutomator['type'], $sInstructions, $aAutomator['params'])) !== false) {
                        sleep(1);
                        $oCmts->addAuto([
                            'cmt_author_id' => $this->_iProfileIdAi,
                            'cmt_parent_id' => 0,
                            'cmt_text' => $sResponse
                        ]);
                    }
                }

                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            }
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
        if($sKey == 'message_id' && ($iCmtId = (int)$aRow[$sKey]) != 0 && ($oCmts = BxDolAI::getInstance()->getAutomatorCmtsObject($aRow['id'])) !== false) {
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
        $mixedResult = parent::_delete($mixedId);
        if($mixedResult) {
            $this->_oDb->deleteAutomatorProviders(['automator_id' => (int)$mixedId]);
            $this->_oDb->deleteAutomatorHelpers(['automator_id' => (int)$mixedId]);
            $this->_oDb->deleteAutomatorAssistants(['automator_id' => (int)$mixedId]);

            if(($oCmts = BxDolAI::getInstance()->getAutomatorCmtsObject($mixedId)) !== false)
                $oCmts->onObjectDelete();
        }

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

        if(!empty($aAutomator['params']) && is_string($aAutomator['params']))
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
                'name' => [
                    'type' => 'text',
                    'name' => 'name',
                    'caption' => _t('_sys_agents_automators_field_name'),
                    'value' => isset($aAutomator['name']) ? $aAutomator['name'] : '',
                    'required' => '1',
                    'checker' => [
                        'func' => 'Avail',
                        'params' => [],
                        'error' => _t('_sys_agents_form_field_err_enter'),
                    ],
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'model_id' => [
                    'type' => 'select',
                    'name' => 'model_id',
                    'caption' => _t('_sys_agents_automators_field_model_id'),
                    'info' => '',
                    'value' => isset($aAutomator['model_id']) ? $aAutomator['model_id'] : BxDolAI::getInstance()->getDefaultModel(),
                    'values' => $this->_oDb->getModelsBy(['sample' => 'all_pairs']),
                    'required' => '1',
                    'checker' => [
                        'func' => 'Avail',
                        'params' => [],
                        'error' => _t('_sys_agents_form_field_err_select'),
                    ],
                    'db' => [
                        'pass' => 'Int',
                    ],
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
                'helpers' => [
                    'type' => 'custom',
                    'name' => 'helpers',
                    'caption' => _t('_sys_agents_automators_field_helpers'),
                    'value' => '',
                ],
                'assistants' => [
                    'type' => 'custom',
                    'name' => 'assistants',
                    'caption' => _t('_sys_agents_automators_field_assistants'),
                    'value' => '',
                ],
                'message' => [
                    'type' => 'textarea',
                    'name' => 'message',
                    'caption' => _t('_sys_agents_automators_field_message'),
                    'required' => '1',
                    'checker' => [
                        'func' => 'Avail',
                        'params' => [],
                        'error' => _t('_sys_agents_automators_field_message_err'),
                    ],
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
        
        if(isset($aForm['inputs']['providers'])) {
            $oForm = new BxTemplFormView([]);

            $sProviders = 'providers';
            $aProviders = array_map(function($sTitle) {
                return _t($sTitle);
            }, ['' => '_sys_please_select'] + $this->_oDb->getProvidersBy(['sample' => 'all_pairs', 'active' => 1]));

            $aTmplVarsProviders = [];
            if(!empty($aAutomator[$sProviders]) && is_array($aAutomator[$sProviders])) {
                foreach($aAutomator[$sProviders] as $iApId => $iProviderId) {
                    $aInputSelect = [
                        'type' => 'select',
                        'name' => $sProviders . '[]',
                        'values' => $aProviders,
                        'value' => (int)$iProviderId,
                        'attrs' => [
                            'class' => 'bx-def-margin-sec-top-auto'
                        ]
                    ];
                    $sInput = $oForm->genInput($aInputSelect);

                    $aInputHidden = [
                        'type' => 'hidden',
                        'name' => $sProviders . '_ids[]',
                        'value' => (int)$iApId,
                    ];
                    $sInput .= $oForm->genInput($aInputHidden);

                    $aTmplVarsProviders[] = ['js_object' => $sJsObject, 'input_select' => $sInput];
                }
            }
            else  {
                $aInputSelect = [
                    'type' => 'select',
                    'name' => $sProviders . '[]',
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
                'name' => $sProviders . '_add',
                'value' => _t('_sys_agents_automators_field_providers_add'),
                'attrs' => [
                    'class' => 'bx-def-margin-sec-top',
                    'onclick' => $sJsObject . ".providerAdd(this, '" . $sProviders . "');"
                ]
            ];

            $aForm['inputs'][$sProviders]['content'] = $this->_oTemplate->parseHtmlByName('agents_automator_form_providers.html', [
                'bx_repeat:providers' => $aTmplVarsProviders,
                'btn_add' => $oForm->genInputButton($aInputButton)
            ]);
        }

        if(isset($aForm['inputs']['helpers'])) {
            $oForm = new BxTemplFormView([]);

            $sHelpers = 'helpers';
            $aHelpers = array_map(function($sTitle) {
                return _t($sTitle);
            }, ['' => '_sys_please_select'] + $this->_oDb->getHelpersBy(['sample' => 'all_pairs', 'active' => 1]));

            $aTmplVarsHelpers = [];
            if(!empty($aAutomator[$sHelpers]) && is_array($aAutomator[$sHelpers])) {
                foreach($aAutomator[$sHelpers] as $iAhId => $iHelperId) {
                    $aInputSelect = [
                        'type' => 'select',
                        'name' => $sHelpers . '[]',
                        'values' => $aHelpers,
                        'value' => (int)$iHelperId,
                        'attrs' => [
                            'class' => 'bx-def-margin-sec-top-auto'
                        ]
                    ];
                    $sInput = $oForm->genInput($aInputSelect);

                    $aInputHidden = [
                        'type' => 'hidden',
                        'name' => $sHelpers . '_ids[]',
                        'value' => (int)$iAhId,
                    ];
                    $sInput .= $oForm->genInput($aInputHidden);

                    $aTmplVarsHelpers[] = ['js_object' => $sJsObject, 'input_select' => $sInput];
                }
            }
            else  {
                $aInputSelect = [
                    'type' => 'select',
                    'name' => $sHelpers . '[]',
                    'values' => $aHelpers,
                    'value' => '',
                    'attrs' => [
                        'class' => 'bx-def-margin-sec-top-auto'
                    ]
                ];

                $aTmplVarsHelpers = [
                    ['js_object' => $sJsObject, 'input_select' => $oForm->genInput($aInputSelect)],
                ];
            }
        
            $aInputButton = [
                'type' => 'button',
                'name' => $sHelpers . '_add',
                'value' => _t('_sys_agents_automators_field_helpers_add'),
                'attrs' => [
                    'class' => 'bx-def-margin-sec-top',
                    'onclick' => $sJsObject . ".helperAdd(this, '" . $sHelpers . "');"
                ]
            ];

            $aForm['inputs'][$sHelpers]['content'] = $this->_oTemplate->parseHtmlByName('agents_automator_form_helpers.html', [
                'bx_repeat:helpers' => $aTmplVarsHelpers,
                'btn_add' => $oForm->genInputButton($aInputButton)
            ]);
        }

        if(isset($aForm['inputs']['assistants'])) {
            $oForm = new BxTemplFormView([]);

            $sAssistants = 'assistants';
            $aAssistants = array_map(function($sTitle) {
                return _t($sTitle);
            }, ['' => '_sys_please_select'] + $this->_oDb->getAssistantsBy(['sample' => 'all_pairs', 'active' => 1]));

            $aTmplVarsAssistants = [];
            if(!empty($aAutomator[$sAssistants]) && is_array($aAutomator[$sAssistants])) {
                foreach($aAutomator[$sAssistants] as $iAhId => $iAssistantId) {
                    $aInputSelect = [
                        'type' => 'select',
                        'name' => $sAssistants . '[]',
                        'values' => $aAssistants,
                        'value' => (int)$iAssistantId,
                        'attrs' => [
                            'class' => 'bx-def-margin-sec-top-auto'
                        ]
                    ];
                    $sInput = $oForm->genInput($aInputSelect);

                    $aInputHidden = [
                        'type' => 'hidden',
                        'name' => $sAssistants . '_ids[]',
                        'value' => (int)$iAhId,
                    ];
                    $sInput .= $oForm->genInput($aInputHidden);

                    $aTmplVarsAssistants[] = ['js_object' => $sJsObject, 'input_select' => $sInput];
                }
            }
            else  {
                $aInputSelect = [
                    'type' => 'select',
                    'name' => $sAssistants . '[]',
                    'values' => $aAssistants,
                    'value' => '',
                    'attrs' => [
                        'class' => 'bx-def-margin-sec-top-auto'
                    ]
                ];

                $aTmplVarsAssistants = [
                    ['js_object' => $sJsObject, 'input_select' => $oForm->genInput($aInputSelect)],
                ];
            }
        
            $aInputButton = [
                'type' => 'button',
                'name' => $sAssistants . '_add',
                'value' => _t('_sys_agents_automators_field_assistants_add'),
                'attrs' => [
                    'class' => 'bx-def-margin-sec-top',
                    'onclick' => $sJsObject . ".assistantAdd(this, '" . $sAssistants . "');"
                ]
            ];

            $aForm['inputs'][$sAssistants]['content'] = $this->_oTemplate->parseHtmlByName('agents_automator_form_assistants.html', [
                'bx_repeat:assistants' => $aTmplVarsAssistants,
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

    protected function _getAutomatorName($sName)
    {
        return uriGenerate($sName, 'sys_agents_automators', 'name', ['lowercase' => false]);
    }
}

/** @} */
