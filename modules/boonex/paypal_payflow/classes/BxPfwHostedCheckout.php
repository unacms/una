<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once("BxPfwPayPal.php");
require_once("BxPfwSecureToken.php");

class BxPfwHostedCheckout extends BxPfwPayPal
{
	function BxPfwHostedCheckout($oDb, $oConfig, $aConfig)
	{
		parent::BxPfwPayPal($oDb, $oConfig, $aConfig);

		$this->_aCallParameters['TENDER'] = 'C';
	}

	function initializeCheckout($iPendingId, $aCartInfo)
	{
		$oSecureToken = new BxPfwSecureToken($this->_oDb, $this->_oConfig, $this->_aConfig); 
		$aSecureToken = $oSecureToken->getSecureToken($iPendingId, $aCartInfo);
		if($aSecureToken === false)
			return false;

		$sRequestUrl = $this->_oConfig->getPfwEndpoint(BX_PFW_ENDPOINT_TYPE_HOSTED);
		$sRequestData = array(
			'MODE' => $this->_oConfig->getMode() == BX_PFW_MODE_LIVE ? 'LIVE' : 'TEST', 
    		'SECURETOKEN' => $aSecureToken['token'], 
    		'SECURETOKENID' => $aSecureToken['token_id']
    	);

		Redirect($sRequestUrl, $sRequestData, 'post');
		exit;
	}

	function finalizeCheckout(&$aData)
	{
		$this->_logInfo(__METHOD__, $aData);

		$iPending = (int)$aData['INVNUM'];
		$aPending = $this->_oDb->getPending(array('type' => 'id', 'id' => $iPending));
        if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return array('code' => 0, 'message' => _t($this->_sLangsPrefix . 'err_already_processed'));

		$iResponseCode = (int)$aData['RESULT'];
		$sResponseMessage = process_db_input($aData['RESPMSG'], BX_TAGS_STRIP);

		$aResult = array(
			'code' => $iResponseCode == 0 ? 1 : 0, 
			'message' => $iResponseCode == 0 ? _t($this->_sLangsPrefix . 'msg_accepted') : $sResponseMessage,
			'pending_id' => $iPending
		);

        //--- Update pending transaction ---//
        $this->_oDb->updatePending($iPending, array(
            'order' => process_db_input($aData['PPREF'], BX_TAGS_STRIP),
        	'order_ref' => process_db_input($aData['PNREF'], BX_TAGS_STRIP),
            'error_code' => $aResult['code'],
            'error_msg' => $sResponseMessage
        ));

		return $aResult;
	}
}
