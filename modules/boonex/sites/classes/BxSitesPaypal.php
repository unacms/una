<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Sites Sites
 * @ingroup     TridentModules
 *
 * @{
 */

require_once(BX_SITES_PP_DIRECTORY_PATH_API . 'PPBootStrap.php');

class BxSitesPaypal
{
    protected $_oModule;

    protected $bLogNote = true;
    protected $bLogError = true;
    protected $bLogException = true;

    protected $bDemo = true;
    protected $bSsl = true;
    protected $bIpn = true;

    protected $sBusiness = '';
    protected $sCurrencyCode = '';
    protected $sUrlReturn = '';
    protected $sUrlCancel = '';

    protected $fAmountTrial = 0.00;
    protected $fAmountRegular = 0.00;
    protected $sDescription = "";

    protected $sActivationType = BX_SITES_PP_ACTIVATION_TRIAL_PERIOD;

    protected $bTrial = false;
    protected $iTrialMaxNumber = 0;

    //Period values: Day, Week, SemiMonth, Month, Year
    protected $sPeriodTrial = '';
    protected $sPeriodRegular = '';

    protected $iFrequencyTrial = 0;
    protected $iFrequencyRegular = 0;

    protected $iBillingCyclesTrial = 0;
    protected $iBillingCyclesRegular = 0;

    public $bProfileCreated = false;
    public $bProfileCanceled = false;
    public $bPaymentDone = false;
    public $fPaymentAmout = 0;
    public $bPaymentRefund = false;

    public $sResultMessage = '';

    function __construct(&$oModule)
    {
        $this->_oModule = $oModule;

        $this->bDemo = $this->_oModule->_oConfig->isPaymentDemo();
        $this->sCurrencyCode = $this->_oModule->_oConfig->getCurrencyCode();
        $this->sBusiness = $this->_oModule->_oConfig->getPaymentEmail();

        $this->fAmountTrial = $this->_oModule->_oConfig->getPaymentPrice(BX_SITES_PP_PERIOD_TRIAL);
        $this->fAmountRegular = $this->_oModule->_oConfig->getPaymentPrice(BX_SITES_PP_PERIOD_REGULAR);

        $this->iTrialMaxNumber = $this->_oModule->_oConfig->getTrialMaxNumber();

        $this->sPeriodTrial = $this->_oModule->_oConfig->getPaymentPeriod(BX_SITES_PP_PERIOD_TRIAL);
        $this->sPeriodRegular = $this->_oModule->_oConfig->getPaymentPeriod(BX_SITES_PP_PERIOD_REGULAR);

        $this->iFrequencyTrial = $this->_oModule->_oConfig->getPaymentFrequency(BX_SITES_PP_PERIOD_TRIAL);
        $this->iFrequencyRegular = $this->_oModule->_oConfig->getPaymentFrequency(BX_SITES_PP_PERIOD_REGULAR);

        $this->iBillingCyclesTrial = $this->_oModule->_oConfig->getPaymentBillingCycles(BX_SITES_PP_PERIOD_TRIAL);
        $this->iBillingCyclesRegular = $this->_oModule->_oConfig->getPaymentBillingCycles(BX_SITES_PP_PERIOD_REGULAR);

        $oPermalinks = BxDolPermalinks::getInstance();
        $this->sUrlReturn = BX_DOL_URL_ROOT . $oPermalinks->permalink('page.php?i=site-subscribe');
        $this->sUrlCancel = BX_DOL_URL_ROOT . $oPermalinks->permalink('page.php?i=site-create');
    }

    function setBusiness($sValue)
    {
        $this->sBusiness = $sValue;
    }

    function setCurrency($sValue)
    {
        $this->sCurrencyCode = $sValue;
    }

    function start($iAccountId)
    {
        $this->checkTrialAllowed(array('type' => 'id', 'value' => $iAccountId));

        $oBADetails = new BillingAgreementDetailsType();
        $oBADetails->BillingType = 'RecurringPayments';
        $oBADetails->BillingAgreementDescription = $this->sDescription;

        $oBAReqDetails = new SetCustomerBillingAgreementRequestDetailsType();
        $oBAReqDetails->BillingAgreementDetails = $oBADetails;
        $oBAReqDetails->ReturnURL = $this->sUrlReturn;
        $oBAReqDetails->CancelURL = $this->sUrlCancel;

        $oBARequest = new SetCustomerBillingAgreementRequestType();
        $oBARequest->SetCustomerBillingAgreementRequestDetails = $oBAReqDetails;

        $oBAReq = new SetCustomerBillingAgreementReq();
        $oBAReq->SetCustomerBillingAgreementRequest = $oBARequest;

        $oPaypalService = new PayPalAPIInterfaceServiceService();
        try {
            $oBAResponse = $oPaypalService->SetCustomerBillingAgreement($oBAReq);
        } catch (Exception $oException) {
            $this->_logException($oException);
            exit;
        }

        if(!isset($oBAResponse)) {
            $sLog = "---\n";
            $sLog .= "--- Get Token: {date}\n";
            $sLog .= "--- Request: " . $oPaypalService->getLastRequest() . "\n";
            $sLog .= "--- Response: " . $oPaypalService->getLastResponse() . "\n";
            $sLog .= "---\n";

            $this->_logError($sLog);
            return;
        }

        $sLog = "---\n";
        $sLog .= "--- Get Token: {date}\n";
        $sLog .= "--- Status: " . $oBAResponse->Ack . "\n";
        $sLog .= "--- Token: " . $oBAResponse->Token;

        $this->_logNote($sLog);
        $this->_logNote($oBAResponse);
        $this->_logNote("---\n");

        if($oBAResponse->Ack == 'Success') {
            $sToken = $oBAResponse->Token;

            $oAccount = $this->_oModule->getObject('Account');
            $oAccount->onTokenReceived($sToken, $iAccountId);

            $sUrl = $this->bDemo ? 'https://www.sandbox.paypal.com/' : 'https://www.paypal.com/';
            return $sUrl . 'webscr?cmd=_customer-billing-agreement&token=' . $sToken;
        }
    }

    function subscribe($sTocken)
    {
        $this->checkTrialAllowed(array('type' => 'token', 'value' => $sTocken));

        $oRPProfileDetails = new RecurringPaymentsProfileDetailsType();
        $oRPProfileDetails->BillingStartDate = date(DATE_ATOM);

        $oPaymentBillingPeriod = new BillingPeriodDetailsType();
        $oPaymentBillingPeriod->BillingPeriod = $this->sPeriodRegular;
        $oPaymentBillingPeriod->BillingFrequency = $this->iFrequencyRegular;
        $oPaymentBillingPeriod->TotalBillingCycles = $this->iBillingCyclesRegular;
        $oPaymentBillingPeriod->Amount = new BasicAmountType($this->sCurrencyCode, $this->fAmountRegular);

        $oScheduleDetails = new ScheduleDetailsType();
        $oScheduleDetails->Description = $this->sDescription;

        switch($this->sActivationType) {
            case BX_SITES_PP_ACTIVATION_INIT_AMOUNT:
                $oActivationDetails = new ActivationDetailsType();
                $oActivationDetails->InitialAmount = new BasicAmountType($this->sCurrencyCode, $this->fAmountTrial);
                $oActivationDetails->FailedInitialAmountAction = 'CancelOnFailure';

                $oScheduleDetails->ActivationDetails = $oActivationDetails;
                break;

            case BX_SITES_PP_ACTIVATION_TRIAL_PERIOD:
                if(!$this->bTrial)
                    break;

                $oTrialBillingPeriod = new BillingPeriodDetailsType();
                $oTrialBillingPeriod->BillingPeriod = $this->sPeriodTrial;
                $oTrialBillingPeriod->BillingFrequency = $this->iFrequencyTrial;
                $oTrialBillingPeriod->TotalBillingCycles = $this->iBillingCyclesTrial;
                $oTrialBillingPeriod->Amount = new BasicAmountType($this->sCurrencyCode, $this->fAmountTrial);

                $oScheduleDetails->TrialPeriod  = $oTrialBillingPeriod;
                break;
        }

        $oScheduleDetails->PaymentPeriod = $oPaymentBillingPeriod;
        $oScheduleDetails->MaxFailedPayments = 1;
        $oScheduleDetails->AutoBillOutstandingAmount = 'NoAutoBill'; // 'AddToNextBilling'

        $oRPProfileRequestDetail = new CreateRecurringPaymentsProfileRequestDetailsType();
        $oRPProfileRequestDetail->Token = $sTocken;
        $oRPProfileRequestDetail->ScheduleDetails = $oScheduleDetails;
        $oRPProfileRequestDetail->RecurringPaymentsProfileDetails = $oRPProfileDetails;

        $oRPProfileRequest = new CreateRecurringPaymentsProfileRequestType();
        $oRPProfileRequest->CreateRecurringPaymentsProfileRequestDetails = $oRPProfileRequestDetail;

        $oRPProfileReq =  new CreateRecurringPaymentsProfileReq();
        $oRPProfileReq->CreateRecurringPaymentsProfileRequest = $oRPProfileRequest;

        $oPaypalService = new PayPalAPIInterfaceServiceService();
        try {
            $oRPProfileResponse = $oPaypalService->CreateRecurringPaymentsProfile($oRPProfileReq);
        } catch (Exception $oException) {
            $this->_logException($oException);
            exit;
        }

        $sResultMessage = '_bx_sites_paypal_err_subscribe';
        if(!isset($oRPProfileResponse)) {
            $sLog = "---\n";
            $sLog .= "--- Create Recurring: {date}\n";
            $sLog .= "--- Request: " . $oPaypalService->getLastRequest() . "\n";
            $sLog .= "--- Response: " . $oPaypalService->getLastResponse() . "\n";
            $sLog .= "---\n";

            $this->_logError($sLog);
            return $sResultMessage;
        }

        $sLog = "---\n";
        $sLog .= "--- Create Recurring: {date}\n";
        $sLog .= "--- Status: " . $oRPProfileResponse->Ack . "\n";
        $sLog .= "--- Profile ID: " . $oRPProfileResponse->CreateRecurringPaymentsProfileResponseDetails->ProfileID;

        $this->_logNote($sLog);
        $this->_logNote($oRPProfileResponse);
        $this->_logNote("---\n");

        if($oRPProfileResponse->Ack == 'Success') {
            $sProfileId = $oRPProfileResponse->CreateRecurringPaymentsProfileResponseDetails->ProfileID;

            $oAccount = $this->_oModule->getObject('Account');
            $oAccount->onProfileCreated($sProfileId, $sTocken);

            $sResultMessage = '_bx_sites_paypal_msg_subscribe';
        }

        return $sResultMessage;
    }

    function process(&$aData)
    {
        $sLog = "---\n";
        $sLog .= "--- IPN Received: {date}\n";

        $this->_logNote($sLog);
        $this->_logNote($aData);
        $this->_logNote("---\n");

        $bResult = $this->processData($aData);
        if($this->sResultMessage != '') {
            $sLog = "---\n";
            $sLog .= "--- IPN Result Message: " . $this->sResultMessage . "\n";
            $sLog .= "---\n";

            $sMethod = $bResult ? '_logNote' : '_logError';
            $this->$sMethod($sLog);
        }

        return $bResult;
    }

    function performAction($sProfileId, $sAction = BX_SITES_PP_RPA_CANCEL)
    {
        if(empty($sProfileId))
            return false;

        $oRPPStatusReqestDetails = new ManageRecurringPaymentsProfileStatusRequestDetailsType();
        $oRPPStatusReqestDetails->Action =  $sAction;
        $oRPPStatusReqestDetails->ProfileID = $sProfileId;

        $oRPPStatusReqest = new ManageRecurringPaymentsProfileStatusRequestType();
        $oRPPStatusReqest->ManageRecurringPaymentsProfileStatusRequestDetails = $oRPPStatusReqestDetails;

        $oRPPStatusReq = new ManageRecurringPaymentsProfileStatusReq();
        $oRPPStatusReq->ManageRecurringPaymentsProfileStatusRequest = $oRPPStatusReqest;

        $oPaypalService = new PayPalAPIInterfaceServiceService();
        try {
            $oRPPStatusResponse = $oPaypalService->ManageRecurringPaymentsProfileStatus($oRPPStatusReq);
        } catch (Exception $oException) {
            $this->_logException($oException);
            return false;
        }

        if(!isset($oRPPStatusResponse)) {
            $sLog = "---\n";
            $sLog .= "--- Perform Action: {date}\n";
            $sLog .= "--- Request: " . $oPaypalService->getLastRequest() . "\n";
            $sLog .= "--- Response: " . $oPaypalService->getLastResponse() . "\n";
            $sLog .= "---\n";

            $this->_logError($sLog);
            return false;
        }

        if($oRPPStatusResponse->Ack == 'Success') {
            $oAccount = $this->_oModule->getObject('Account');
            $oAccount->onActionPerformed($sProfileId, $sAction);
        }

        return true;
    }

    protected function processData(&$aData)
    {
        if($aData['txn_type'] == 'recurring_payment_profile_created') {
            $this->bProfileCreated = true;
            $this->sResultMessage = 'Recurring profile was confirmed: ' . $aData['recurring_payment_id'];

            //TODO: Check for Initial Payment ('initial_payment_status', 'initial_payment_amount') if it will be used.
            return true;
        }

        if($aData['txn_type'] == 'recurring_payment_profile_cancel') {
            $this->bProfileCanceled = true;
            $this->sResultMessage = 'Recurring profile was canceled: ' . $aData['recurring_payment_id'];
            return true;
        }

        if(in_array($aData['payment_status'], array('Reversed', 'Refunded'))) {
            $this->bPaymentRefund = true;
            $this->sResultMessage = 'Order canceled: ' . $aData['reason_code'];
            return true;
        }

        if($aData['payment_status'] != 'Completed') {
            $this->sResultMessage = 'Payment is not completed';
            return false;
        }

        if($aData['business'] != $this->sBusiness) {
            $this->sResultMessage = 'Wrong receiver email (required: ' . $this->sBusiness . ' received: ' . $aData['business'] . ')';
            return false;
        }

        $bResult = $this->verifyData($aData);
        if(!$bResult)
            return false;

        $sAmountVar = '';
        if(in_array($aData['period_type'], array(BX_SITES_PP_PERIOD_TRIAL, BX_SITES_PP_PERIOD_REGULAR)))
            $sAmountVar = 'fAmount' . ucfirst($aData['period_type']);

        if($sAmountVar == '' || !isset($this->$sAmountVar)) {
            $this->sResultMessage = 'Cannot determine payment period';
            return false;
        }

        $fAmount = $this->getAmount($aData);
        if($this->$sAmountVar != $fAmount) {
            $this->sResultMessage = 'Wrong price ' . $this->$sAmountVar . ' != ' . $fAmount;
            return false;
        }

        $this->fPaymentAmout = $fAmount;
        $this->bPaymentDone = true;
        $this->sResultMessage = 'Payment was successfully processed';
        return true;
    }
    protected function verifyData(&$aData)
    {
        $sRequest = 'cmd=_notify-validate';
        foreach($aData as $sKey => $sValue) {
            if(in_array($sKey, array('cmd', 'email', 'fullname', 'username', 'term', 'boonex_price')))
                continue;

            $sRequest .= '&'. $sKey .'='. urlencode(get_magic_quotes_gpc() ? stripslashes($sValue) : $sValue);
        }

        //--- Post back to PayPal system to validate ---//
        $sHeader = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $sHeader .= $this->bDemo ? "Host: www.sandbox.paypal.com\r\n" : "Host: www.paypal.com\r\n";
        $sHeader .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $sHeader .= "Content-Length: " . strlen($sRequest) . "\r\n";
        $sHeader .= "Connection: close\r\n\r\n";

        //--- open socket ---//
        $iSockError = 0;
        $sSockError = '';
        $sUrl = $this->bDemo ? 'www.sandbox.paypal.com' : 'www.paypal.com';
        if($this->bSsl)
            $rHandle = fsockopen("ssl://" . $sUrl, 443, $iSockError, $sSockError, 60);
        else
            $rHandle = fsockopen("tcp://" . $sUrl, 80, $iSockError, $sSockError, 60);

        if(!$rHandle) {
            $this->sResultMessage = 'Cannot connect to remote host for validation (' . $sSockError . ')';
            return false;
        }

        //--- Send data ---//
        fputs($rHandle, $sHeader);
        fputs($rHandle, $sRequest);

        //--- Read the body data ---//
        $sResponse = '';
        while (!feof($rHandle))
            $sResponse .= fread($rHandle, 1024);
        fclose($rHandle);

        //--- Parse the data ---//
        list($sResponseHeader, $sResponseContent) = explode("\r\n\r\n", $sResponse);

        $aLines = explode("\n", $sResponseContent);
        array_walk($aLines, create_function('&$arg', "\$arg = trim(\$arg);") );

        if(strcmp($aLines[0], "INVALID") == 0) {
            $this->sResultMessage = 'Transaction verification failed: ' . $sResponse;
            return false;
        }

        if(strcmp($aLines[0], "VERIFIED") != 0) {
            $this->sResultMessage = 'No verification status received (' . $aLines[0] . '): ' . $sResponse;
            return false;
        }

        return true;
    }

    protected function getAmount(&$aData)
    {
        $sCurrencyCode = $this->sCurrencyCode;
        $bQuantity = array_key_exists('quantity', $aData) && $aData['quantity'] > 1;

        $fResult = 0;
        if($aData['mc_currency'] == $sCurrencyCode  && $sCurrencyCode == 'USD' && strlen($aData['payment_gross']))
            $fResult = $bQuantity ? $aData['payment_gross']/$aData['quantity'] : $aData['payment_gross'];
        else if($aData['mc_currency'] == $sCurrencyCode && strlen($aData['mc_gross']))
            $fResult = $bQuantity ? $aData['mc_gross']/$aData['quantity'] : $aData['mc_gross'];
        else if($aData['settle_currency'] == $sCurrencyCode && strlen($aData['settle_amount']))
            $fResult = $bQuantity ? $aData['settle_amount']/$aData['quantity'] : $aData['settle_amount'];

        return (float)$fResult;
    }

    protected function getDescription($bTrial = false)
    {
        $sCurrency = $this->sCurrencyCode;
        $sDuration = _t('_bx_sites_paypal_duration_' . strtolower($this->sPeriodRegular));
        $sAmountRegular = number_format($this->fAmountRegular, 2);

        if(!$bTrial)
            return _t('_bx_sites_paypal_product_description_wo_trial', $sDuration, $sAmountRegular, $sCurrency);

        if($this->fAmountTrial == 0)
            return _t('_bx_sites_paypal_product_description_free_trial', $sDuration, $sAmountRegular, $sCurrency);

        $sAmountTrial = number_format($this->fAmountTrial, 2);
        return _t('_bx_sites_paypal_product_description', $sDuration, $sAmountRegular, $sCurrency, $sAmountTrial, $sCurrency);
    }

    protected function checkTrialAllowed($aParams)
    {
        $aAccount = $this->_oModule->_oDb->getAccount($aParams);
        $iAccountTrials = isset($aAccount['owner_trials']) ? (int)$aAccount['owner_trials'] : 0;

        $this->bTrial = $this->iTrialMaxNumber == 0 || $this->iTrialMaxNumber > $iAccountTrials;
        $this->sDescription = $this->getDescription($this->bTrial);
    }

    protected function _logNote($mixedValue)
    {
        if(!$this->bLogNote)
            return;

        $this->_log($mixedValue);
    }

    protected function _logError($mixedValue)
    {
        if(!$this->bLogError)
            return;

        $sMessage = "--- Error Occured: {date}";

        $this->_log($sMessage);
        $this->_log($mixedValue);
        $this->_log($sMessage);
    }

    protected function _logException($oException)
    {
        if(!$this->bLogException)
            return;

        $sExType = "Unknown";
        $sExMessage = "";
        $sExMessageDetailed = "";

        if(isset($oException)) {
            $sExMessage = $oException->getMessage();
            $sExType = get_class($oException);

            if($oException instanceof PPConnectionException)
                $sExMessageDetailed = "Error connecting to " . $oException->getUrl();
            else if($oException instanceof PPMissingCredentialException || $oException instanceof PPInvalidCredentialException)
                $sExMessageDetailed = $oException->errorMessage();
            else if($oException instanceof PPConfigurationException)
                $sExMessageDetailed = "Invalid configuration. Please check your configuration file";
        }

        $sLog = "---\n";
        $sLog .= "--- PayPal SDK Exception: {date}\n";
        $sLog .= "--- Type: " . $sExType . "\n";
        $sLog .= "--- Message: " . $sExMessage . "\n";
        $sLog .= "--- Details: " . $sExMessageDetailed . "\n";
        $sLog .= "---\n";

        $sMessage = "--- Exception Occured: {date}\n";

        $this->_log($sMessage);
        $this->_log($sLog);
        $this->_log($sMessage);
    }

    protected function _log($mixedValue)
    {
        bx_import('Log', $this->_oModule->_aModule);
        $oLog = new BxSitesLog($this->_oModule->_oConfig->getHomePath() . 'log/pp.log');
        $oLog->log($mixedValue);
    }
}

/** @} */
