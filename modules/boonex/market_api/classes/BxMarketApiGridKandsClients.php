<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    MarketApi MarketApi
 * @ingroup     TridentModules
 *
 * @{
 */

require_once('BxMarketApiGridKands.php');

class BxMarketApiGridKandsClients extends BxMarketApiGridKands
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_market_api';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);
    	if(!$oTemplate)
			$oTemplate = $this->_oModule->_oTemplate;

        parent::__construct ($aOptions, $oTemplate);

        $this->_sFormClass = 'FormAddClients';
        $this->_sFormTemplate = 'kands_add_form_clients.html';

        $this->_iUserId = bx_get_logged_profile_id();
    }

	protected function _getCellUserId($mixedValue, $sKey, $aField, $aRow)
    {
    	$oProfile = BxDolProfile::getInstance($mixedValue);
        if (!$oProfile) 
            $oProfile = BxDolProfileUndefined::getInstance();

    	$sProfile = $oProfile->getDisplayName();

        $mixedValue = $this->_oTemplate->parseHtmlByName('bx_a.html', array(
            'href' => $oProfile->getUrl(),
            'title' => bx_html_attribute($sProfile),
            'bx_repeat:attrs' => array(),
        	'content' => $sProfile
        ));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

	protected function _getDataArray($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

		$this->_aOptions['source'] = BxDolService::call($CNF['OAUTH'], 'get_clients_by', array(array('type' => 'parent_id', 'parent_id' => $this->_iUserId)));

        return BxTemplGrid::_getDataArray($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

	protected function _getKeyParentId(&$oForm)
    {
    	return $this->_iUserId;
    }

    protected function _getKeyUserId(&$oForm)
    {
    	return $oForm->getCleanValue('user_id');
    }
}

/** @} */
