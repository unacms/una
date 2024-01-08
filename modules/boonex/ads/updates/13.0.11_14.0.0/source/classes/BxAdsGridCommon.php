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

class BxAdsGridCommon extends BxBaseModTextGridCommon
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_ads';

        parent::__construct ($aOptions, $oTemplate);
    }

    protected function _getActionPromotion($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = [])
    {
    	if(!$this->_oModule->_oConfig->isPromotion())
            return '';

    	$CNF = &$this->_oModule->_oConfig->CNF;

        $sUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY_PROMOTION'] . '&id=' . $aRow[$CNF['FIELD_ID']]));

        if(bx_is_api()) {
            $a['type'] = 'link';
            $a['name'] = $sKey;
            $a['url'] = bx_api_get_relative_url($sUrl);
            return $a;
        }

    	$a['attr'] = array_merge($a['attr'], [
            "onclick" => "window.open('" . $sUrl . "','_self');"
    	]);

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionEditBudget($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = [])
    {
    	if(!$this->_oModule->_oConfig->isPromotion())
            return '';

    	$CNF = &$this->_oModule->_oConfig->CNF;

        $sUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_EDIT_ENTRY_BUDGET'] . '&id=' . $aRow[$CNF['FIELD_ID']]));

        if(bx_is_api()) {
            $a['type'] = 'link';
            $a['name'] = $sKey;
            $a['url'] = bx_api_get_relative_url($sUrl);
            return $a;
        }

    	$a['attr'] = array_merge($a['attr'], [
            "onclick" => "window.open('" . $sUrl . "','_self');"
    	]);

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
}

/** @} */
