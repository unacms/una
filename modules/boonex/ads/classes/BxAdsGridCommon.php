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

    protected function _getCellStatusAdmin($mixedValue, $sKey, $aField, $aRow)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!empty($CNF['T']['filter_item_' . $mixedValue]))
            $mixedValue = $CNF['T']['filter_item_' . $mixedValue];
        else
            $mixedValue = '_undefined';

        return parent::_getCellDefault(_t($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellSwitcher ($mixedValue, $sKey, $aField, $aRow)
    {
        if(!$this->_switcherState2Checked($aRow['status_admin']))
            return parent::_getCellDefault('', $sKey, $aField, $aRow);

        return parent::_getCellSwitcher ($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _isRowDisabled($aRow)
    {
        if(parent::_isRowDisabled($aRow))
            return true;

        return !$this->_switcherState2Checked($aRow['status_admin']);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `author`=?", bx_get_logged_profile_id());

        return $this->__getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
