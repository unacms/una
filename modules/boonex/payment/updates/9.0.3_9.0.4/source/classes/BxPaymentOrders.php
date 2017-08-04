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

class BxPaymentOrders extends BxBaseModPaymentOrders
{
    protected $_sLangsPrefix;

    function __construct()
    {
    	$this->MODULE = 'bx_payment';

    	parent::__construct();

        $this->_sLangsPrefix = $this->_oModule->_oConfig->getPrefix('langs');
    }

	/*
     * Service methods
     */
	public function serviceGetBlockOrders($sType = '', $iUserId = BX_PAYMENT_EMPTY_ID)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

    	if(empty($sType))
    		$sType = bx_get('type') !== false ? bx_process_input(bx_get('type')) : BX_PAYMENT_ORDERS_TYPE_PROCESSED;

    	if(!$this->_oModule->isLogged())
            return MsgBox(_t($this->_sLangsPrefix . 'err_required_login'));

        $iUserId = $iUserId != BX_PAYMENT_EMPTY_ID ? $iUserId : $this->_oModule->getProfileId();
        if($sType == BX_PAYMENT_ORDERS_TYPE_PROCESSED)
            $this->_oModule->_oDb->updateOrdersProcessed(array('new' => 0), array('seller_id' => $iUserId, 'new' => 1));

        $this->_oModule->setSiteSubmenu('menu_dashboard', 'system', 'dashboard-orders');

        $sBlockSubmenu = $this->_oModule->_oConfig->getObject('menu_orders_submenu');
        $oBlockSubmenu = BxDolMenu::getObjectInstance($sBlockSubmenu);
        if($oBlockSubmenu)
            $oBlockSubmenu->setSelected($this->MODULE, 'orders-' . $sType);

        return array(
        	'title' => _t($this->_sLangsPrefix . 'page_block_title_orders_' . $sType),
        	'content' => $this->_oModule->_oTemplate->displayBlockOrders($sType, $iUserId),
            'menu' => $oBlockSubmenu
        );
    }

    /**
     * Check transaction(s) in database which satisty all conditions.
     *
     * @param array $aConditions an array of pears('key' => 'value'). Available keys are the following:
     * a. license - internal license (string)
     * b. client_id - client's ID (integer)
     * c. seller_id - seller's ID (integer)
     * d. module_id - modules's where the purchased product is located. (integer)
     * e. item_id - item id in the database. (integer)
     * f. date - the date when the payment was processed(UNIXTIME STAMP)
     *
     * @return array of transactions. Each transaction has full info(client ID, seller ID, external transaction ID, date and so on)
     */
    public function serviceGetOrdersInfo($aConditions)
    {
        if(empty($aConditions) || !is_array($aConditions))
            return array();

        return $this->_oModule->_oDb->getOrderProcessed(array('type' => 'mixed', 'conditions' => $aConditions));
    }

    /**
     * Check pending transaction(s) in database which satisty all conditions.
     *
     * @param array $aConditions an array of pears('key' => 'value'). The most useful keys are the following:
     * a. client_id - client's ID (integer)
     * b. seller_id - seller's ID (integer)
     * c. type - transaction type: single or recurring (string)
     * d. amount - transaction amount (float)
     * e. order - order ID received from payment provider (string)
     * f. provider - payment provider name (string)
     * g. date - the date when the payment was established(UNIXTIME STAMP)
     * h. processed - whether the payment was processed or not (integer, 0 or 1)
     *
     * @return array of pending transactions. Each transaction has full info(client ID, seller ID, type, date and so on)
     */
    public function serviceGetPendingOrdersInfo($aConditions)
    {
        if(empty($aConditions) || !is_array($aConditions))
            return array();

        return $this->_oModule->_oDb->getOrderPending(array('type' => 'mixed', 'conditions' => $aConditions));
    }

	public function addOrder($aData)
    {
        $iSellerId = isset($aData['seller_id']) ? (int)$aData['seller_id'] : $this->_oModule->getProfileId();
        if($iSellerId == $aData['client_id'])
            return array('msg' => $this->_sLangsPrefix . 'err_self_purchase');

        $sOrder = trim($aData['order']);
        if(empty($sOrder))
            return array('msg' => $this->_sLangsPrefix . 'form_processed_input_order_err');

        $aCartInfo = array('vendor_id' => $iSellerId, 'items_price' => 0, 'items' => array());
        foreach($aData['items'] as $aItem) {
        	if(!$this->_oModule->isAllowedSell(array('module_id' => $aData['module_id'], 'item_id' => $aItem['id']), true))
        		continue;

            $aCartInfo['items_price'] += $this->_oModule->_oConfig->getPrice($aData['type'], $aItem) * $aItem['quantity'];
            $aCartInfo['items'][] = array(
                'vendor_id' => $iSellerId,
                'module_id' => $aData['module_id'],
                'id' => $aItem['id'],
                'quantity' => $aItem['quantity']
            );
        }

        $iPendingId = $this->_oModule->_oDb->insertOrderPending($aData['client_id'], $aData['type'], $aData['provider'], $aCartInfo);
        $this->_oModule->_oDb->updateOrderPending($iPendingId, array(
            'order' => $sOrder,
            'error_code' => $aData['error_code'],
            'error_msg' => $aData['error_msg']
        ));

        return (int)$iPendingId;
    }

    public function getOrder($sType, $iId)
    {
        return $this->_oModule->_oTemplate->displayOrder($sType, $iId);
    }

    public function cancel($sType, $iOrderId)
    {
    	$sMethodName = 'getOrder' . bx_gen_method_name($sType);
    	$aOrder = $this->_oModule->_oDb->$sMethodName(array('type' => 'id', 'id' => $iOrderId));
		if(empty($aOrder) || !is_array($aOrder))
	    	return true;

		if($sType == BX_PAYMENT_ORDERS_TYPE_PROCESSED && !$this->_oModule->callUnregisterCartItem((int)$aOrder['module_id'], array($aOrder['client_id'], $aOrder['seller_id'], $aOrder['item_id'], $aOrder['item_count'], $aOrder['order'], $aOrder['license'])))
			return false;

		$sMethodName = 'deleteOrder' . bx_gen_method_name($sType);
		if(!$this->_oModule->_oDb->$sMethodName($iOrderId))
			return false;

        return true;
    }
}

/** @} */
