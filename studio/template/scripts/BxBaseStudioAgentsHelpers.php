<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioAgentsHelpers extends BxDolStudioAgentsHelpers
{
    protected $_sUrlPage;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sUrlPage = BX_DOL_URL_STUDIO . 'agents.php?page=helpers';
    }

    public function getPageJsObject()
    {
        return 'oBxDolStudioPageAgents';
    }
    
    
    protected function _getActionsDisabledBehavior($aRow)
    {
        return false;
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

            $aValsToAdd['name'] = $oForm->getCleanValue('name');
            $aValsToAdd['prompt'] = $oForm->getCleanValue('prompt');

            $bIsValid = true;
            if($bIsValid) {
                if(($iId = $oForm->insert($aValsToAdd)) !== false) {
                    $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
                }
                else
                    $aRes = ['msg' => _t('_sys_txt_error_occured')];

                return echoJson($aRes);
            }
        }

        $sFormId = $oForm->getId();
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup', _t('_sys_agents_helpers_popup_add'), $this->_oTemplate->parseHtmlByName('agents_automator_form.html', [
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
        $aHelper = $this->_oDb->getHelpersBy(['sample' => 'id', 'id' => $iId]);

        $aForm = $this->_getFormEdit($sAction, $aHelper);
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

            $aValsToAdd['name'] = $oForm->getCleanValue('name');
            $aValsToAdd['prompt'] = $oForm->getCleanValue('prompt');
            
            if($oForm->update($iId, $aValsToAdd) !== false)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            else
                $aRes = ['msg' => _t('_sys_txt_error_occured')];

            return echoJson($aRes);
        } 

        $sFormId = $oForm->getId();
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup', _t('_sys_agents_helpers_popup_edit'), $this->_oTemplate->parseHtmlByName('agents_automator_form.html', [
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
        return parent::_getCellDefault(_t('_sys_agents_helpers_field_type_' . $mixedValue), $sKey, $aField, $aRow);
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

    

    protected function _getActionCheck($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = [])
    {
    	$a['attr'] = array_merge($a['attr'], [
            "onclick" => "window.open('" . $this->_sUrlPage . '&id=' . $aRow['id'] . "', '_self');"
    	]);

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();

        $this->_oTemplate->addJs(['jquery.form.min.js', 'agents_helpers.js']);

        $oForm = new BxTemplStudioFormView([]);
        $oForm->addCssJs();
    }
    
    protected function _isCheckboxDisabled($aRow)
    {
        return false;
    }



    protected function _getFormEdit($sAction, $aHelper = [])
    {
        $aForm = $this->_getForm($sAction, $aHelper);
        $aForm['form_attrs']['action'] .= '&id=' . $aHelper['id'];

        return $aForm;
    }

    protected function _getForm($sAction, $aHelper = [])
    {
        $sJsObject = $this->getPageJsObject();

        $sType = isset($aHelper['type']) ? $aHelper['type'] : 'event';



        $aForm = array(
            'form_attrs' => array(
                'id' => 'bx_std_agents_helpers_' . $sAction,
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=sys_studio_agents_helpers&a=' . $sAction,
                'method' => 'post',
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_agents_helpers',
                    'key' => 'id',
                    'submit_name' => 'do_submit',
                ),
            ),
            'inputs' => array(
                 'name' => [
                    'type' => 'text',
                    'name' => 'name',
                    'required' => '1',
                    'caption' => _t('_sys_agents_helpers_field_name'),
                    'value' => isset($aHelper['name']) ? $aHelper['name'] : '',
                ],
                'model_id' => [
                    'type' => 'select',
                    'name' => 'model_id',
                    'caption' => _t('_sys_agents_helpers_field_model_id'),
                    'info' => '',
                    'value' => isset($aHelper['model_id']) ? $aHelper['model_id'] : BxDolAI::getInstance()->getDefaultModel(),
                    'values' => $this->_oDb->getModelsBy(['sample' => 'all_pairs']),
                    'required' => '1',
                    'db' => [
                        'pass' => 'Int',
                    ]
                ],
                'profile_id' => [
                    'type' => 'select',
                    'name' => 'profile_id',
                    'caption' => _t('_sys_agents_helpers_field_profile_id'),
                    'info' => _t('_sys_agents_helpers_field_profile_id_inf'),
                    'value' => isset($aHelper['profile_id']) ? $aHelper['profile_id'] : 0,
                    'values' => bx_srv('system', 'get_options_agents_profile', [], 'TemplServices'),
                    'required' => '0',
                    'db' => [
                        'pass' => 'Int',
                    ]
                ],
                
                'prompt' => [
                    'type' => 'textarea',
                    'name' => 'prompt',
                    'required' => '1',
                    'caption' => _t('_sys_agents_helpers_field_prompt'),
                    'value' => isset($aHelper['prompt']) ? $aHelper['prompt'] : '',
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
