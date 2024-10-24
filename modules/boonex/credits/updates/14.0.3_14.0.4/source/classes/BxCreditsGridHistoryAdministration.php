<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Credits Credits
 * @ingroup     UnaModules
 * 
 * @{
 */

require_once('BxCreditsGrid.php');

class BxCreditsGridHistoryAdministration extends BxCreditsGrid
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sDefaultSortingOrder = 'DESC';
    }

    public function performActionGrant()
    {
        $sAction = 'grant';

        $this->_performActionWithProfileAmount($sAction);
    }

    protected function _getCellDirection($mixedValue, $sKey, $aField, $aRow)
    {
        if(in_array($mixedValue, [BX_CREDITS_DIRECTION_IN, BX_CREDITS_DIRECTION_OUT]))
            $mixedValue = _t('_bx_credits_txt_direction_' . $mixedValue);

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellFirstPid($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_getProfile($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellSecondPid($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_getProfile($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellAmount($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->convertC2S($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellOrder($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellDate($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue, BX_FORMAT_DATE, true), $sKey, $aField, $aRow);
    }

    protected function _getCellCleared($mixedValue, $sKey, $aField, $aRow)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if($aRow[$CNF['FIELD_H_DIRECTION']] == BX_CREDITS_DIRECTION_IN) {
            if(empty($mixedValue)) {
                $iPeriod = (int)$aRow['wdw_clearing'];
                if($iPeriod == 0) {
                    $iPeriod = $this->_oModule->_oConfig->getWithdrawClearing();
                    if($iPeriod == 0)
                        $iPeriod = 1;
                }

                $oDate = date_create('@' . $aRow[$CNF['FIELD_H_DATE']]);
                date_add($oDate, new DateInterval('P' . $iPeriod . 'D'));
                $mixedValue = date_format($oDate, 'U');
            }

            $mixedValue = bx_time_js($mixedValue, BX_FORMAT_DATE, true);
        }
        else 
            $mixedValue = '';

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oModule->_oTemplate->addJs([
            'jquery.form.min.js'
        ]);

        $this->_oModule->_oTemplate->addCss([
            'main.css'
        ]);

        $oForm = new BxTemplFormView([]);
        $oForm->addCssJs();
    }
}

/** @} */
