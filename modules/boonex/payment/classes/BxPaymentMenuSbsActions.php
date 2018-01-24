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


class BxPaymentMenuSbsActions extends BxTemplMenu
{
    protected $_sModule;
    protected $_oModule;
    
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_payment';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $oTemplate);

        $iPendingId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if(empty($iPendingId))
            return;

        $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
        if(empty($aPending) || !is_array($aPending))
            return;

        $sMethod = 'getMenuItemsActionsRecurring';
        $oProvider = $this->_oModule->getObjectProvider($aPending['provider'], $aPending['seller_id']);
        if($oProvider === false || !$oProvider->isActive() || !method_exists($oProvider, $sMethod))
        	return;

        $this->_aObject['menu_items'] = array_merge($oProvider->$sMethod($aPending['client_id'], $aPending['seller_id'], array(
        	'id' => $aPending['id'],
            'order' => $aPending['order']
        )), $this->getMenuItemsRaw());

        $aMarkers = array(
			'js_object' => $this->_oModule->_oConfig->getJsObject('subscription'),
            'id' => $iPendingId
		);

		if(bx_get('grid') !== false)
		    $aMarkers['grid'] = bx_process_input(bx_get('grid'));

        $this->addMarkers($aMarkers);
    }

    public function getCode()
    {
        if(empty($this->_aObject['menu_items']) || !is_array($this->_aObject['menu_items']))
            return MsgBox(_t('_Empty'));

        return parent::getCode();
    }
}

/** @} */
