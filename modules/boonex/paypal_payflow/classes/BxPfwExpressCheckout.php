<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once("BxPfwPayPal.php");

class BxPfwExpressCheckout extends BxPfwPayPal
{
	function BxPfwExpressCheckout($oDb, $oConfig, $aConfig)
	{
		parent::BxPfwPayPal($oDb, $oConfig, $aConfig);

		$this->_aCallParameters['TENDER'] = 'P';
	}

	function initializeCheckout($iPendingId, $aCartInfo)
	{
		$this->_setExpressCheckout($iPendingId, $aCartInfo);

		$aResponse = $this->_executeCall();
		if($aResponse === false)
			return false;

		$this->_logInfo(__METHOD__, $aResponse);

		$sToken = process_db_input($aResponse['TOKEN'], BX_TAGS_STRIP);

		$sRedirectUrl = $this->_oConfig->getPpEndpoint(BX_PFW_ENDPOINT_TYPE_HOSTED);
		$sRedirectUrl = bx_append_url_params($sRedirectUrl, array('cmd' => '_express-checkout', 'token' => $sToken));

		header("Location: " . $sRedirectUrl);
		exit;
	}

	function confirmCheckout($sToken, $sPayerId)
	{
		$this->_getExpressCheckout($sToken, $sPayerId);

		$aResponse = $this->_executeCall();
		if($aResponse === false)
			return false;

		$this->_logInfo(__METHOD__, $aResponse);
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

		$aResponse = $this->_executeCall();
		if($aResponse === false)
			return array('code' => 0, 'message' => _t($this->_sLangsPrefix . 'err_unknown'));

		$this->_logInfo(__METHOD__, $aResponse);

		$iResponseCode = (int)$aResponse['RESULT'];
		$sResponseMessage = process_db_input($aResponse['RESPMSG'], BX_TAGS_STRIP);

		$aResult = array(
			'code' => $iResponseCode == 0 ? 1 : 0, 
			'message' => $iResponseCode == 0 ? _t($this->_sLangsPrefix . 'msg_accepted') : $sResponseMessage,
			'pending_id' => $iPending,
			'payer_name' => $sPayerName, 
			'payer_email' => $sPayerEmail,
		);

        //--- Update pending transaction ---//
        $this->_oDb->updatePending($iPending, array(
            'order' => process_db_input($aResponse['PPREF'], BX_TAGS_STRIP),
        	'order_ref' => process_db_input($aResponse['PNREF'], BX_TAGS_STRIP),
            'error_code' => $aResult['code'],
            'error_msg' => $sResponseMessage
        ));

		return $aResult;
	}

    protected function _setExpressCheckout($iPendingId, $aCartInfo)
    {
    	$this->_aValidationParameters = array('TENDER', 'TRXTYPE', 'ACTION', 'RETURNURL', 'CANCELURL', 'AMT', 'CURRENCY');

		$this->_aCallParameters['ACTION'] = 'S';

		$this->_aCallParameters['RETURNURL'] = $this->_oConfig->getReturnUrl() . $this->_sName . '/' . $aCartInfo['vendor_id'] . '/';
		$this->_aCallParameters['CANCELURL'] = $this->_oConfig->getCancelUrl();

		$this->_aCallParameters['AMT'] = sprintf( "%.2f", (float)$aCartInfo['items_price']);
		$this->_aCallParameters['CURRENCY'] = $aCartInfo['vendor_currency_code'];
		$this->_aCallParameters['CUSTOM'] = $iPendingId;

		foreach($aCartInfo['items'] as $iIndex => $aItem)
			$this->_aCallParameters = array_merge($this->_aCallParameters, array(
				'L_NAME' . $iIndex => $aItem['title'],
				'L_DESC' . $iIndex => $aItem['description'],
				'L_COST' . $iIndex => $aItem['price'],
				'L_TAXAMT' . $iIndex => '0.00',
				'L_QTY' . $iIndex => $aItem['quantity'],
				'L_AMT' . $iIndex => sprintf( "%.2f", (float)$aItem['quantity'] * $aItem['price']),
			));
    }

    protected function _getExpressCheckout($sToken, $sPayerId)
    {
    	$this->_aValidationParameters = array('TENDER', 'TRXTYPE', 'ACTION');

    	$this->_aCallParameters['ACTION'] = 'G';
    	$this->_aCallParameters['TOKEN'] = $sToken;
    }

	protected function _doExpressCheckout($sToken, $sPayerId, $sAmount)
    {
    	$this->_aValidationParameters = array('TENDER', 'TRXTYPE', 'ACTION', 'TOKEN', 'PAYERID', 'AMT');

    	$this->_aCallParameters['ACTION'] = 'D';
    	$this->_aCallParameters['TOKEN'] = $sToken;
    	$this->_aCallParameters['PAYERID'] = $sPayerId;
    	$this->_aCallParameters['AMT'] = $sAmount;
    }
}
