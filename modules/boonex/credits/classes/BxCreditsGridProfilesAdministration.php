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

class BxCreditsGridProfilesAdministration extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->_sModule = 'bx_credits';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);
    	if(!$oTemplate)
            $oTemplate = $this->_oModule->_oTemplate;

        parent::__construct ($aOptions, $oTemplate);
    }

    public function performActionEdit()
    {
        $sAction = 'edit';

        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId)
                return false;

            $aIds = [$iId];
        }

        $iId = $aIds[0];

        $aProfile = $this->_oModule->_oDb->getProfile(['type' => 'id', 'id' => $iId]);
        if(empty($aProfile) || !is_array($aProfile))
            return echoJson([]);

        $oForm = $this->_getFormObject($sAction, $aProfile);
        $oForm->initChecker($aProfile);

        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($iId) !== false)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            else
                $aRes = ['msg' => _t('_bx_credits_txt_err_cannot_perform_action')];

            echoJson($aRes);
        }
        else {
            $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('edit_profile_popup'), _t('_bx_credits_grid_popup_title_pfl_edit', BxDolProfile::getInstanceMagic($aProfile['id'])->getDisplayName()), $this->_oModule->_oTemplate->parseHtmlByName('profile_form.html', [
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            ]));

            echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false, 'removeOnClose' => true]]]);
        }
    }

    protected function _getFormObject($sAction, $aProfile = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_PROFILE'], $CNF['OBJECT_FORM_PROFILE_DISPLAY_' . strtoupper($sAction)]);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        if(!empty($aProfile['id']))
            $oForm->aFormAttrs['action'] .= '&id=' . $aProfile['id'];

        return $oForm;
    }

    protected function _getCellId($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_getProfile($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellBalanceCleared($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->getProfileBalanceCleared($aRow['id']), $sKey, $aField, $aRow);
    }

    protected function _getProfile($mixedValue) 
    {
        $oProfile = BxDolProfile::getInstanceMagic($mixedValue);
        if(!$oProfile)
            return $mixedValue;

        return $oProfile->getUnit(0, ['template' => ['name' => 'unit', 'size' => 'icon']]);
    }
}

/** @} */
