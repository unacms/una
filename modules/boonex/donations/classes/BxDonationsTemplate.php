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

class BxDonationsTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);

        $this->aMethodsToCallAddJsCss[] = 'getBlockMake';
    }

    public function addCssJs()
    {
        parent::addCssJs();

        $this->addJs(array('main.js'));
    }

    public function getIncludeCssJs()
    {
        return '';
    }

    public function getBlockMake($sSelected = 'single')
    {
        $CNF = &$this->_oConfig->CNF;

        $oPayment = BxDolPayments::getInstance();
        $iPaymentSeller = $this->_oConfig->getOwner();
        $sPaymentModule = $this->_oConfig->getName();

        $sJsCode = '';
        $sJsObject = $this->_oConfig->getJsObject('main');
        $sHtmlIdBTypeLink = $this->_oConfig->getHtmlIds('link_billing_type');

        $bShowTitle = $this->_oConfig->isShowTitle();
        $aPeriodUnits = $this->_oConfig->getPeriodUnits();

        $aMenuBillingTypes = array();
        $aTmplVarsBillingTypes = array();
        
        $aBillingTypes = $this->_oConfig->getBillingTypes();
        foreach($aBillingTypes as $sBillingType) {
            $aMenuBillingTypes[] = array('name' => $sHtmlIdBTypeLink . $sBillingType, 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . ".changeType(this, '" . $sBillingType . "')", 'target' => '_self', 'title' => _t('_bx_donations_txt_do_' . $sBillingType));

            $aTmplVarsTypes = array();

            $aTypes = $this->_oDb->getTypes(array('type' => 'by_btype_' . $sBillingType));
            foreach($aTypes as $aType) {
                $sDuration = '';

                switch($sBillingType) {
                    case BX_DONATIONS_BTYPE_SINGLE:
                        $aJs = $oPayment->getAddToCartJs($iPaymentSeller, $sPaymentModule, $aType[$CNF['FIELD_ID']], 1, true);
                        if(empty($aJs) || !is_array($aJs))
                            continue 2;
                        break;

                    case BX_DONATIONS_BTYPE_RECURRING:
                        $aJs = $oPayment->getSubscribeJs($iPaymentSeller, '', $sPaymentModule, $aType[$CNF['FIELD_ID']], 1);
                        if(empty($aJs) || !is_array($aJs))
                            continue 2;

                        if((int)$aType[$CNF['FIELD_PERIOD']] > 1)
                            $sDuration .= $aType[$CNF['FIELD_PERIOD']] . ' ';
                        $sDuration .= _t($aPeriodUnits[$aType[$CNF['FIELD_PERIOD_UNIT']]]);
                        break;
                }

                list($sJsCode, $sOnclick) = $aJs;

                $sAmount = _t_format_currency($aType[$CNF['FIELD_PRICE']]);
                $sAmount = _t('_bx_donations_txt_amount_' . $sBillingType, $sAmount, $sDuration);

                $aTmplVarsTypes[] = array(
                    'onclick' => $sOnclick,
                    'bx_if:show_title' => array(
                        'condition' => $bShowTitle,
                        'content' => array(
                            'title' => _t($aType[$CNF['FIELD_TITLE']])
                        )
                    ),
                    'amount' => $sAmount
                );
            }

            $aTmplVarsBillingTypes[] = array(
                'class' => $sBillingType . ($sBillingType == $sSelected ? ' active' : ''),
                'bx_repeat:types' => $aTmplVarsTypes
            );
        }

        $oMenuBillingTypes = new BxTemplMenu(array('template' => 'menu_buttons_hor.html', 'menu_id'=> $this->_oConfig->getHtmlIds('menu_billing_types'), 'menu_items' => $aMenuBillingTypes));
        $oMenuBillingTypes->setSelected('', $sHtmlIdBTypeLink . $sSelected);

        $sTmplName = 'block_make.html';
        $aTmplVars = array(
            'menu_billing_type' => $oMenuBillingTypes->getCode(),
            'bx_repeat:billing_types' => $aTmplVarsBillingTypes,
            'js_code' => $sJsCode . $this->getJsCode('main')
        );

        $sResult = null;
        bx_alert($this->_oConfig->getName(), 'donate', 0, 0, array(
            'tmpl_name' => &$sTmplName,
            'tmpl_vars' => &$aTmplVars,
            'override_result' => &$sResult
        ));
        if($sResult !== null)
            return $sResult;

        return $this->parseHtmlByName($sTmplName, $aTmplVars);
    }

    public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
        $aParams = array_merge(array(
            'aHtmlIds' => $this->_oConfig->getHtmlIds()
        ), $aParams);

        return parent::getJsCode($sType, $aParams, $bWrap);
    }
}

/** @} */
