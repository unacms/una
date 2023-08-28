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


class BxAclGridLevels extends BxTemplGrid
{
	protected $MODULE;
	protected $_oModule;

	protected $_aPeriodUnits;

    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_acl';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct ($aOptions, $oTemplate);

        $CNF = $this->_oModule->_oConfig->CNF;

        $this->_aPeriodUnits = BxDolForm::getDataItems($CNF['OBJECT_FORM_PRELISTS_PERIOD_UNITS']);
    }

	protected function _getCellPeriod($mixedValue, $sKey, $aField, $aRow)
    {
        if((int)$mixedValue == 0)
            $mixedValue = _t('_bx_acl_txt_lifetime');
        else
            $mixedValue = _t('_bx_acl_txt_n_unit', $mixedValue, _t($this->_aPeriodUnits[$aRow['period_unit']]));

    	return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

	protected function _getCellPrice($mixedValue, $sKey, $aField, $aRow)
    {
        if((float)$mixedValue != 0) {
            $aCurrency = $this->_oModule->_oConfig->getCurrency();

            $mixedValue = _t('_bx_acl_grid_column_price_value', $aCurrency['sign'], $mixedValue);
        }
        else 
            $mixedValue = _t('_bx_acl_txt_free');

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

	protected function _getCellTrial($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = (int)$mixedValue != 0 ? _t('_bx_acl_txt_n_unit', $mixedValue, _t($this->_aPeriodUnits['day'])) : _t('_bx_acl_txt_none');

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getIds()
    {
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId) 
                return false;

            $aIds = array($iId);
        }

        return $aIds;
    }
}

/** @} */
