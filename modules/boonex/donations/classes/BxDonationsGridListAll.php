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

class BxDonationsGridListAll extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->_sModule = 'bx_donations';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);
    	if(!$oTemplate)
            $oTemplate = $this->_oModule->_oTemplate;

        parent::__construct ($aOptions, $oTemplate);

        $this->_sDefaultSortingOrder = 'DESC';
        $this->_aQueryReset = array($this->_aOptions['order_get_field'], $this->_aOptions['order_get_dir'], $this->_aOptions['paginate_get_start'], $this->_aOptions['paginate_get_per_page']);
    }

    protected function _getCellProfileId($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->_oTemplate->getProfileLink($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellEntry($mixedValue, $sKey, $aField, $aRow)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sTitlekey = '_bx_donations_txt_amount_' . ($this->_oModule->_oConfig->isShowTitle() ? 'with' : 'wo') . '_title';

        $sAmount = _t_format_currency($aRow['type_amount'], getParam($CNF['PARAM_AMOUNT_PRECISION']));
        $sAmount = _t('_bx_donations_txt_amount_single', $sAmount);

        return parent::_getCellDefault(_t($sTitlekey, $sAmount, _t($aRow['type_title'])), $sKey, $aField, $aRow);
    }

    protected function _getCellBillingType($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = BX_DONATIONS_BTYPE_SINGLE;
        if(!empty($aRow['type_period']) && !empty($aRow['type_period_unit']))
            $mixedValue = BX_DONATIONS_BTYPE_RECURRING;

        return parent::_getCellDefault(_t('_bx_donations_txt_btype_' . $mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue, BX_FORMAT_DATE, true), $sKey, $aField, $aRow);
    }
}

/** @} */
