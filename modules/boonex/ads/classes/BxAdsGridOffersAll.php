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

class BxAdsGridOffersAll extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    protected $_iProfileId;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sModule = 'bx_ads';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);
    	if(!$oTemplate)
            $oTemplate = $this->_oModule->_oTemplate;

        parent::__construct ($aOptions, $oTemplate);
        
        $this->_sDefaultSortingOrder = 'DESC';

        $this->_iProfileId = bx_get_logged_profile_id();
    }

    protected function _getCellContentId($mixedValue, $sKey, $aField, $aRow)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $mixedValue = $this->_oModule->_oTemplate->getEntryLink(array(
            $CNF['FIELD_ID'] => $aRow['content_id'],
            $CNF['FIELD_TITLE'] => $aRow['content_title'],
        ));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getActionView($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY_OFFERS'], array('id' => $aRow['content_id'])));

    	$a['attr'] = array_merge($a['attr'], array(
            "onclick" => "window.open('" . $sUrl . "','_self');"
    	));
        unset($a['attr']['bx_grid_action_single']);

        return parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _getDataSqlOrderClause ($sOrderByFilter, $sOrderField, $sOrderDir, $bFieldsOnly = false)
    {
        return " GROUP BY `to`.`content_id` " . parent::_getDataSqlOrderClause ($sOrderByFilter, $sOrderField, $sOrderDir, $bFieldsOnly);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `te`.`author`=?", $this->_iProfileId);

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
