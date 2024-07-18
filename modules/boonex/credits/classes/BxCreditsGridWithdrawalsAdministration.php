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

class BxCreditsGridWithdrawalsAdministration extends BxCreditsGrid
{
    protected $_bWithdraw;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sDefaultSortingOrder = 'DESC';

        $this->_bWithdraw = $this->_oModule->_oConfig->isWithdraw();
    }


    public function performActionWithdrawConfirm()
    {
        if(!$this->_bWithdraw)
            return echoJson([]);

        $sAction = 'withdraw_confirm';

        $iId = $this->_getId();
        if(!$iId)
            return echoJson([]);

        $aResult = $this->_oModule->processWithdrawConfirm($this->_iUserId, $iId);
        return echoJson($this->_onPerformAction($aResult));
    }

    protected function _getActionWithdrawConfirm($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->_bWithdraw || $aRow['status'] != BX_CREDITS_WITHDRAWAL_STATUS_REQUESTED)
            return $this->_bIsApi ? [] : '';

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _getCellProfileId($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_getProfile($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellPerformerId($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(!empty($mixedValue) ? $this->_getProfile($mixedValue) : '', $sKey, $aField, $aRow);
    }

    protected function _getCellAmount($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->convertC2S($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellOrder($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue, BX_FORMAT_DATE, true), $sKey, $aField, $aRow);
    }

    protected function _getCellConfirmed($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(!empty($mixedValue) ? bx_time_js($mixedValue, BX_FORMAT_DATE, true) : '', $sKey, $aField, $aRow);
    }

    protected function _getCellStatus($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t('_bx_credits_txt_withdrawal_status_' . $mixedValue), $sKey, $aField, $aRow);
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oModule->_oTemplate->addJs([
            'jquery.form.min.js', 
            'withdraw.js'
        ]);

        $this->_oModule->_oTemplate->addCss([
            'main.css',
            'withdraw.css'
        ]);

        $oForm = new BxTemplFormView([]);
        $oForm->addCssJs();
    }
}

/** @} */
