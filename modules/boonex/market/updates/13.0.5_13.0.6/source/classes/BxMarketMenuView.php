<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry menu
 */
class BxMarketMenuView extends BxBaseModTextMenuView
{
    /**
     * Array with check_sum => JS_code pairs of all JS codes 
     * which should be added to the page.
     */
    protected $_aJsCodes;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_market';

        parent::__construct($aObject, $oTemplate);

        $this->_aJsCodes = array();

        $this->addMarkers(array(
            'js_object' => $this->_oModule->_oConfig->getJsObject('entry')
        ));
    }

    public function getCode ()
    {
    	return parent::getCode() . $this->getJsCode();
    }

    public function getJsCode()
    {
        if(empty($this->_aJsCodes) || !is_array($this->_aJsCodes))
            return '';

        return implode('', $this->_aJsCodes);
    }

    protected function _isVisible ($a)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        if(!parent::_isVisible($a))
            return false;

        $oPayment = BxDolPayments::getInstance();

        $bResult = false;
        switch ($a['name']) {
            case 'download':
                if((int)$this->_aContentInfo[$CNF['FIELD_PACKAGE']] == 0) 
                    break;

                $bResult = true;
                break;

            case 'add-to-cart':
                if((float)$this->_aContentInfo[$CNF['FIELD_PRICE_SINGLE']] == 0) 
                    break;

                if(!BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_PURCHASE'])->check((int)$this->_aContentInfo[$CNF['FIELD_ID']]))
                    break;

                $iAuthorId = (int)$this->_aContentInfo[$CNF['FIELD_AUTHOR']];
                $aJs = $oPayment->getAddToCartJs($iAuthorId, $this->MODULE, $this->_aContentInfo[$CNF['FIELD_ID']], 1, true);
                if(empty($aJs) || !is_array($aJs))
                    break;

                list($sJsCode, $sJsMethod) = $aJs;

                $sJsCodeCheckSum = md5($sJsCode);
                if(!isset($this->_aJsCodes[$sJsCodeCheckSum]))
                    $this->_aJsCodes[$sJsCodeCheckSum] = $sJsCode;

                $bResult = true;
                $this->addMarkers(array(
                    'add_to_cart_title' => _t('_bx_market_menu_item_title_add_to_cart', BxDolPayments::getInstance()->getCurrencySign($iAuthorId), $this->_aContentInfo[$CNF['FIELD_PRICE_SINGLE']]),
                    'add_to_cart_onclick' => $sJsMethod
                ));
                break;

            case 'subscribe':
                if((float)$this->_aContentInfo[$CNF['FIELD_PRICE_RECURRING']] == 0) 
                    break;

                if(!BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_PURCHASE'])->check((int)$this->_aContentInfo[$CNF['FIELD_ID']]))
                    break;

                /**
                 * Current version of Credits module 
                 * doesn't support Subscription payments.
                 */
                if($oPayment->isCreditsOnly())
                    break;

                $iAuthorId = (int)$this->_aContentInfo[$CNF['FIELD_AUTHOR']];
                $aJs = $oPayment->getSubscribeJs($iAuthorId, '', $this->MODULE, $this->_aContentInfo[$CNF['FIELD_ID']], 1);
                if(empty($aJs) || !is_array($aJs))
                    break;

                list($sJsCode, $sJsMethod) = $aJs;

                $sJsCodeCheckSum = md5($sJsCode);
                if(!isset($this->_aJsCodes[$sJsCodeCheckSum]))
                    $this->_aJsCodes[$sJsCodeCheckSum] = $sJsCode;

                $bResult = true;
                $this->addMarkers(array(
                    'subscribe_title' => _t('_bx_market_menu_item_title_subscribe', BxDolPayments::getInstance()->getCurrencySign($iAuthorId), $this->_aContentInfo[$CNF['FIELD_PRICE_RECURRING']], _t('_bx_market_txt_per_' . $this->_aContentInfo[$CNF['FIELD_DURATION_RECURRING']])),
                    'subscribe_onclick' => $sJsMethod
                )); 
                break;

            default:
                $bResult = true;
        }

        return $bResult;
    }
}

/** @} */
