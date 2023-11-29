<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry menu
 */
class BxAdsMenuView extends BxBaseModTextMenuView
{
    /**
     * Array with check_sum => JS_code pairs of all JS codes 
     * which should be added to the page.
     */
    protected $_aJsCodes;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_ads';

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
            case 'add-to-cart':
                if((float)$this->_aContentInfo[$CNF['FIELD_PRICE']] == 0 || (int)$this->_aContentInfo[$CNF['FIELD_QUANTITY']] <= 0) 
                    break;

                $aCommodity = $this->_oModule->_oDb->getCommodity([
                    'sample' => 'entry_id', 
                    'entry_id' => $this->_aContentInfo[$CNF['FIELD_ID']], 
                    'type' => BX_ADS_COMMODITY_TYPE_PRODUCT, 
                    'latest' => true
                ]);
                if(empty($aCommodity) || !is_array($aCommodity))
                    break;

                $iAuthorId = (int)$this->_aContentInfo[$CNF['FIELD_AUTHOR']];
                $aJs = $oPayment->getAddToCartJs($iAuthorId, $this->MODULE, $aCommodity['id'], 1, true);
                if(empty($aJs) || !is_array($aJs))
                    break;

                list($sJsCode, $sJsMethod) = $aJs;

                $sJsCodeCheckSum = md5($sJsCode);
                if(!isset($this->_aJsCodes[$sJsCodeCheckSum]))
                    $this->_aJsCodes[$sJsCodeCheckSum] = $sJsCode;

                $bResult = true;
                $this->addMarkers(array(
                    'add_to_cart_title' => _t('_bx_ads_menu_item_title_add_to_cart', BxDolPayments::getInstance()->getCurrencySign($iAuthorId), $this->_aContentInfo[$CNF['FIELD_PRICE']]),
                    'add_to_cart_onclick' => $sJsMethod
                ));
                break;

            case 'interested':
                $bResult = $this->_aContentInfo[$CNF['FIELD_AUTHOR']] != bx_get_logged_profile_id();
                break;

            case 'make-offer':
                $bResult = $this->_oModule->isAllowedMakeOffer($this->_aContentInfo);
                break;

            case 'view-offers':
                $bResult = $this->_oModule->isAllowedViewOffers($this->_aContentInfo);
                break;

            case 'approve':
                $bResult = $this->_oModule->isAllowedApprove($this->_aContentInfo);
                break;

            case 'shipped':
                $bResult = $this->_oModule->isAllowedMarkShipped($this->_aContentInfo);
                break;

            case 'received':
                $bResult = $this->_oModule->isAllowedMarkReceived($this->_aContentInfo);
                break;

            default:
                $bResult = true;
        }

        return $bResult;
    }
}

/** @} */
