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
 * Entry forms helper functions
 */
class BxAdsFormsEntryHelper extends BxBaseModTextFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }

    public function getObjectFormAdd($sDisplay = false)
    {
        if(($sCategoryDisplay = $this->_oModule->getCategoryDisplay('add')) !== false)
            $sDisplay = $sCategoryDisplay;

        return parent::getObjectFormAdd($sDisplay);
    }

    public function viewDataEntry ($iContentId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $mixedResult = parent::viewDataEntry ($iContentId);
        if(!empty($mixedResult))
            return $mixedResult;

        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

        $sDisplay = false;
        if(!empty($aContentInfo[$CNF['FIELD_CATEGORY']]) && ($sCategoryDisplay = $this->_oModule->getCategoryDisplay('view', $aContentInfo[$CNF['FIELD_CATEGORY']])) !== false)
            $sDisplay = $sCategoryDisplay;

        $oForm = $this->getObjectFormView($sDisplay);
        if(!$oForm)
            return '';

        $oForm->initChecker($aContentInfo);

        if(!empty($CNF['FIELD_TEXT']) &&  !$oForm->isInputVisible($CNF['FIELD_TEXT']))
            $s = '';
        else
            $s = $this->_oModule->_oTemplate->entryText($aContentInfo);
        
        return bx_is_api() ? [bx_api_get_block('entity_text', $s)] : $s;
    }

    public function onDataAddAfter($iAccountId, $iContentId)
    {
        $s = parent::onDataAddAfter($iAccountId, $iContentId);
        if(!empty($s))
            return $s;

        $this->_oModule->serviceUpdateCategoriesStats($iContentId);

        return '';
    }

    public function onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm)
    {
        $s = parent::onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm);
        if(!empty($s))
            return $s;

        $this->_oModule->serviceUpdateCategoriesStats($aContentInfo);

        return '';
    }

    public function onDataDeleteAfter ($iContentId, $aContentInfo, $oProfile)
    {
        $s = parent::onDataDeleteAfter ($iContentId, $aContentInfo, $oProfile);
        if(!empty($s))
            return $s;

        $this->_oModule->serviceUpdateCategoriesStats($aContentInfo);

        return '';
    }
    
    public function redirectAfterAdd($aContentInfo, $sUrl = '')
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if($this->_oModule->_oConfig->isPromotion() && (float)$aContentInfo[$CNF['FIELD_BUDGET_TOTAL']] > 0) {
            $aCommodity = $this->_oModule->_oDb->getCommodity([
                'sample' => 'entry_id', 
                'entry_id' => $aContentInfo[$CNF['FIELD_ID']], 
                'type' => BX_ADS_COMMODITY_TYPE_PROMOTION, 
                'latest' => true
            ]);

            if(!empty($aCommodity) && is_array($aCommodity)) {
                $oPayments = BxDolPayments::getInstance();

                $iVendorId = (int)$oPayments->getOption('site_admin');
                $aResult = $oPayments->addToCart($iVendorId, $this->_oModule->_oConfig->getId(), $aCommodity['id'], 1);
                if(isset($aResult['code']) && (int)$aResult['code'] == 0){
                    $sUrl = $oPayments->getCartUrl($iVendorId);
                    if (bx_is_api())
                        return [bx_api_get_block('redirect', ['uri' => bx_api_get_relative_url($sUrl), 'timeout' => 1000])];
                }
            }
        }

        return parent::redirectAfterAdd($aContentInfo, $sUrl);
    }

    protected function redirectAfterEdit($aContentInfo, $sUrl = '')
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        
        if($this->_oModule->_oConfig->isPromotion() && (float)$aContentInfo[$CNF['FIELD_BUDGET_TOTAL']] > 0) {
            $aCommodity = $this->_oModule->_oDb->getCommodity([
                'sample' => 'entry_id', 
                'entry_id' => $aContentInfo[$CNF['FIELD_ID']], 
                'type' => BX_ADS_COMMODITY_TYPE_PROMOTION, 
                'unpaid' => true,
            ]);

            if(!empty($aCommodity) && is_array($aCommodity)) {
                $oPayments = BxDolPayments::getInstance();

                $iVendorId = (int)$oPayments->getOption('site_admin');
                $aResult = $oPayments->addToCart($iVendorId, $this->_oModule->_oConfig->getId(), $aCommodity['id'], 1);
                if(isset($aResult['code']) && (int)$aResult['code'] == 0){
                    $sUrl = $oPayments->getCartUrl($iVendorId);
                    if (bx_is_api())
                        return bx_api_get_block('redirect', ['uri' => bx_api_get_relative_url($sUrl), 'timeout' => 1000]);
                }
            }
        }

        return parent::redirectAfterEdit($aContentInfo, $sUrl);
    }
}

/** @} */
