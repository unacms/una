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

require_once('BxCreditsGridWithdrawalsAdministration.php');

class BxCreditsGridWithdrawalsCommon extends BxCreditsGridWithdrawalsAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
    }

    public function performActionWithdrawRequest()
    {
        if(!$this->_bWithdraw)
            return echoJson([]);

        $sAction = 'withdraw_request';

        $this->_performActionWithProfileAmount($sAction);
    }

    public function performActionWithdrawCancel()
    {
        if(!$this->_bWithdraw)
            return echoJson([]);

        $sAction = 'withdraw_cancel';

        $iId = $this->_getId();
        if(!$iId)
            return echoJson([]);

        $aResult = $this->_oModule->processWithdrawCancel($iId);
        return echoJson($this->_onPerformAction($aResult));
    }

    protected function _getActionWithdrawRequest($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->_bWithdraw)
            return $this->_bIsApi ? [] : '';

        return parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _getActionWithdrawCancel($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->_bWithdraw || $aRow['status'] != BX_CREDITS_WITHDRAWAL_STATUS_REQUESTED)
            return $this->_bIsApi ? [] : '';

        return parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(empty($this->_iUserId) || $this->_iUserId != bx_get_logged_profile_id())
            return [];

        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `profile_id`=?", $this->_iUserId);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
