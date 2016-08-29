<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once("BxPfwExpressCheckout.php");

class BxPfwRecurringBilling extends BxPfwExpressCheckout
{
	function BxPfwRecurringBilling($oDb, $oConfig, $aConfig)
	{
		parent::BxPfwExpressCheckout($oDb, $oConfig, $aConfig);

		$this->_aCallParameters['TRXTYPE'] = 'R';
	}

	function initializeCheckout($iPendingId, $aCartInfo)
	{
		if((int)$aCartInfo['items_count'] > 1) {
			$oMain = bx_instance($this->_oConfig->getClassPrefix() . 'Module');
			$oMain->_oTemplate->getPageCodeError($this->_sLangsPrefix . 'msg_one_item_only');
			exit;
		}

		parent::initializeCheckout($iPendingId, $aCartInfo);
	}

	function confirmCheckout($sToken, $sPayerId)
	{
		$aResponse = parent::confirmCheckout($sToken, $sPayerId);
		$aResponse['SUBSCRIPTION'] = 1;

		return $aResponse;
	}

	function finalizeCheckout(&$aData)
	{
		$this->_logInfo(__METHOD__, $aData);

		$iPending = (int)$aData['pendingid'];
		$aPending = $this->_oDb->getPending(array('type' => 'id', 'id' => $iPending));
        if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return array('code' => 0, 'message' => _t($this->_sLangsPrefix . 'err_already_processed'));

		$sToken = process_db_input($aData['token'], BX_TAGS_STRIP);
		$sPayerId = process_db_input($aData['payerid'], BX_TAGS_STRIP);
		$sPayerName = process_db_input($aData['payername'], BX_TAGS_STRIP);
		$sPayerEmail = process_db_input($aData['payeremail'], BX_TAGS_STRIP);
		$sAmt = process_db_input($aData['amt'], BX_TAGS_STRIP);
		$this->_doExpressCheckout($sToken, $sPayerId, $sAmt);

		$aResponsePay = $this->_executeCall();
		if($aResponsePay === false)
			return array('code' => 0, 'message' => _t($this->_sLangsPrefix . 'err_unknown'));

		$this->_logInfo(__METHOD__, $aResponsePay);

		$iResponseCode = (int)$aResponsePay['RESULT'];
		$sResponseMessage = process_db_input($aResponsePay['RESPMSG'], BX_TAGS_STRIP);
		$bPaymentAccepted = $iResponseCode == 0;

		$aResult = array(
			'code' => $bPaymentAccepted ? 1 : 0, 
			'message' => $bPaymentAccepted ? _t($this->_sLangsPrefix . 'msg_accepted') : $sResponseMessage,
			'pending_id' => $iPending,
			'payer_name' => $sPayerName,
			'payer_email' => $sPayerEmail,
		);

        //--- Update pending transaction ---//
        $this->_oDb->updatePending($iPending, array(
            'order' => process_db_input($aResponsePay['PPREF'], BX_TAGS_STRIP),
        	'order_ref' => process_db_input($aResponsePay['PNREF'], BX_TAGS_STRIP),
            'error_code' => $aResult['code'],
            'error_msg' => $sResponseMessage
        ));

        //--- Establish subscription ---//
		if($bPaymentAccepted && !empty($aResponsePay['BAID'])) {
			$sBaid = process_db_input($aResponsePay['BAID'], BX_TAGS_STRIP);

			$oMain = bx_instance($this->_oConfig->getClassPrefix() . 'Module');
			$aCartInfo = $oMain->_oCart->getInfo((int)$aPending['client_id'], (int)$aPending['seller_id'], $aPending['items']);

			$this->_createRecurringBillingProfile($iPending, $aCartInfo, $sBaid);

			$aResponseSubscribe = $this->_executeCall();
			if($aResponseSubscribe !== false && (int)$aResponseSubscribe['RESULT'] == 0) {
				$this->_logInfo(__METHOD__, $aResponseSubscribe);

				$aResult['message'] = _t($this->_sLangsPrefix . 'msg_accepted_subscribed');

				$this->_oDb->updatePending($iPending, array(
		        	'order_profile' => process_db_input($aResponseSubscribe['PROFILEID'], BX_TAGS_STRIP)
		        ));
			}
		}

		return $aResult;
	}

	function inquiryRecurringBillingProfileDetails($sRecurringProfileId, $bPaymentsHistory = false)
	{
		$this->_inquiryRecurringBillingProfileDetails($sRecurringProfileId, $bPaymentsHistory);

		$aResponse = $this->_executeCall();
		if($aResponse === false || (isset($aResponse['RESULT']) && $aResponse['RESULT'] != 0))
			return false;

		if($bPaymentsHistory)
			$aResponse = $this->_decodePaymentHistory($aResponse);

		$this->_logInfo(__METHOD__, $aResponse);

		return $aResponse;
	}

	function cancelSubscription($aOrder)
	{
		if(empty($aOrder['order_profile']))
			return false;

		$this->_cancelRecurringBillingProfile($aOrder['order_profile']);

		$aResponse = $this->_executeCall();
		if($aResponse === false || (isset($aResponse['RESULT']) && $aResponse['RESULT'] != 0))
			return false;

		$aProcOrders = $this->_oDb->getProcessed(array('type' => 'order_profile', 'order_profile' => $aOrder['order_profile']));
		foreach($aProcOrders as $aProcOrder)
			$this->_oDb->updatePending($aProcOrder['pending_id'], array('order_profile' => ''));

		return true;
	}

