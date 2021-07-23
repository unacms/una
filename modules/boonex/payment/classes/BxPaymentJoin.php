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
     */

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-page_blocks Page Blocks
     * @subsubsection bx_payment-get_block_join get_block_join
     * 
     * @code bx_srv('bx_payment', 'get_block_join', [...], 'Join'); @endcode
     * 
     * Get page block with join form to complete the payment.
     * TODO: Should be tested when paid memberships with "Paid Before Join" or some similar feature will appear.
     *
     * @return an array describing a block to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPaymentJoin::serviceGetBlockJoin
     */
    /** 
     * @ref bx_payment-get_block_join "get_block_join"
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
            return array(
                'content' => MsgBox(_t($this->_sLangsPrefix . 'err_not_allowed'))
            );

        $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
        if(empty($aPending['order']) || (int)$aPending['error_code'] != 1)
            return array(
                'content' => MsgBox(_t($this->_sLangsPrefix . 'err_not_processed'))
            );

        if((int)$aPending['processed'] == 1)
            return array(
                'content' => MsgBox(_t($this->_sLangsPrefix . 'err_already_processed'))
            );

        //--- 'System' -> 'Join after Payment' for Alerts Engine ---//
        $bOverride = false;
        $sOverrideError = '';
        $this->_oModule->alert('join_after_payment', 0, 0, array('override' => &$bOverride, 'override_error' => &$sOverrideError));
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
            $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate($this->_oModule->_oConfig->getPrefix('general') . 'paid_need_join', array(
                'RealName' => $sClientName,
                'JoinLink' => $this->_oModule->_oConfig->getUrl('URL_JOIN', array($this->_oModule->_oConfig->getKey('KEY_REQUEST_PENDING') => (int)$iPendingId))
            ));

            sendMail($sClientEmail, $aTemplate['Subject'], $aTemplate['Body'], 0, array(), BX_EMAIL_SYSTEM);
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
