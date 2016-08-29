<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once(BX_DIRECTORY_PATH_MODULES . 'boonex/payment/classes/BxPmtModule.php');

require_once('BxPfwLog.php');

define('BX_PFW_MODE_LIVE', 1);
define('BX_PFW_MODE_TEST', 2);

define('BX_PFW_ENDPOINT_TYPE_CALL', 'call');
define('BX_PFW_ENDPOINT_TYPE_HOSTED', 'hosted');

define('BX_PFW_PROVIDER_EXPRESS', 'express_checkout');
define('BX_PFW_PROVIDER_HOSTED', 'hosted_checkout');
define('BX_PFW_PROVIDER_RECURRING', 'recurring_billing');

class BxPfwModule extends BxPmtModule
{
    function BxPfwModule($aModule)
    {
        parent::BxPmtModule($aModule);

		$this->_oTemplate->init($this);
    }

    /**
     * System Methods
     */

    /**
     * Action Methods
     */
    function actionCart()
    {
    	$this->_oTemplate->addJsTranslation(array(
		    $this->_sLangsPrefix . 'err_nothing_selected'
		));

    	bx_import('PageCart', $this->_aModule);
    	$oPage = new BxPfwPageCart($this);

    	$aParams = array(
            'index' => 1,
            'title' => array(
                'page' => _t($this->_sLangsPrefix . 'pcpt_view_cart')
            ),
            'content' => array(
                'page_main_code' => $oPage->getCode()
            )
        );
        $this->_oTemplate->getPageCode($aParams);
    }

	function actionHistory($sType = '')
    {
    	bx_import('PageHistory', $this->_aModule);
    	$oPage = new BxPfwPageHistory($sType, $this);

    	$aParams = array(
            'index' => 2,
            'css' => array('orders.css', '_orders.css'),
    		'js' => array('orders.js', '_orders.js'),
            'title' => array(
                'page' => _t($this->_sLangsPrefix . 'pcpt_cart_history')
            ),
            'content' => array(
                'page_main_code' => $oPage->getCode(),
            	'more_code' => $this->getMoreWindow(),
				'js_code' => $this->_oTemplate->getJsCode('orders', true)
            )
        );
        $this->_oTemplate->getPageCode($aParams);
    }

	function actionOrders($sType = '')
    {
    	bx_import('PageOrders', $this->_aModule);
    	$oPage = new BxPfwPageOrders($sType, $this);

    	$aParams = array(
            'index' => 3,
            'css' => array('orders.css', '_orders.css'),
    		'js' => array('orders.js', '_orders.js'),
            'title' => array(
                'page' => _t($this->_sLangsPrefix . 'pcpt_view_orders')
            ),
            'content' => array(
                'page_main_code' => $oPage->getCode(),
            	'more_code' => $this->getMoreWindow(),
				'manual_order_code' => $this->getManualOrderWindow(),
				'js_code' => $this->_oTemplate->getJsCode('orders', true)
            )
        );
        $this->_oTemplate->getPageCode($aParams);
    }

    function actionDetails()
    {
    	bx_import('PageDetails', $this->_aModule);
    	$oPage = new BxPfwPageDetails($this);

    	$aParams = array(
            'index' => 4,
            'title' => array(
                'page' => _t($this->_sLangsPrefix . 'pcpt_details')
            ),
            'content' => array(
                'page_main_code' => $oPage->getCode()
            )
        );
        $this->_oTemplate->getPageCode($aParams);
    }

