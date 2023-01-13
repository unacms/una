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

class BxAdsGridOffers extends BxTemplGrid
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

        if(($iContentId = bx_get('content_id')) !== false)
            $this->setContentId($iContentId);
    }

    public function setContentId($iContentId)
    {
        $this->_aQueryAppend['content_id'] = (int)$iContentId;
    }

    public function performActionAccept()
    {
        $iId = $this->_getId();
        if($iId && ($mixedResult = $this->_oModule->offerAccept($iId)) === true)
            $aResult = ['grid' => $this->getCode(false), 'blick' => $iId];
        else
            $aResult = ['msg' => $mixedResult !== false ? $mixedResult : _t('_bx_ads_txt_err_cannot_perform_action')];

        echoJson($aResult);
    }

    public function performActionDecline()
    {
        $iId = $this->_getId();
        if($iId && $this->_oModule->offerDecline($iId))
            $aResult = ['grid' => $this->getCode(false), 'blick' => $iId];
        else
            $aResult = ['msg' => _t('_bx_ads_txt_err_cannot_perform_action')];

        echoJson($aResult);
    }

    protected function _getCellAuthorId($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->_oTemplate->getProfileLink($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellAmount($mixedValue, $sKey, $aField, $aRow)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aContentInfo = $this->_oModule->_oDb->getContentInfoById((int)$aRow[$CNF['FIELD_OFR_CONTENT']]);

        return parent::_getCellDefault(_t_format_currency_ext($mixedValue, [
            'sign' => BxDolPayments::getInstance()->getCurrencySign((int)$aContentInfo[$CNF['FIELD_AUTHOR']])
        ]), $sKey, $aField, $aRow);
    }

    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue, BX_FORMAT_DATE, true), $sKey, $aField, $aRow);
    }
    
    protected function _getCellStatus($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t('_bx_ads_txt_offer_status_' . $mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getActionAccept ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if($aRow[$CNF['FIELD_OFR_STATUS']] != BX_ADS_OFFER_STATUS_AWAITING)
            return '';

        return parent::_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionDecline ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if($aRow[$CNF['FIELD_OFR_STATUS']] != BX_ADS_OFFER_STATUS_AWAITING)
            return '';

        return parent::_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(empty($this->_aQueryAppend['content_id']))
            return array();

        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `content_id`=?", $this->_aQueryAppend['content_id']);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    protected function _getId()
    {
        $aIds = bx_get('ids');
        if(!empty($aIds) && is_array($aIds))
            return array_shift($aIds);

        if(($iId = bx_get('id')) !== false)
            return (int)$iId;

        return false;
    }
}

/** @} */
