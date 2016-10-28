<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Payment Payment
 * @ingroup     TridentModules
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
        if(empty($aPending) && !is_array($aPending))
            return;

        $sMethod = 'getMenuItemsActionsRecurring';
        $oProvider = $this->_oModule->getObjectProvider($aPending['provider'], $aPending['seller_id']);
        if($oProvider === false || !$oProvider->isActive() || !method_exists($oProvider, $sMethod))
        	return;

        $this->_aObject['menu_items'] = array_merge($oProvider->$sMethod($aPending['client_id'], $aPending['seller_id'], array(
        	'id' => $aPending['id'],
            'order' => $aPending['order']
        )), $this->getMenuItemsRaw());

        $this->addMarkers(array(
			'js_object' => $this->_oModule->_oConfig->getJsObject('subscription'),
            'id' => $iPendingId
		));
    }

    public function getCode()
    {
        if(empty($this->_aObject['menu_items']) || !is_array($this->_aObject['menu_items']))
            return MsgBox(_t('_Empty'));

        return parent::getCode();
    }
}

/** @} */