	function actionAdmin($sName = '')
    {
        $GLOBALS['iAdminPage'] = 1;
        require_once(BX_DIRECTORY_PATH_INC . 'admin_design.inc.php');

        $sUri = $this->_oConfig->getUri();

        check_logged();
        if(!@isAdmin()) {
            send_headers_page_changed();
            login_form("", 1);
            exit;
        }

        //--- Process actions ---//
        $mixedResultSettings = '';
        if(isset($_POST['save']) && isset($_POST['cat'])) {
            $mixedResultSettings = $this->setSettings($_POST);
        }
        //--- Process actions ---//

		$aDetailsBox = $this->getDetailsForm(BX_PMT_ADMINISTRATOR_ID);
		$aPendingOrdersBox = $this->getOrdersBlock(BX_PMT_ORDERS_TYPE_PENDING, BX_PMT_ADMINISTRATOR_ID);
		$aProcessedOrdersBox = $this->getOrdersBlock(BX_PMT_ORDERS_TYPE_PROCESSED, BX_PMT_ADMINISTRATOR_ID);
		$aSubscriptionOrdersBox = $this->getOrdersBlock(BX_PMT_ORDERS_TYPE_SUBSCRIPTION, BX_PMT_ADMINISTRATOR_ID);

		$sContent = ''; 
		$sContent .= $this->_oTemplate->getJsCode('orders', true);
        $sContent .= DesignBoxAdmin(_t($this->_sLangsPrefix . 'bcpt_settings'), $GLOBALS['oAdmTemplate']->parseHtmlByName('design_box_content.html', array('content' => $this->getSettingsForm($mixedResultSettings))));
        $sContent .= DesignBoxAdmin(_t($this->_sLangsPrefix . 'bcpt_details'), $GLOBALS['oAdmTemplate']->parseHtmlByName('design_box_content.html', array('content' => $aDetailsBox[0])));
        $sContent .= DesignBoxAdmin(_t($this->_sLangsPrefix . 'bcpt_pending_orders'), $aPendingOrdersBox[0]);
		$sContent .= DesignBoxAdmin(_t($this->_sLangsPrefix . 'bcpt_processed_orders'), $aProcessedOrdersBox[0]);
		$sContent .= DesignBoxAdmin(_t($this->_sLangsPrefix . 'bcpt_subscription_orders'), $aSubscriptionOrdersBox[0]);
		$sContent .= $this->getMoreWindow();
		$sContent .= $this->getManualOrderWindow();

		$this->_oTemplate->addAdminJs(array('orders.js', '_orders.js'));
		$this->_oTemplate->addAdminCss(array('orders.css', '_orders.css'));

        $aParams = array(
            'title' => array(
                'page' => _t($this->_sLangsPrefix . 'pcpt_administration')
            ),
            'content' => array(
                'page_main_code' => $sContent
            )
        );
        $this->_oTemplate->getPageCodeAdmin($aParams);
    }

	/**
     * Action Methods: PayFlow Integration
     * Perform payment confirmation for Express Checkout 
     */
	function actionConfirm($sProvider, $iVendorId)
	{
		$sToken = bx_get('token');
		$sPayerId = bx_get('PayerID');
		if($sToken === false || $sPayerId === false) {
			$this->_oTemplate->getPageCodeError($this->_sLangsPrefix . 'err_wrong_data');
			return;
		}

		$sToken = process_db_input($sToken, BX_TAGS_STRIP);
		$sPayerId = process_db_input($sPayerId, BX_TAGS_STRIP);

		$oProvider = $this->_getProvider($sProvider, $iVendorId);
		if(is_string($oProvider)) {
			$this->_oTemplate->getPageCodeError($oProvider);
			return;
		}

		$aOrderInfo = $oProvider->confirmCheckout($sToken, $sPayerId);
		if($aOrderInfo === false) {
			$this->_oTemplate->getPageCodeError($this->_sLangsPrefix . 'err_unknown');
        	return;
		}

		$iPendingId = (int)$aOrderInfo['CUSTOM'];
    	$aPending = $this->_oDb->getPending(array('type' => 'id', 'id' => $iPendingId));
        if(empty($aPending) || !is_array($aPending)) {
        	$this->getPageCodeError($this->_sLangsPrefix . 'wrong_data');
        	return;
        }

        $aCartInfo = $this->_oCart->getInfo((int)$aPending['client_id'], $iVendorId, $aPending['items']);
		$this->_oTemplate->displayConfirmPage($sProvider, $iVendorId, $aOrderInfo, $aCartInfo);
	}

