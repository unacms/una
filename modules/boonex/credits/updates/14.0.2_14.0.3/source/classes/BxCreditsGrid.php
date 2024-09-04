<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Credits Credits
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxCreditsGrid extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    protected $_iUserId;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sModule = 'bx_credits';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);
    	if(!$oTemplate)
            $oTemplate = $this->_oModule->_oTemplate;

        parent::__construct ($aOptions, $oTemplate);

        $iUserId = bx_get_logged_profile_id();
        if($iUserId !== false) {
            $this->_iUserId = (int)$iUserId;
            $this->_aQueryAppend['user_id'] = $this->_iUserId;
        }
    }

    protected function _getProfile($mixedValue) 
    {
        if(is_numeric($mixedValue) && (int)$mixedValue == 0)
            $mixedValue = $this->_oModule->_oConfig->getAuthor();

        $oProfile = BxDolProfile::getInstanceMagic($mixedValue);
        if(!$oProfile)
            return $mixedValue;

        return $oProfile->getUnit(0, ['template' => ['name' => 'unit', 'size' => 'icon']]);
    }

    protected function _performActionWithProfileAmount($sAction)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oForm = $this->_getFormObject($sAction);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $iProfile = (int)$oForm->getCleanValue('profile');
            $fAmount = (float)$oForm->getCleanValue('amount');
            $sMessage = $oForm->getCleanValue('message');

            $aResult = $this->_oModule->{'process' . bx_gen_method_name($sAction)}($this->_iUserId, $iProfile, $fAmount, $sMessage);
            echoJson($this->_onPerformAction($aResult));
        }
        else {
            $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds($sAction . '_popup'), _t($CNF['T'][$sAction . '_popup']), $this->_oModule->_oTemplate->parseHtmlByName('credit_form.html', [
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            ]));

            echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
        }
    }
    
    protected function _onPerformAction($aResult)
    {
        $aRes = [];

        if((int)$aResult['code'] == 0) {
            if(!empty($aResult['id']))
                $aRes = ['grid' => $this->getCode(false), 'blink' => $aResult['id']];
            else
                $aRes = ['msg' => _t(!empty($aResult['msg']) ? $aResult['msg'] : '_bx_credits_msg_action_performed')];
        }
        else
            $aRes = ['msg' => _t(!empty($aResult['msg']) ? $aResult['msg'] : '_bx_credits_err_cannot_perform_action')];

        return $aRes;
    }

    protected function _getFormObject($sAction)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_CREDIT'], $CNF['OBJECT_FORM_CREDIT_DISPLAY_' . strtoupper($sAction)]);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        $oForm->setUserId($this->_iUserId);

        return $oForm;
    }
    
    protected function _getId()
    {
        $aIds = bx_get('ids');
        if(!empty($aIds) && is_array($aIds))
            return array_shift($aIds);

        if(($iId = bx_get('id')) !== false)
            return (int)$iId;

        return false;
    }
}

/** @} */
