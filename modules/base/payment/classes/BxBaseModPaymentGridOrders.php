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

class BxBaseModPaymentGridOrders extends BxTemplGrid
{
    protected $MODULE;
    protected $_oModule;

    protected $_sOrdersType;

    protected $_bSingleSeller;
    protected $_iSingleSeller;

    protected $_sJsObject;
    protected $_sLangsPrefix;
    protected $_sCurrencySign;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->MODULE);

        $this->_bSingleSeller = $this->_oModule->_oConfig->isSingleSeller();

        $this->_iSingleSeller = 0;
        if($this->_bSingleSeller)
            $this->_iSingleSeller = $this->_oModule->_oConfig->getSiteAdmin();

        $this->_sLangsPrefix = $this->_oModule->_oConfig->getPrefix('langs');
        $this->_sCurrencySign = $this->_oModule->_oConfig->getDefaultCurrencySign();

        $this->_sDefaultSortingOrder = 'DESC';

        $iSellerId = bx_get('seller_id');
        if($iSellerId !== false) {
            $iSellerId = (int)$iSellerId;
            $this->_aQueryAppend['seller_id'] = $iSellerId;

            if(!$this->_bSingleSeller)
                $this->_sCurrencySign = $this->_oModule->getVendorCurrencySign($iSellerId);
        }

        $iClientId = bx_get('client_id');
        if($iClientId !== false)
            $this->_aQueryAppend['client_id'] = (int)$iClientId;
    }

    public function addQueryParam($sKey, $sValue)
    {
        if(empty($sKey) || !isset($sValue))
            return;

        $this->_aQueryAppend[$sKey] = $sValue;
    }

    public function performActionViewOrder()
    {
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) 
            return echoJson(array());

        $iId = (int)$aIds[0];

        $sKey = 'order_' . $this->_sOrdersType . '_view';
        $sId = $this->_oModule->_oConfig->getHtmlIds($this->_sOrdersType, $sKey);
        $sTitle = _t($this->_sLangsPrefix . 'popup_title_ods_' . $sKey);
        $sContent = $this->_oModule->getObjectOrders()->getOrder($this->_sOrdersType, $iId);

        return echoJson(array('popup' => BxTemplFunctions::getInstance()->popupBox($sId, $sTitle, $sContent)));
    }

    public function performActionCancel()
    {
    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) 
            return echoJson(array());

        $oOrders = $this->_oModule->getObjectOrders();

        $iAffected = 0;
        $aAffected = array();
        foreach($aIds as $iId)
            if($oOrders->cancel($this->_sOrdersType, $iId)) {
                $aAffected[] = $iId;
                $iAffected++;
            }

        echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aAffected) : array('msg' => _t($this->_sLangsPrefix . 'err_cannot_perform')));
    }

    protected function _getCellHeaderAuthorId ($sKey, $aField)
    {
        if(!$this->_bSingleSeller)
            return '';

        if($this->_aQueryAppend['seller_id'] != $this->_iSingleSeller)
            $aField['title'] = _t('_bx_payment_grid_column_title_ods_seller_id');

        return parent::_getCellHeaderDefault($sKey, $aField);
    }

    protected function _getCellClientId($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->_oTemplate->displayProfileLink($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellSellerId($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->_oTemplate->displayProfileLink($mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getCellAuthorId($mixedValue, $sKey, $aField, $aRow)
    {
        if(!$this->_bSingleSeller)
            return '';

        if($this->_aQueryAppend['seller_id'] != $this->_iSingleSeller)
            $mixedValue = $aRow['seller_id'];
        
        $sResult = '';
        if(!empty($mixedValue) && is_numeric($mixedValue))
            $sResult = $this->_oModule->_oTemplate->displayProfileLink($mixedValue);

        return parent::_getCellDefault($sResult, $sKey, $aField, $aRow);
    }

    protected function _getCellItem($mixedValue, $sKey, $aField, $aRow)
    {
        $sTxtUnknown = _t('_uknown');

        if(empty($aRow['module_id']) || empty($aRow['item_id']))
            return parent::_getCellDefault($sTxtUnknown, $sKey, $aField, $aRow);

        $aItemInfo = $this->_oModule->callGetCartItem((int)$aRow['module_id'], array((int)$aRow['item_id'], $aRow['client_id']));
        if(empty($aItemInfo) || !is_array($aItemInfo))
            return parent::_getCellDefault($sTxtUnknown, $sKey, $aField, $aRow);

        return parent::_getCellDefault($this->_oModule->_oTemplate->displayLink('link', array(
            'href' => $aItemInfo['url'],
            'title' => bx_html_attribute($aItemInfo['title']),
            'content' => $aItemInfo['title']
    	)), $sKey, $aField, $aRow);
    }

    protected function _getCellAmount($mixedValue, $sKey, $aField, $aRow)
    {
        $sSign = '';
        if(!empty($aRow['currency']))
            $sSign = $this->_oModule->_oConfig->retrieveCurrencySign($aRow['currency']);
        else
            $sSign = !$this->_bSingleSeller ? $this->_oModule->getVendorCurrencySign((int)$aRow['seller_id']) : $this->_sCurrencySign;

        return parent::_getCellDefault(_t_format_currency_ext($mixedValue, ['sign' => $sSign]), $sKey, $aField, $aRow);
    }

    protected function _getCellDate($mixedValue, $sKey, $aField, $aRow)
    {
        return $this->_getCellDefaultDateTime($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellDefaultDate($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue, BX_FORMAT_DATE, true), $sKey, $aField, $aRow);
    }

    protected function _getCellDefaultDateTime($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue, BX_FORMAT_DATE_TIME, true), $sKey, $aField, $aRow);
    }

    protected function _getFilterSelectAll($sName, $aParams = array())
    {
        $sJsObject = !empty($aParams['js_object']) ? $aParams['js_object'] : $this->_sJsObject;
        $sJsOnChange = !empty($aParams['js_onchange']) ? $aParams['js_onchange'] : 'onChangeFilter(this)';
        
        $aAttrs = array(
            'id' => 'bx-grid-' . $sName . '-' . $this->_sObject,
            'onChange' => 'javascript:$(this).off(\'keyup focusout\'); ' . $sJsObject . '.' . $sJsOnChange,
            'onBlur' => 'javascript:' . $sJsObject . '.' . $sJsOnChange,
        );
        if(!empty($aParams['attrs']) && is_array($aParams['attrs']))
            $aAttrs = array_merge($aAttrs, $aParams['attrs']);

        $aInput = array(
            'type' => 'select',
            'name' => $sName,
            'tr_attrs' => array(
                'class' => 'bx-grid-filter-' . $sName,
            ),
            'attrs' => $aAttrs,
            'value' => !empty($aParams['value']) ? $aParams['value'] : '',
            'values' => !empty($aParams['values']) && is_array($aParams['values']) ? $aParams['values'] : array()
        );

        $oForm = new BxTemplFormView(array());
        return $oForm->genRow($aInput);
    }

    protected function _getSearchInput()
    {
        $aInput = array(
            'type' => 'text',
            'name' => 'keyword',
            'attrs' => array(
                'id' => 'bx-grid-search-' . $this->_sObject,
                'onKeyup' => 'javascript:$(this).off(\'keyup\'); ' . $this->_sJsObject . '.onChangeFilter(this)',
                'placeholder' => bx_html_attribute(_t('_sys_grid_search'))
            )
        );

        $oForm = new BxTemplFormView(array());
        return $oForm->genRow($aInput);
    }
}

/** @} */