	/**
	 * Currently is used with errors returned from Hosted Checkout Pages.
	 */
	function actionResponse($sProvider, $iVendorId)
	{
		$oProvider = $this->_getProvider($sProvider, $iVendorId);
		if(is_string($oProvider)) {
			$this->_oTemplate->getPageCodeError($oProvider);
			return;
		}

		$aResult = $oProvider->processResponse($_REQUEST);

		$sMethod = 'getPageCodeResponse';
		$sMessage = $this->_sLangsPrefix . 'msg_successfully_done';
		if((int)$aResult['code'] != 0) {
			$sMethod = 'getPageCodeError';
			$sMessage = $this->_sLangsPrefix . 'err_unknown';
		}

		$this->_oTemplate->$sMethod($sMessage);
		return;
	}

	/**
	 * Subscription related actions
	 */
	function actionCancelSubscription()
	{
		$iId = (int)bx_get('id');
		$sType = process_db_input(bx_get('type'), BX_TAGS_STRIP);

		if(empty($iId) || empty($sType) || !in_array($sType, array(BX_PMT_ORDERS_TYPE_SUBSCRIPTION, BX_PMT_ORDERS_TYPE_HISTORY)))
			return $this->_onResultJson(array('code' => 1, 'message' => '_bx_pfw_err_wrong_data'));

		$aOrder = $this->_oDb->getProcessed(array('type' => 'id', 'id' => $iId));
		if(empty($aOrder))
			return $this->_onResultJson(array('code' => 1, 'message' => '_bx_pfw_err_wrong_data'));

		if(!isAdmin() && (int)$aOrder['client_id'] != getLoggedId())
			return $this->_onResultJson(array('code' => 2, 'message' => '_bx_pfw_err_not_allowed'));

		$oProvider = $this->_getProvider($aOrder['provider'], $aOrder['seller_id']);
		if(is_string($oProvider))
			return $this->_onResultJson(array('code' => 3, 'message' => $oProvider));

		if(!$oProvider->cancelSubscription($aOrder))
			return $this->_onResultJson(array('code' => 4, 'message' => '_bx_pfw_err_unknown'));

		return $this->_onResultJson(array('code' => 0, 'message' => '_bx_pfw_msg_subscription_canceled'));
	}

    /**
     * Service Methods
     */
	function serviceProlongSubscription($sOrderId)
	{
		$aOrder = $this->_oDb->getProcessed(array('type' => 'order_id', 'order_id' => $sOrderId));
		if(empty($aOrder))
			return array('code' => 1, 'message' => '_bx_pfw_err_wrong_data');

		$oProvider = $this->_getProvider($aOrder['provider'], $aOrder['seller_id']);
		if(is_string($oProvider))
			return array('code' => 2, 'message' => $oProvider);

		if(!method_exists($oProvider, 'prolongSubscription'))
			return array('code' => 3, 'message' => '_bx_pfw_err_not_available');

		$aResult = $oProvider->prolongSubscription($aOrder);
		if($aResult['code'] != 0)
			return $aResult;

		//--- Register payment by pending in associated modules
		$this->_oCart->updateInfo($aResult['pending_id']);

		return $aResult;
	}

	/**
     * Protected Methods
     */
	protected function _getProvider($sProvider, $iVendorId)
	{
		if($iVendorId == BX_PMT_EMPTY_ID)
			return $this->_sLangsPrefix . 'err_unknown_vendor';

        $aProvider = $this->_oDb->getVendorInfoProviders($iVendorId, $sProvider);
        $sClassPath = !empty($aProvider['class_file']) ? BX_DIRECTORY_PATH_ROOT . $aProvider['class_file'] : $this->_oConfig->getClassPath() . $aProvider['class_name'] . '.php';
        if(empty($aProvider) || !file_exists($sClassPath))
        	return $this->_sLangsPrefix . 'err_incorrect_provider';

        require_once($sClassPath);
        return new $aProvider['class_name']($this->_oDb, $this->_oConfig, $aProvider);
	}
	
	protected function _onResultJson($aResult)
    {
        $oJson = new Services_JSON();

        if(isset($aResult['message']))
        	$aResult['message'] = _t($aResult['message']);

		header('Content-Type:text/javascript; charset=utf-8');
        return $oJson->encode($aResult);
    }
}
