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

bx_import('BxDolPaginate');
bx_import('BxDolModuleTemplate');
bx_import('BxTemplFormView');
bx_import('BxTemplSearchResult');

class BxPmtTemplate extends BxDolModuleTemplate {
    /**
     * Constructor
     */
    function BxPmtTemplate(&$oConfig, &$oDb) {
        parent::BxDolModuleTemplate($oConfig, $oDb);
    }
    function loadTemplates() {
        parent::loadTemplates();

        $this->_aTemplates['script'] = '<script language="javascript" type="text/javascript">__content__</script>';
        $this->_aTemplates['on_result'] = '<script language="javascript" type="text/javascript">alert(\'__message__\')</script>';
        $this->_aTemplates['on_result_inline'] = '<script language="javascript" type="text/javascript">parent.__js_object__.showResultInline(__params__);</script>';
    }
    function displayMoreWindow() {
        return $this->parseHtmlByName('more.html', array());
    }
    function displayItems($aItemsInfo) {
        $aItems = array();
        foreach($aItemsInfo as $aItem) {
            $aItems[] = array(
                'id' => $aItem['id'],
                'price' => $aItem['price'],
                'bx_if:link' => array(
                    'condition' => !empty($aItem['url']),
                    'content' => array(
                        'url' => $aItem['url'],
                        'title' => $aItem['title']
                    )
                ),
                'bx_if:text' => array(
                    'condition' => empty($aItem['url']),
                    'content' => array(
                        'title' => $aItem['title']
                    )
                ),
            );
        }

        return $this->parseHtmlByName('items.html', array('bx_repeat:items' => $aItems));
    }
    function displayManualOrderWindow($aForm) {
        $oForm = new BxTemplFormView($aForm);

        return $this->parseHtmlByName('manual_order_form.html', array(
           'form' => $oForm->getCode()
        ));
    }
    function displayOrder($sType, $iId) {
        $sMethodName = 'get' . ucfirst($sType);
        $aOrder = $this->_oDb->$sMethodName(array('type' => 'id', 'id' => $iId));
        $aSeller = $this->_oDb->getVendorInfoProfile((int)$aOrder['seller_id']);

        $aResult = array(
            'client_name' => getNickName($aOrder['client_id']),
            'client_url' => getProfileLink($aOrder['client_id']),
            'bx_if:show_link' => array(
                'condition' => !empty($aSeller['profile_url']),
                'content' => array(
                    'seller_name' => $aSeller['username'],
                    'seller_url' => $aSeller['profile_url'],
                )
            ),
            'bx_if:show_text' => array(
                'condition' => empty($aSeller['profile_url']),
                'content' => array(
                    'seller_name' => $aSeller['username']
                )
            ),
            'order' => $aOrder['order'],
            'provider' => $aOrder['provider'],
            'error' => $aOrder['error_msg'],
            'date' => $aOrder['date_uf'],
            'bx_repeat:items' => array()
        );

        if($sType == BX_PMT_ORDERS_TYPE_PENDING)
            $aItems = BxPmtCart::items2array($aOrder['items']);
        else
            $aItems = BxPmtCart::items2array($aOrder['seller_id'] . '_' . $aOrder['module_id'] . '_' . $aOrder['item_id'] . '_' . $aOrder['item_count']);
        foreach($aItems as $aItem) {
            $aInfo = BxDolService::call((int)$aItem['module_id'], 'get_cart_item', array($aOrder['client_id'], $aItem['item_id']));
            $aResult['bx_repeat:items'][] = array(
                'bx_if:link' => array(
                    'condition' => !empty($aInfo['url']),
                    'content' => array(
                        'title' => $aInfo['title'],
                        'url' => $aInfo['url']
                    )
                ),
                'bx_if:text' => array(
                    'condition' => empty($aInfo['url']),
                    'content' => array(
                        'title' => $aInfo['title'],
                    )
                ),
                'quantity' => $aItem['item_count'],
                'price' => $aInfo['price'],
                'currency_code' => $aSeller['currency_code']
            );
        }

        return $this->parseHtmlByName($sType . '_order.html', $aResult);
    }
    function displayOrders($sType, $aParams) {
        if(empty($aParams['per_page']))
            $aParams['per_page'] = $this->_oConfig->getPerPage('orders');
        $sJsObject = $this->_oConfig->getJsObject('orders');

        $sMethodNameInfo = 'get' . ucfirst($sType) . 'Orders';
        $aOrders = $this->_oDb->$sMethodNameInfo($aParams);
        if(empty($aOrders))
           return MsgBox(_t('_payment_txt_empty'));

        $aAdministrator = $this->_oDb->getVendorInfoProfile(BX_PMT_ADMINISTRATOR_ID);

        //--- Get Orders ---//
        $aResultOrders = array();
        foreach($aOrders as $aOrder) {
            if(empty($aOrder['user_id']) || empty($aOrder['user_name'])) {
                $aOrder['user_id'] = $aAdministrator['id'];
                $aOrder['user_name'] = $aAdministrator['username'];
                $aOrder['user_url'] = $aAdministrator['profile_url'];
            }
            else
                $aOrder['user_url'] = getProfileLink($aOrder['user_id']);

            $aResultOrders[] = array_merge($aOrder, array(
                'type' => $sType,
                'bx_if:show_link' => array(
                    'condition' => !empty($aOrder['user_url']),
                    'content' => array(
                        'user_name' => $aOrder['user_name'],
                        'user_url' => $aOrder['user_url']
                    )
                ),
                'bx_if:show_text' => array(
                    'condition' => empty($aOrder['user_url']),
                    'content' => array(
                        'user_name' => $aOrder['user_name']
                    )
                ),
                'bx_if:pending' => array(
                    'condition' => $sType == BX_PMT_ORDERS_TYPE_PENDING,
                    'content' => array(
                        'id' => $aOrder['id'],
                        'order' => $aOrder['order']
                    )
                ),
                'bx_if:processed' => array(
                    'condition' => $sType == BX_PMT_ORDERS_TYPE_PROCESSED || $sType == BX_PMT_ORDERS_TYPE_HISTORY,
                    'content' => array(
                        'order' => $aOrder['order']
                    )
                ),
                'products' => $aOrder['products'],
                'items' => $aOrder['items'],
                'js_object' => $sJsObject
            ));
        }

        //--- Get Paginate Panel ---//
        $sPaginatePanel = "";
        $sMethodNameCount = 'get' . ucfirst($sType) . 'OrdersCount';
        if(($iCount = $this->_oDb->$sMethodNameCount($aParams)) > $aParams['per_page']) {
            $oPaginate = new BxDolPaginate(array(
                'page_url' => 'javascript:void(0);',
                'start' => $aParams['start'],
                'count' => $iCount,
                'per_page' => $aParams['per_page'],
                'per_page_step' => 2,
                'per_page_interval' => 3,
                'page_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . ($sType == BX_PMT_ORDERS_TYPE_HISTORY ? 'history' : 'orders') . '/',
                'on_change_page' => $sJsObject . ".changePage('" . $sType . "', {start}, {per_page}, " . ($sType == BX_PMT_ORDERS_TYPE_HISTORY ? $aParams['seller_id'] : "") . ")"
            ));
            $sPaginatePanel = $oPaginate->getPaginate();
        }

        return $this->parseHtmlByName($sType . '_orders.html', array(
            'bx_repeat:orders' => $aResultOrders,
            'paginate_panel' => $sPaginatePanel
        ));
    }
    function displayOrdersBlock($sType, $iVendorId) {
        $sJsObject = $this->_oConfig->getJsObject('orders');

        //--- Get Filter Panel ---//
        $sFilterPanel = BxTemplSearchResult::showAdminFilterPanel('', 'pmt-filter-text-' . $sType, 'pmt-filter-enable-' . $sType, 'filter', $sJsObject . ".applyFilter('" . $sType . "', this)");

        //--- Get Control Panel ---//
        $aButtons = array();
        if($sType == BX_PMT_ORDERS_TYPE_PENDING)
            $aButtons['pmt-process'] = _t('_payment_btn_process');
        $aButtons['pmt-cancel'] = _t('_payment_btn_cancel');
        $aButtons['pmt-report'] = _t('_payment_btn_report');
        if($sType == BX_PMT_ORDERS_TYPE_PROCESSED)
            $aButtons['pmt-manual'] = array('type' => 'button', 'name' => 'pmt-manual', 'value' => _t('_payment_btn_manual_order'), 'onclick' => 'onclick="javascript:' . $sJsObject . '.addManually(this);"');

        $sControlPanel = BxTemplSearchResult::showAdminActionsPanel('pmt-form-' . $sType, $aButtons, 'orders');

        return $this->parseHtmlByName($sType . '_orders_block.html', array(
            'type' => $sType,
            'action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'act_orders_submit/' . $sType,
            'orders' => $this->displayOrders($sType, array('seller_id' => $iVendorId, 'start' => 0)),
            'filter_panel' => $sFilterPanel,
            'control_panel' => $sControlPanel,
            'loading' => LoadingBox('pmt-orders-loading')
        ));
    }
    function displayHistoryBlock($iUserId, $iVendorId) {
        $sJsObject = $this->_oConfig->getJsObject('orders');

        //--- Get Filter Panel ---//
        $sFilterPanel = BxTemplSearchResult::showAdminFilterPanel('', 'pmt-filter-text-history', 'pmt-filter-enable-history', 'filter', $sJsObject . ".applyFilter('history', this)");

        return $this->parseHtmlByName('history_orders_block.html', array(
            'orders' => $this->displayOrders('history', array('user_id' => $iUserId, 'seller_id' => $iVendorId, 'start' => 0)),
            'filter_panel' => $sFilterPanel,
            'loading' => LoadingBox('pmt-orders-loading')
        ));
    }
    function displayToolbarSubmenu($aInfo) {
        $aCarts = array();
        foreach($aInfo as $iVendorId => $aVendorCart) {
            //--- Get Items ---//
            $aItems = array();
            foreach($aVendorCart['items'] as $aItem)
                $aItems[] = array(
                    'vendor_id' => $aVendorCart['vendor_id'],
                    'vendor_currency_code' => $aVendorCart['vendor_currency_code'],
                    'item_id' => $aItem['id'],
                    'item_title' => $aItem['title'],
                    'item_url' => $aItem['url'],
                    'item_quantity' => $aItem['quantity'],
                    'item_price' => $aItem['quantity'] * $aItem['price'],
                );

            //--- Get General Info ---//
            $aCarts[] = array(
                'vendor_id' => $aVendorCart['vendor_id'],
                'bx_if:show_link' => array(
                    'condition' => !empty($aVendorCart['vendor_profile_url']),
                    'content' => array(
                        'vendor_username' => $aVendorCart['vendor_username'],
                        'vendor_url' => $aVendorCart['vendor_profile_url'],
                        'vendor_currency_code' => $aVendorCart['vendor_currency_code'],
                        'items_count' => $aVendorCart['items_count'],
                        'items_price' => $aVendorCart['items_price']
                    )
                ),
                'bx_if:show_text' => array(
                    'condition' => empty($aVendorCart['vendor_profile_url']),
                    'content' => array(
                        'vendor_username' => $aVendorCart['vendor_username'],
                        'vendor_currency_code' => $aVendorCart['vendor_currency_code'],
                        'items_count' => $aVendorCart['items_count'],
                        'items_price' => $aVendorCart['items_price']
                    )
                ),
                'vendor_icon' => get_member_icon($aVendorCart['vendor_id'] != BX_PMT_ADMINISTRATOR_ID ? $aVendorCart['vendor_id'] : $this->_oDb->getFirstAdminId()),
                'bx_repeat:items' => $aItems
            );
        }
        return $this->parseHtmlByName('toolbar_submenu.html', array('bx_repeat:carts' => $aCarts));
    }
    function displayCartContent($aCartInfo, $iVendorId = BX_PMT_EMPTY_ID) {
        $iAdminId = $this->_oConfig->getAdminId();
        $sJsObject = $this->_oConfig->getJsObject('cart');

        if($iVendorId != BX_PMT_EMPTY_ID)
            $aCartInfo = array($aCartInfo);

        $aVendors = array();
        foreach($aCartInfo as $aVendor) {
            //--- Get Providers ---//
            $aProviders = array();
            $aVendorProviders = $this->_oDb->getVendorInfoProviders($aVendor['vendor_id']);
            foreach($aVendorProviders as $aProvider)
                $aProviders[] = array(
                    'name' => $aProvider['name'],
                    'caption' => $aProvider['caption'],
                    'checked' => empty($aProviders) ? 'checked="checked"' : ''
                );

            //--- Get Items ---//
            $aItems = array();
            foreach($aVendor['items'] as $aItem)
                $aItems[] = array(
                    'vendor_id' => $aVendor['vendor_id'],
                    'vendor_currency_code' => $aVendor['vendor_currency_code'],
                    'module_id' => $aItem['module_id'],
                    'item_id' => $aItem['id'],
                    'item_title' => $aItem['title'],
                    'item_url' => $aItem['url'],
                    'item_quantity' => $aItem['quantity'],
                    'item_price' => $aItem['quantity'] * $aItem['price'],
                    'js_object' => $sJsObject
                );

            //--- Get Control Panel ---//
            $aButtons = array(
                'pmt-checkout' => _t('_payment_btn_checkout'),
                'pmt-delete' => _t('_payment_btn_delete')
            );
            $sControlPanel = BxTemplSearchResult::showAdminActionsPanel('items_from_' . $aVendor['vendor_id'], $aButtons, 'items', true, true);

            //--- Get General ---//
            $aVendors[] = array(
                'box_width' => ($aVendor['vendor_id'] == $iAdminId ? 260 : 310),
                'vendor_id' => $aVendor['vendor_id'],
                'bx_if:show_link' => array(
                    'condition' => !empty($aVendor['vendor_profile_url']),
                    'content' => array(
                        'vendor_username' => $aVendor['vendor_username'],
                        'vendor_url' => $aVendor['vendor_profile_url'],
                        'vendor_currency_code' => $aVendor['vendor_currency_code'],
                        'items_count' => $aVendor['items_count'],
                        'items_price' => $aVendor['items_price']
                    )
                ),
                'bx_if:show_text' => array(
                    'condition' => empty($aVendor['vendor_profile_url']),
                    'content' => array(
                        'vendor_username' => $aVendor['vendor_username'],
                        'vendor_currency_code' => $aVendor['vendor_currency_code'],
                        'items_count' => $aVendor['items_count'],
                        'items_price' => $aVendor['items_price']
                    )
                ),
                'vendor_icon' => get_member_icon($aVendor['vendor_id'] != -1 ? $aVendor['vendor_id'] : $this->_oDb->getFirstAdminId()),
                'bx_repeat:providers' => $aProviders,
                'bx_repeat:items' => $aItems,
                'js_object' => $sJsObject,
                'process_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'act_cart_submit/',
                'control_panel' => $sControlPanel
            );
        }

        $this->addCss('cart.css');
        $this->addJs('cart.js');
        return $this->parseHtmlByName('cart.html', array_merge($this->_getJsContentCart(), array('bx_repeat:vendors' => $aVendors)));
    }
    function displayCartJs($bWrapped = true) {
        $this->addJs('cart.js');

        $aJs = $this->_getJsContentCart();
        if($bWrapped)
            return $this->parseHtmlByName('wrapper_js.html', $aJs);
        else
            return $aJs['js_content'];
    }
    function displayAddToCartJs($iVendorId, $iModuleId, $iItemId, $iItemCount, $bNeedRedirect = false, $bWrapped = true) {
        $aJs = $this->_getJsContentCart();

        $sJsCode = $this->displayCartJs($bWrapped);
        $sJsMethod = $this->parseHtmlByName('add_to_cart_js.html', array(
            'js_object' => $aJs['js_object'],
            'vendor_id' => $iVendorId,
            'module_id' => $iModuleId,
            'item_id' => $iItemId,
            'item_count' => $iItemCount,
            'need_redirect' => (int)$bNeedRedirect
        ));

        return array($sJsCode, $sJsMethod);
    }
    function displayAddToCartLink($iVendorId, $iModuleId, $iItemId, $iItemCount, $bNeedRedirect = false) {
        $this->addJs('cart.js');
        return $this->parseHtmlByName('add_to_cart.html', array_merge($this->_getJsContentCart(), array(
            'vendor_id' => $iVendorId,
            'module_id' => $iModuleId,
            'item_id' => $iItemId,
            'item_count' => $iItemCount,
            'need_redirect' => (int)$bNeedRedirect
        )));
    }

    function _getJsContentCart() {
        $sJsObject = $this->_oConfig->getJsObject('cart');
        ob_start();
?>
        var <?=$sJsObject; ?> = new BxPmtCart({
            sActionUrl: '<?=BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(); ?>',
            sObjName: '<?=$sJsObject; ?>'
        });
<?
        $sJsContent = ob_get_clean();

        return array('js_object' => $sJsObject, 'js_content' => $sJsContent);
    }
}
?>