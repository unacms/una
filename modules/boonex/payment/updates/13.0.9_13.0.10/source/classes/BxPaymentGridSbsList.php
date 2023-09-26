<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 * 
 * @{
 */

require_once('BxPaymentGridSbsAdministration.php');

class BxPaymentGridSbsList extends BxPaymentGridSbsAdministration
{
	protected $_aProvidersAttached;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sManageType = 'common';
        $this->_aProvidersAttached = array();
    }

    public function getCode ($isDisplayHeader = true)
    {
        if(empty($this->_aQueryAppend['client_id']) || $this->_aQueryAppend['client_id'] != bx_get_logged_profile_id())
            return '';

        return parent::getCode($isDisplayHeader);
    }

    protected function _getActionActions ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
    	$sJsCode = '';
    	if(!empty($aRow['seller_id']) && !empty($aRow['provider']) && !in_array($aRow['provider'], $this->_aProvidersAttached)) {
    		$oProvider = $this->_oModule->getObjectProvider($aRow['provider'], $aRow['seller_id']);
    		if($oProvider) {
    			$oProvider->addJsCss();
    			 
    			$sMethodName = 'getJsCode';
    			if(method_exists($oProvider, $sMethodName))
    				$sJsCode = $oProvider->$sMethodName();

    			$this->_aProvidersAttached[] = $aRow['provider'];
    		}
    	}

    	unset($a['attr']['bx_grid_action_single']);
    	$a['attr'] = array_merge($a['attr'], array(
    		"onclick" => "bx_menu_popup('" . $this->_oModule->_oConfig->getObject('menu_sbs_actions') . "', this, {id: 'bx-payment-subscription-" . $aRow['id'] . "'}, {id: " . $aRow['id'] . ", grid: '" . $this->_sObject . "'});"
    	));

        return $sJsCode . parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

	protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
		if(empty($this->_aQueryAppend['client_id']))
			return array();

		$this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `client_id`=?", $this->_aQueryAppend['client_id']);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
