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

class BxPaymentJoin extends BxBaseModPaymentJoin
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
     * 
     * TODO: Should be tested when paid memberships with "Paid Before Join" or some similar feature will appear.
     */
	public function serviceGetBlockJoin()
    {
    	$sSessionKeyPending = $this->_oModule->_oConfig->getKey('KEY_SESSION_PENDING');
    	$sRequestKeyPending = $this->_oModule->_oConfig->getKey('KEY_REQUEST_PENDING');

    	$oSession = BxDolSession::getInstance();
    	$iPendingId = (int)$oSession->getValue($sSessionKeyPending);

    	if(empty($iPendingId) && bx_get($sRequestKeyPending) !== false) {
    		$iPendingId = (int)bx_get($sRequestKeyPending);

    		$oSession->setValue($sSessionKeyPending, $iPendingId);
    	}

		if(empty($iPendingId))
			return MsgBox(_t($this->_sLangsPrefix . 'err_not_allowed'));

		$aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
	    if(empty($aPending['order']) || (int)$aPending['error_code'] != 1)
			return MsgBox(_t($this->_sLangsPrefix . 'err_not_processed'));

		if((int)$aPending['processed'] == 1)
			return MsgBox(_t($this->_sLangsPrefix . 'err_already_processed'));

		//--- 'System' -> 'Join after Payment' for Alerts Engine ---//
		$bOverride = false;
		$sOverrideError = '';

		bx_import('BxDolAlerts');
        $oAlert = new BxDolAlerts('system', 'join_after_payment', 0, 0, array('override' => &$bOverride, 'override_error' => &$sOverrideError));
        $oAlert->alert();

        if($bOverride)
        	return;
		//--- 'System' -> 'Join after Payment' for Alerts Engine ---//

    	$sContent = BxDolService::call('system', 'create_account_form', array(array('action' => $this->_oModule->_oConfig->getUrl('URL_JOIN'))), 'TemplServiceAccount');
    	if(!empty($sOverrideError))
    		$sContent = MsgBox(_t($sOverrideError)) . $sContent;

		return array(
        	'content' => $sContent
		);
    }

	public function performJoin($iPendingId, $sClientName = '', $sClientEmail = '')
    {
		BxDolSession::getInstance()->setValue($this->_oModule->_oConfig->getKey('KEY_SESSION_PENDING'), (int)$iPendingId);

		if(!empty($sClientName) && !empty($sClientEmail)) {
			bx_import('BxDolEmailTemplates');
			$oEmailTemplates = new BxDolEmailTemplates();

			$aTemplate = $oEmailTemplates->parseTemplate($this->_oModule->_oConfig->getPrefix('general') . 'paid_need_join', array(
				'RealName' => $sClientName,
				'JoinLink' => $this->_oModule->_oConfig->getUrl('URL_JOIN', array($this->_oModule->_oConfig->getKey('KEY_REQUEST_PENDING') => (int)$iPendingId))
			));

			sendMail($sClientEmail, $aTemplate['Subject'], $aTemplate['Body'], 0, array(), 'html', false, true);
		}

		header('Location: ' . $this->_oModule->_oConfig->getUrl('URL_JOIN'));
		exit;
	}

	public function onProfileJoin($iProfileId)
    {
    	$sSessionKeyPending = $this->_oModule->_oConfig->getKey('KEY_SESSION_PENDING');

    	$oSession = BxDolSession::getInstance();
		$iPendingId = (int)$oSession->getValue($sSessionKeyPending);

        if(empty($iProfileId) || empty($iPendingId))
        	return;
 
		$aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
		if(empty($aPending) || (isset($aPending['client_id']) && (int)$aPending['client_id'] != 0))
			return;

		if(!$this->_oModule->_oDb->updateOrderPending($iPendingId, array('client_id' => $iProfileId)))
			return;

		$this->_oModule->registerPayment($iPendingId);

		$oSession->unsetValue($sSessionKeyPending);
    }
}

/** @} */
