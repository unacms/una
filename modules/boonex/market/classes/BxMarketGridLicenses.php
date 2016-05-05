<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Market Market
 * @ingroup     TridentModules
 * 
 * @{
 */

class BxMarketGridLicenses extends BxTemplGrid
{
	protected $MODULE;
	protected $_oModule;

    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_market';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);
    	if(!$oTemplate)
			$oTemplate = $this->_oModule->_oTemplate;

        parent::__construct ($aOptions, $oTemplate);

        $this->_sDefaultSortingOrder = 'DESC';

        $iProfileId = bx_get_logged_profile_id();
        if($iProfileId !== false)
            $this->_aQueryAppend['profile_id'] = (int)$iProfileId;
    }

	public function performActionReset()
    {
    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) 
        	return echoJson(array());

		$iAffected = 0;
		$aAffected = array();
		foreach($aIds as $iId)
			if($this->_oModule->_oDb->updateLicense(array('domain' => ''), array('id' => $iId, 'profile_id' => $this->_aQueryAppend['profile_id']))) {
				$aAffected[] = $iId;
            	$iAffected++;
			}

        echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aAffected) : array('msg' => _t('_bx_market_grid_action_err_cannot_perform')));
    }

	protected function _getCellProduct($mixedValue, $sKey, $aField, $aRow)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_VIEW_ENTRY'] . $aRow['product_id']);

        $mixedValue = $this->_oTemplate->parseHtmlByName('product_link.html', array(
            'href' => $sUrl,
            'title' => bx_html_attribute($mixedValue),
            'content' => $mixedValue
        ));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

	protected function _getCellType($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t('_bx_market_grid_txt_lcs_type_' . $mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

	protected function _getCellExpired($mixedValue, $sKey, $aField, $aRow)
    {
    	$mixedValue = (int)$mixedValue != 0 ? bx_time_js($mixedValue): _t('_bx_market_grid_txt_lcs_never');
    		
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

	protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
		if(empty($this->_aQueryAppend['profile_id']))
			return array();

		$this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tl`.`profile_id`=?", $this->_aQueryAppend['profile_id']);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
