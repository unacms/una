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

class BxAdsGridLicensesAdministration extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->_sModule = 'bx_ads';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);
    	if(!$oTemplate)
            $oTemplate = $this->_oModule->_oTemplate;

        parent::__construct ($aOptions, $oTemplate);

        $this->_sDefaultSortingOrder = 'DESC';
        $this->_aQueryReset = array($this->_aOptions['order_get_field'], $this->_aOptions['order_get_dir'], $this->_aOptions['paginate_get_start'], $this->_aOptions['paginate_get_per_page']);

        $iProfileId = bx_get_logged_profile_id();
        if($iProfileId !== false)
            $this->_aQueryAppend['profile_id'] = (int)$iProfileId;
    }

    protected function _getCellProfileId($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->_oTemplate->getProfileLink($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellEntry($mixedValue, $sKey, $aField, $aRow)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        $mixedValue = $this->_oModule->_oTemplate->getEntryLink(array(
            $CNF['FIELD_ID'] => $aRow['entry_id'],
            $CNF['FIELD_TITLE'] => $mixedValue,
        ));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue, BX_FORMAT_DATE, true), $sKey, $aField, $aRow);
    }
}

/** @} */
