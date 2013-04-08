<?

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/
class BxPmtOrders {
    var $_iUserId;
    var $_oDb;
    var $_oConfig;
    var $_oTemplate;

    /*
     * Constructor.
     */
    function BxPmtOrders($iUserId, &$oDb, &$oConfig, &$oTemplate) {
        $this->_iUserId = $iUserId;
        $this->_oDb = &$oDb;
        $this->_oConfig = &$oConfig;
        $this->_oTemplate = &$oTemplate;
    }
    function getExtraJs() {
        $sJsObject = $this->_oConfig->getJsObject('orders');
        ob_start();
?>
        var <?=$sJsObject; ?> = new BxPmtOrders({
            sActionUrl: '<?=BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(); ?>',
            sObjName: '<?=$sJsObject; ?>'
        });
<?
        $sJsContent = ob_get_clean();

        return $this->_oTemplate->parseHtmlByTemplateName('script', array('content' => $sJsContent));
    }
    function getOrder($sType, $iId) {
        return $this->_oTemplate->displayOrder($sType, $iId);
    }
    function getOrders($sType, $aParams) {
        return $this->_oTemplate->displayOrders($sType, $aParams);
    }
    function getOrdersBlock($sType, $iUserId = BX_PMT_EMPTY_ID) {
        return $this->_oTemplate->displayOrdersBlock($sType, $iUserId != BX_PMT_EMPTY_ID ? $iUserId : $this->_iUserId);
    }
    function report($sType, $aOrders) {
        $sMethodName = "report" . ucfirst($sType) . "Orders";
        if(!$this->_oDb->$sMethodName($aOrders))
            return array('code' => 3, 'message' => '_payment_err_unknown');

        return array('code' => 10, 'message' => '_payment_inf_successfully_reported');
    }
    function cancel($sType, $aOrders) {
        $sMethodName = "cancel" . ucfirst($sType) . "Orders";

        if($sType == BX_PMT_ORDERS_TYPE_PROCESSED)
            foreach($aOrders as $iOrderId) {
                $aOrder = $this->_oDb->getProcessed(array('type' => 'id', 'id' => $iOrderId));
                BxDolService::call(
                    (int)$aOrder['module_id'],
                    'unregister_cart_item',
                    array(
                        $aOrder['client_id'],
                        $aOrder['seller_id'],
                        $aOrder['item_id'],
                        $aOrder['item_count'],
                        $aOrder['order_id']
                    )
                );
            }

        if(!$this->_oDb->$sMethodName($aOrders))
            return array('code' => 3, 'message' => '_payment_err_unknown');

        return array('code' => 0, 'message' => '');
    }
    function getMoreWindow() {
        return $this->_oTemplate->displayMoreWindow();
    }
    function getManualOrderWindow() {
        $sJsObject = $this->_oConfig->getJsObject('orders');
        $aModulesInfo = $this->_oDb->getModules();

        $aModules = array(
            array('key' => '0', 'value' => _t('_payment_ocaption_select'))
        );
        foreach($aModulesInfo as $aModule)
           $aModules[] = array('key' => $aModule['id'], 'value' => $aModule['title']);


        $aForm = array(
            'form_attrs' => array(
                'id' => 'pmt-manual-order-form',
                'name' => 'text_data',
                'action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'act_manual_order_submit/',
                'method' => 'post',
                'enctype' => 'multipart/form-data',
                'target' => 'pmt-manual-order-iframe'
            ),
            'inputs' => array (
                'client' => array(
                    'type' => 'text',
                    'name' => 'client',
                    'caption' => _t("_payment_fldcaption_client"),
                    'value' => ''
                ),
                'order' => array(
                    'type' => 'text',
                    'name' => 'order',
                    'caption' => _t("_payment_fldcaption_order"),
                    'value' => ''
                ),
                'module_id' => array(
                    'type' => 'select',
                    'name' => 'module_id',
                    'caption' => _t("_payment_fldcaption_module"),
                    'value' => '',
                    'values' => $aModules,
                    'attrs' => array(
                        'onchange' => 'javascript:' . $sJsObject . '.selectModule(this);'
                    )
                ),
                'add' => array(
                    'type' => 'submit',
                    'name' => 'add',
                    'colspan' => true,
                    'value' => _t("_payment_btn_add"),
                ),
            )
        );

        return $this->_oTemplate->displayManualOrderWindow($aForm);
    }
    function addManualOrder($aData) {
        if(!isset($aData['client']) || empty($aData['client']))
            return array('code' => 2, 'message' => '_payment_err_wrong_client');

        $iClientId = 0;
        if(($iClientId = $this->_oDb->userExists(process_db_input($aData['client'], BX_TAGS_STRIP))) === false)
            return array('code' => 2, 'message' => '_payment_err_wrong_client');

        if($this->_iUserId == $iClientId)
            return array('code' => 3, 'message' => '_payment_err_self_purchase');

        if(!isset($aData['order']) || empty($aData['order']))
            return array('code' => 4, 'message' => '_payment_err_wrong_order');

        $sOrder = trim(process_db_input($aData['order'], BX_TAGS_STRIP));
        if(empty($sOrder))
            return array('code' => 4, 'message' => '_payment_err_wrong_order');

        $iModuleId = (int)$aData['module_id'];
        if($iModuleId <= 0)
            return array('code' => 5, 'message' => '_payment_err_wrong_module');

        if(!isset($aData['items']) || !is_array($aData['items']) || empty($aData['items']))
            return array('code' => 6, 'message' => '_payment_err_empty_items');

        $aCartInfo = array('vendor_id' => $this->_iUserId, 'items_price' => 0, 'items' => array());
        foreach($aData['items'] as $iItemId) {
            $iItemId = (int)$iItemId;

            $sKeyPrice = 'item-price-' . $iItemId;
            $sKeyQuantity = 'item-quantity-' . $iItemId;
            if(!isset($aData[$sKeyQuantity]) || (int)$aData[$sKeyQuantity] <= 0)
                return array('code' => 7, 'message' => '_payment_err_wrong_quantity');

            $aCartInfo['items_price'] += (float)$aData[$sKeyPrice] * (int)$aData[$sKeyQuantity];
            $aCartInfo['items'][] = array(
                'vendor_id' => $this->_iUserId,
                'module_id' => $iModuleId,
                'id' => $iItemId,
                'quantity' => (int)$aData[$sKeyQuantity]
            );
        }
        $iPendingId = $this->_oDb->insertPending($iClientId, 'manual', $aCartInfo);
        $this->_oDb->updatePending($iPendingId, array(
            'order' => $sOrder,
            'error_code' => 0,
            'error_msg' => 'Manually processed'
        ));

        return (int)$iPendingId;
    }
}
?>