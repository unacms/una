<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BasePayment Base classes for Payment like modules
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxBaseModPaymentGridInvoices extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;
    protected $_oPayment;

    protected $_sLangsPrefix;
    protected $_aJsCodes;

    protected $_bAllowManage;
    protected $_iMainSeller;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->_sModule);
        $this->_oPayment = BxDolPayments::getInstance();

        $this->_sLangsPrefix = $this->_oModule->_oConfig->getPrefix('langs');
        $this->_aJsCodes = array();

        $this->_bAllowManage = $this->_oModule->isAllowedManageInvoices() === true;
        if(!$this->_bAllowManage) {
            $iProfileId = bx_get('profile_id');
            $this->_aQueryAppend['profile_id'] = $iProfileId !== false ? (int)$iProfileId : bx_get_logged_profile_id();
        }

        $this->_iMainSeller = $this->_oModule->_oConfig->getSiteAdmin();
    }

    public function getCode($isDisplayHeader = true)
    {
    	return parent::getCode($isDisplayHeader) . $this->getJsCode();
    }

    public function getJsCode()
    {
        if(empty($this->_aJsCodes) || !is_array($this->_aJsCodes))
            return '';

        return implode('', $this->_aJsCodes);
    }

    protected function _getActionEdit ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->_bAllowManage)
            return '';
    
        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionDelete ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->_bAllowManage)
            return '';
    
        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionPay ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if($aRow['committent_id'] != bx_get_logged_profile_id() || (float)$aRow['amount'] == 0 || $aRow['status'] == BX_PAYMENT_INV_STATUS_PAID)
            return '';

    	$aJs = $this->_oPayment->getAddToCartJs($this->_iMainSeller, $this->_sModule, $aRow['id'], 1, true);
    	if(!empty($aJs) && is_array($aJs)) {
            list($sJsCode, $sJsMethod) = $aJs;

            $sJsCodeCheckSum = md5($sJsCode);
            if(!isset($this->_aJsCodes[$sJsCodeCheckSum]))
                $this->_aJsCodes[$sJsCodeCheckSum] = $sJsCode;

            $a['attr'] = array(
                'title' => bx_html_attribute(_t('_bx_payment_grid_action_title_inv_pay')),
                'onclick' => $sJsMethod
            );
    	}

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getCellHeaderCommissionaireId ($sKey, $aField)
    {
        if(!$this->_bAllowManage)
            return '';

        return parent::_getCellHeaderDefault($sKey, $aField);
    }

    protected function _getCellCommissionaireId($mixedValue, $sKey, $aField, $aRow)
    {
        if(!$this->_bAllowManage)
            return '';

        return parent::_getCellDefault($this->_oModule->_oTemplate->displayProfileLink($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellCommittentId($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->_oTemplate->displayProfileLink($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellPeriodStart($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->_oConfig->formatDate($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellPeriodEnd($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->_oConfig->formatDate($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellAmount($mixedValue, $sKey, $aField, $aRow)
    {
        $sSign = '';
        if(!empty($aRow['currency']))
            $sSign = $this->_oModule->_oConfig->retrieveCurrencySign($aRow['currency']);
        else
            $sSign = $this->_oModule->getVendorCurrencySign((int)$aRow['commissionaire_id']);

        return parent::_getCellDefault(_t_format_currency_ext($mixedValue, [
            'sign' => $sSign
        ]), $sKey, $aField, $aRow);
    }

    protected function _getCellDateIssue($mixedValue, $sKey, $aField, $aRow)
    {
        return $this->_getCellDefaultDate($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellDateDue($mixedValue, $sKey, $aField, $aRow)
    {
        return $this->_getCellDefaultDate($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellStatus($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t('_bx_payment_txt_status_' . $mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellDefaultDate($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue, BX_FORMAT_DATE, true), $sKey, $aField, $aRow);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(!$this->_bAllowManage) {
            if(empty($this->_aQueryAppend['profile_id']))
                return array();

            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `committent_id`=?", $this->_aQueryAppend['profile_id']);
        }

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
