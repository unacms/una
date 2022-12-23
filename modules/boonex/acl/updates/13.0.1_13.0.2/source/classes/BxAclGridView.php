<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    PaidLevels Paid Levels
 * @ingroup     UnaModules
 * 
 * @{
 */

require_once('BxAclGridLevels.php');

class BxAclGridView extends BxAclGridLevels
{
    /**
     * Array with check_sum => JS_code pairs of all JS codes 
     * which should be added to the page.
     */
    protected $_aJsCodes;

    protected $_iOwner;
    protected $_oPayment;
    protected $_bTypeSingle;
    protected $_bTypeRecurring;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_aJsCodes = array();

        $this->_iOwner = $this->_oModule->_oConfig->getOwner();
        $this->_oPayment = BxDolPayments::getInstance();
        $this->_bTypeSingle = $this->_oPayment->isAcceptingPayments($this->_iOwner, BX_PAYMENT_TYPE_SINGLE);
        $this->_bTypeRecurring = !$this->_oPayment->isCreditsOnly() && $this->_oPayment->isAcceptingPayments($this->_iOwner, BX_PAYMENT_TYPE_RECURRING);
    }

    public function performActionChoose()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aIds = $this->_getIds();
        if($aIds === false)
            return echoJson(array());

        $aItem = $this->_oModule->_oDb->getPrices(array('type' => 'by_id', 'value' => $aIds[0]));
        if(!is_array($aItem) || empty($aItem) || (float)$aItem['price'] != 0)
        	return echoJson(array());

        $aResult = array();
        $iUserId = bx_get_logged_profile_id();
        if(BxDolAcl::getInstance()->setMembership($iUserId, $aItem['level_id'], array('period' => $aItem['period'], 'period_unit' => $aItem['period_unit']), true))
            $aResult = array('grid' => $this->getCode(false), 'blink' => $aItem['id'], 'msg' => _t('_bx_acl_msg_performed'));
        else
            $aResult = array('msg' => _t('_bx_acl_err_cannot_perform'));

        return echoJson($aResult);
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

    protected function _getCellLevelIcon($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_oModule->_oTemplate->displayLevelIcon($mixedValue);
    	return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getActionChoose ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if((float)$aRow['price'] != 0 || BxDolAcl::getInstance()->isMemberLevelInSet(array($aRow['level_id'])))
            return '';

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _getActionBuy ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if((float)$aRow['price'] == 0 || !$this->_bTypeSingle || ($this->_bTypeRecurring && getParam($CNF['PARAM_RECURRING_PRIORITIZE']) == 'on' && !$this->_isLifetime($aRow)))
            return '';

    	$aJs = $this->_oPayment->getAddToCartJs($this->_iOwner, $this->MODULE, $aRow['id'], 1, true);
    	if(!empty($aJs) && is_array($aJs)) {
            list($sJsCode, $sJsMethod) = $aJs;

            $sJsCodeCheckSum = md5($sJsCode);
            if(!isset($this->_aJsCodes[$sJsCodeCheckSum]))
                $this->_aJsCodes[$sJsCodeCheckSum] = $sJsCode;

            $a['attr'] = array(
                'title' => bx_html_attribute(_t('_bx_acl_grid_action_buy_title')),
                'onclick' => $sJsMethod
            );
    	}

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _getActionSubscribe ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if((float)$aRow['price'] == 0 || !$this->_bTypeRecurring || ($this->_bTypeSingle && $this->_isLifetime($aRow)))
            return '';

    	$aJs = $this->_oPayment->getSubscribeJs($this->_iOwner, '', $this->MODULE, $aRow['id'], 1);
        if(!empty($aJs) && is_array($aJs)) {
            list($sJsCode, $sJsMethod) = $aJs;

            $sJsCodeCheckSum = md5($sJsCode);
            if(!isset($this->_aJsCodes[$sJsCodeCheckSum]))
                $this->_aJsCodes[$sJsCodeCheckSum] = $sJsCode;

            $a['attr'] = array(
                'title' => bx_html_attribute(_t('_bx_acl_grid_action_subscribe_title')),
                'onclick' => $sJsMethod
            );
        }

    	return parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _isLifetime($aRow)
    {
        return empty($aRow['period']) && empty($aRow['period_unit']);
    }
}

/** @} */
