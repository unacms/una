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
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
    }

    protected function _getActionActions ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
    	unset($a['attr']['bx_grid_action_single']);
    	$a['attr'] = array_merge($a['attr'], array(
    		"onclick" => "bx_menu_popup('" . $this->_oModule->_oConfig->getObject('menu_sbs_actions') . "', this, {id: 'bx-payment-subscription-" . $aRow['id'] . "'}, {id: " . $aRow['id'] . "});"
    	));

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
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