	function prolongSubscription($aOrder)
	{
		if(empty($aOrder['order_profile']))
			return array('code' => 4, 'message' => '_bx_pfw_err_subscription_not_found');

		$aPayments = $this->inquiryRecurringBillingProfileDetails($aOrder['order_profile'], true);
		if(empty($aPayments) || !is_array($aPayments))
			return array('code' => 5, 'message' => '_bx_pfw_err_subscription_not_prolonged');

		$aNextPayment = array();
		foreach($aPayments as $aPayment) {
			if((int)$aPayment['RESULT'] != 0 || (int)$aPayment['TRANSTATE'] != 8)
				continue;

			if(strtotime($aPayment['TRANSTIME']) > $aOrder['date'] && $aPayment['PNREF'] != $aOrder['order_ref'] && (float)$aPayment['AMT'] >= (float)$aOrder['amount']) {
				$aNextPayment = $aPayment;
				break;
			}
		}

		if(empty($aNextPayment) || !is_array($aNextPayment))
			return array('code' => 5, 'message' => '_bx_pfw_err_subscription_not_prolonged');

		//--- Create pending for newly accepted payment
		$iPendingId = $this->_oDb->insertPending($aOrder['client_id'], $aOrder['provider'], array(
			'vendor_id' => $aOrder['seller_id'],
			'items_price' => $aOrder['amount'],
			'items' => array(
				array('module_id' => $aOrder['module_id'], 'id' => $aOrder['item_id'], 'quantity' => $aOrder['item_count'])
			)
		));

		$this->_oDb->updatePending($iPendingId, array(
            'order' => $aOrder['order'],
        	'order_ref' => process_db_input($aPayment['PNREF'], BX_TAGS_STRIP),
			'order_profile' => $aOrder['order_profile'],
            'error_code' => 1,
            'error_msg' => 'Prolonged'
        ));

        return array('code' => 0, 'message' => '_bx_pfw_msg_accepted', 'pending_id' => $iPendingId);
	}

	protected function _setExpressCheckout($iPendingId, $aCartInfo)
	{
		parent::_setExpressCheckout($iPendingId, $aCartInfo);

		$this->_aCallParameters['TRXTYPE'] = 'S';
		$this->_aCallParameters['BILLINGTYPE'] = 'RecurringBilling';
	}

	protected function _getExpressCheckout($sToken, $sPayerId)
    {
    	parent::_getExpressCheckout($sToken, $sPayerId);

    	$this->_aCallParameters['TRXTYPE'] = 'S';
    }

    protected function _doExpressCheckout($sToken, $sPayerId, $sAmount)
    {
    	parent::_doExpressCheckout($sToken, $sPayerId, $sAmount);

    	$this->_aCallParameters['TRXTYPE'] = 'S';
    }

	protected function _createRecurringBillingProfile($iPendingId, $aCartInfo, $sBaid)
	{
		$aItem = array_shift($aCartInfo['items']);

		$this->_aValidationParameters = array('TENDER', 'TRXTYPE', 'ACTION', 'BAID', 'PROFILENAME', 'START', 'PAYPERIOD', 'TERM', 'AMT');

		$this->_aCallParameters['TRXTYPE'] = 'R';
		$this->_aCallParameters['ACTION'] = 'A';
		$this->_aCallParameters['BAID'] = $sBaid;

		$this->_aCallParameters['PROFILENAME'] = _t($this->_sLangsPrefix . 'txt_subscription_for', $aItem['title']);
		$this->_aCallParameters['START'] = date('mdY', (time() + 86400 * $aItem['duration']));
		$this->_aCallParameters['PAYPERIOD'] = 'DAYS';
		$this->_aCallParameters['FREQUENCY'] = $aItem['duration'];
		$this->_aCallParameters['TERM'] = 0;

		$this->_aCallParameters['AMT'] = sprintf( "%.2f", (float)$aItem['price']);
		$this->_aCallParameters['CURRENCY'] = $aCartInfo['vendor_currency_code'];
	}

	protected function _cancelRecurringBillingProfile($sRecurringProfileId)
	{
		$this->_aValidationParameters = array('TENDER', 'TRXTYPE', 'ACTION', 'ORIGPROFILEID');

		$this->_aCallParameters['TRXTYPE'] = 'R';
		$this->_aCallParameters['ACTION'] = 'C';

		$this->_aCallParameters['ORIGPROFILEID'] = $sRecurringProfileId;
	}

	protected function _inquiryRecurringBillingProfileDetails($sRecurringProfileId, $bPaymentsHistory = false)
	{
		$this->_aValidationParameters = array('TENDER', 'TRXTYPE', 'ACTION', 'ORIGPROFILEID');

		$this->_aCallParameters['TRXTYPE'] = 'R';
		$this->_aCallParameters['ACTION'] = 'I';

		$this->_aCallParameters['ORIGPROFILEID'] = $sRecurringProfileId;

		if($bPaymentsHistory)
			$this->_aCallParameters['PAYMENTHISTORY'] = 'Y';
	}

	protected function _decodePaymentHistory($aResponse)
	{
		$aPayments = array();

		foreach($aResponse as $sKey => $sValue) {
			$aMatches = array();
			if(preg_match("/P_([A-Z]+)([0-9]+)/", $sKey, $aMatches)) {
				$aPayments[$aMatches[2]][$aMatches[1]] = $sValue;
			}
		}

		return $aPayments;
	}
}