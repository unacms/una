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


class BxPaymentGridProcessed extends BxBaseModPaymentGridOrders
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct ($aOptions, $oTemplate);

        $this->_sOrdersType = 'processed';
    }

    public function performActionAdd()
    {
    	$sType = BX_PAYMENT_TYPE_SINGLE;
    	$sAction = 'add';
    	$sJsObject = $this->_oModule->_oConfig->getJsObject('processed');

        $sFormObject = $this->_oModule->_oConfig->getObject('form_processed');
        $sFormDisplay = $this->_oModule->_oConfig->getObject('display_processed_add');

        $oForm = BxDolForm::getObjectInstance($sFormObject, $sFormDisplay, $this->_oModule->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;

        $oForm->aInputs['seller_id']['value'] = $this->_aQueryAppend['seller_id'];

        $oForm->aInputs['client_id']['attrs'] = array(
        	'id' => $this->_oModule->_oConfig->getHtmlIds('processed', 'order_processed_client_id'),
        );
        $oForm->aInputs['client']['attrs'] = array(
        	'id' => $this->_oModule->_oConfig->getHtmlIds('processed', 'order_processed_client'),
        );

        $oForm->aInputs['module_id']['attrs'] = array(
			'onchange' => 'javascript:' . $sJsObject . '.paOnSelectModule(this);'
		);
        $oForm->aInputs['module_id']['values'] = array(
            array('key' => '0', 'value' => _t('_Select_one'))
        );

        $aModules = $this->_oModule->_oDb->getModules();
        foreach($aModules as $aModule)
           $oForm->aInputs['module_id']['values'][] = array('key' => $aModule['id'], 'value' => $aModule['title']);

		$oForm->aInputs['items']['content'] = $this->_oModule->_oTemplate->displayItems($sType);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
        	$sFormMethod = $oForm->aFormAttrs['method'];

        	$aItemIds = $oForm->getSubmittedValue('items', $sFormMethod);
        	if(empty($aItemIds) || !is_array($aItemIds))
            	return echoJson(array('msg' => $this->_sLangsPrefix . 'err_empty_items'));

			$aItems = array();
			$sPriceKey = $this->_oModule->_oConfig->getKey('KEY_ARRAY_PRICE_SINGLE');
			foreach($aItemIds as $iItemId) {
	            $iItemId = (int)$iItemId;
	            $fItemPrice = (float)$oForm->getSubmittedValue('item-price-' . $iItemId, $sFormMethod);
	            $iItemQuantity = (int)$oForm->getSubmittedValue('item-quantity-' . $iItemId, $sFormMethod);

	            if($iItemQuantity <= 0)
	                return echoJson(array('msg' => $this->_sLangsPrefix . 'err_wrong_quantity'));

				$aItems[] = array(
					'id' => $iItemId, 
					 $sPriceKey => $fItemPrice, 
					'quantity' => $iItemQuantity
				);
			}

        	$mixedResult = $this->_oModule->getObjectOrders()->addOrder(array(
        		'client_id' => $oForm->getCleanValue('client_id'),
        		'seller_id' => $oForm->getCleanValue('seller_id'),
        		'provider' => 'manual',
        		'type' => $sType,
        		'order' => $oForm->getCleanValue('order'),
        		'error_code' => 0,
        		'error_msg' => 'Manually processed',        		
        		'module_id' => $oForm->getCleanValue('module_id'),
        		'items' => $aItems
        	));
        	if(is_array($mixedResult))
        		return echoJson($mixedResult);

			$iPendingId = (int)$mixedResult;
        	if(!$this->_oModule->registerPayment($iPendingId))
        		return echoJson(array('msg' => _t($this->_sLangsPrefix . 'err_cannot_perform')));

			$aOrders = $this->_oModule->_oDb->getOrderProcessed(array('type' => 'pending_id', 'pending_id' => $iPendingId, 'with_key' => 'id'));
            return echoJson(array('grid' => $this->getCode(false), 'blink' => array_keys($aOrders)));
        }

        $sKey = 'order_' . $this->_sOrdersType . '_add';
        $sId = $this->_oModule->_oConfig->getHtmlIds('processed', $sKey);
    	$sTitle = _t($this->_sLangsPrefix . 'popup_title_ods_' . $sKey);

		$sContent = BxTemplStudioFunctions::getInstance()->popupBox($sId, $sTitle, $this->_oModule->_oTemplate->parseHtmlByName('order_processed_add.html', array(
			'js_object' => $sJsObject,
			'form_id' => $oForm->aFormAttrs['id'],
			'form' => $oForm->getCode(true),
			'object' => $this->_sObject,
			'action' => $sAction
		)));

		echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
    }

	protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
    	if(empty($this->_aQueryAppend['seller_id']))
    		return array();

		$this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tt`.`seller_id`=?", $this->_aQueryAppend['seller_id']);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
