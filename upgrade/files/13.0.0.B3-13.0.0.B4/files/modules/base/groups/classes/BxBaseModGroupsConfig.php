<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Groups Groups
 * @ingroup     UnaModules
 * 
 * @{
 */

bx_import('BxDolInformer');

class BxBaseModGroupsConfig extends BxBaseModProfileConfig
{
    protected $_aHtmlIds;

    protected $_aCurrency;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_aMenuItems2MethodsActions = array (
            'join-group-profile' => 'checkAllowedFanAdd',
            'profile-fan-add' => 'checkAllowedFanAdd',
            'profile-fan-remove' => 'checkAllowedFanRemove',
            'profile-subscribe-add' => 'checkAllowedSubscribeAdd',
            'profile-subscribe-remove' => 'checkAllowedSubscribeRemove',
            'profile-actions-more' => 'checkAllowedViewMoreMenu',
            'convos-compose' => 'checkAllowedCompose',
        );

        $sHtmlPrefix = str_replace('_', '-', $this->_sName);
        $this->_aHtmlIds = array(
            'popup_price' => $sHtmlPrefix . '-popup-price'
        );

        $oPayments = BxDolPayments::getInstance();
        $this->_aCurrency = array(
            'code' => $oPayments->getOption('default_currency_code'),
            'sign' => $oPayments->getOption('default_currency_sign')
        );
    }

    public function getHtmlIds($sKey = '')
    {
        if(empty($sKey))
            return $this->_aHtmlIds;

        return isset($this->_aHtmlIds[$sKey]) ? $this->_aHtmlIds[$sKey] : '';
    }

    public function getCurrency()
    {
    	return $this->_aCurrency;
    }

    public function isFans()
    {
        return true;
    }

    public function isAdmins()
    {
        return $this->isFans() && !empty($this->CNF['TABLE_ADMINS']);
    }

    public function isPaidJoin()
    {
        return isset($this->CNF['PARAM_PAID_JOIN_ENABLED']) && $this->CNF['PARAM_PAID_JOIN_ENABLED'] === true;
    }

    public function isInternalNotifications()
    {
        return !isset($this->CNF['PARAM_USE_IN']) || getParam($this->CNF['PARAM_USE_IN']) == 'on';
    }

    public function getPriceName($sName)
    {
        return uriGenerate($sName, $this->CNF['TABLE_PRICES'], $this->CNF['FIELD_PRICE_NAME'], ['lowercase' => false]);
    }
}

/** @} */
