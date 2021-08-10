<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Donations Donations
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxDonationsGridTypes extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;
    
    protected $_aPeriodUnits;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sModule = 'bx_donations';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct ($aOptions, $oTemplate);

        $this->_aPeriodUnits = $this->_oModule->_oConfig->getPeriodUnits();
    }

    public function performActionAdd()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$sAction = 'add';

    	$oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_TYPE'], $CNF['OBJECT_FORM_TYPE_DISPLAY_ADD']);
        $oForm->setAction(BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $iPeriod = $oForm->getCleanValue('period');
            $bPeriod = !empty($iPeriod);

            $sPeriodUnit = $oForm->getCleanValue('period_unit');
            $bPeriodUnit = !empty($sPeriodUnit);

            if(!$bPeriod && $bPeriodUnit) 
                return echoJson(array('msg' => _t('_bx_donations_form_type_input_err_period')));
            if($bPeriod && !$bPeriodUnit) 
                return echoJson(array('msg' => _t('_bx_donations_form_type_input_err_period_unit')));

            $iAmount = $oForm->getCleanValue('amount');
            $aType = $this->_oModule->_oDb->getTypes(array('type' => 'by_duration_amount', 'period' => $iPeriod, 'period_unit' => $sPeriodUnit, 'amount' => $iAmount));
            if(!empty($aType) && is_array($aType))
                return echoJson(array('msg' => _t('_bx_donations_err_price_duplicate')));

            $iId = (int)$oForm->insert(array('order' => $this->_oModule->_oDb->getTypeOrderMax() + 1));
            if($iId != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_donations_err_cannot_perform'));

            echoJson($aRes);
            return;
        }

        bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_type'), _t('_bx_donations_popup_title_price_add'), $this->_oModule->_oTemplate->parseHtmlByName('popup_type.html', array(
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        )));

        echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false, 'removeOnClose' => true))));
    }

    public function performActionEdit()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        $sAction = 'edit';

        $iId = $this->_getId();
        if($iId === false)
            return echoJson(array());

        $aItem = $this->_oModule->_oDb->getTypes(array('type' => 'by_id', 'value' => $iId));
        if(!is_array($aItem) || empty($aItem))
            return echoJson(array());

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_TYPE'], $CNF['OBJECT_FORM_TYPE_DISPLAY_EDIT']);
        $oForm->setAction(BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&id=' . $iId);

        $oForm->initChecker($aItem);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($aItem['id']) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $aItem['id']);
            else
                $aRes = array('msg' => _t('_bx_donations_err_cannot_perform'));

            return echoJson($aRes);
        }

        bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_type'), _t('_bx_donations_popup_title_price_edit'), $this->_oModule->_oTemplate->parseHtmlByName('popup_type.html', array(
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        )));

        echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false, 'removeOnClose' => true))));
    }

    protected function _delete ($mixedId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aItem = $this->_oModule->_oDb->getTypes(array('type' => 'by_id', 'value' => (int)$mixedId));
        if(!empty($aItem) && is_array($aItem))
            BxDolStudioLanguagesUtils::getInstance()->deleteLanguageString($aItem[$CNF['FIELD_TITLE']]);

        return parent::_delete($mixedId);
    }

    protected function _getCellPeriod($mixedValue, $sKey, $aField, $aRow)
    {
        if((int)$mixedValue == 0)
            $mixedValue = _t('_bx_donations_txt_btype_single');
        else
            $mixedValue = _t('_bx_donations_txt_n_unit', $mixedValue, _t($this->_aPeriodUnits[$aRow['period_unit']]));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellPrice($mixedValue, $sKey, $aField, $aRow)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return parent::_getCellDefault(_t_format_currency($mixedValue, getParam($CNF['PARAM_AMOUNT_PRECISION'])), $sKey, $aField, $aRow);
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oModule->_oTemplate->addStudioJs(array('jquery.form.min.js'));

        $oForm = new BxTemplFormView(array());
        $oForm->addCssJs();
    }

    protected function _isVisibleGrid ($a)
    {
        return isAdmin();
    }

    protected function _getId()
    {
        $aIds = bx_get('ids');
        if(!empty($aIds) && is_array($aIds)) 
            return (int)array_shift($aIds);

        $iId = bx_get('id');
        if($iId !== false) 
            return (int)$iId;

        return false;
    }
}

/** @} */
