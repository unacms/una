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

class BxSitesAccount extends BxDol
{
    protected $_oModule;

    protected $bLogNote = true;
    protected $bLogEmail = true;
    protected $bLogError = true;

    function __construct($oModule)
    {
        parent::__construct();
        $this->_oModule = $oModule;
    }

    public function onAccountCreated($iId)
    {
        $aAccount = $this->_oModule->_oDb->getAccount(array('type' => 'id', 'value' => $iId));
        if(empty($aAccount) || !is_array($aAccount))
            return;

        if(!$this->_oModule->_oDb->isOwner(array('id' => $aAccount['owner_id'])))
            $this->_oModule->_oDb->insertOwner(array(
                'id' => $aAccount['owner_id']
            ));
    }

    function onTokenReceived($sToken, $iAccountId)
    {
        if(empty($sToken) || empty($iAccountId))
            return;

        $this->_oModule->_oDb->deletePaymentDetails(array('account_id' => $iAccountId));
        $this->_oModule->_oDb->insertPaymentDetails(array('account_id' => $iAccountId, 'token' => $sToken));
    }

    public function onProfileCreated($sProfileId, $sToken)
    {
        if(empty($sProfileId) || empty($sToken))
            return;

        $sSid = encryptUserPwd($sProfileId, genRndSalt());
        $this->_oModule->_oDb->updatePaymentDetails(array('profile_id' => $sProfileId, 'profile_sid' => $sSid), array('token' => $sToken));

        $aAccount = $this->_oModule->_oDb->getAccount(array('type' => 'profile_id', 'value' => $sProfileId));
        if(!empty($aAccount) && is_array($aAccount)) {
            $this->_oModule->_oDb->updateAccount(array('status' => BX_SITES_ACCOUNT_STATUS_PENDING), array('id' => $aAccount['id']));

            // perform action
            $this->_oModule->isAllowedAdd(true);

            // alert
            bx_alert($this->_oModule->getName(), 'added', $aAccount['id']);
        }
    }

    public function onProfileConfirmed(&$aData)
    {
        $sProfileId = $aData['recurring_payment_id'];

        $aAccount = $this->_oModule->_oDb->getAccount(array('type' => 'profile_id', 'value' => $sProfileId));
        if(!empty($aAccount) && is_array($aAccount)) {
            $aUpdateParams = array(
                'email' => bx_process_input($aData['payer_email']),
                'paid' => strtotime($aData['next_payment_date'])
            );

            // check whether FREE TRIAL is used.
            if((float)$aData['amount'] == 0 && (float)$aData['amount_per_cycle'] == 0 && $aData['period_type'] == BX_SITES_PP_PERIOD_TRIAL) {
                $sFirstName = bx_process_input($aData['first_name']);
                $sLastName = bx_process_input($aData['last_name']);

                $aUpdateParams['paid'] += $this->_oModule->_oConfig->getTrialDuration();
                $aUpdateParams['status'] = BX_SITES_ACCOUNT_STATUS_TRIAL;

                $oPermalinks = BxDolPermalinks::getInstance();

                $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('bx_sites_site_created',  array(
                    'RealName' => $sFirstName . (!empty($sFirstName) && !empty($sLastName) ? ' ' . $sLastName : ''),
                    'Domain' => $this->_oModule->getDomain($aAccount['domain']),
                    'Email' => $aUpdateParams['email'],
                    'Status' => _t('_bx_sites_txt_status_' . BX_SITES_ACCOUNT_STATUS_TRIAL),
                    'NextPaymentDate' => bx_time_js($aUpdateParams['paid']),
                    'DetailsFormUrl' => BX_DOL_URL_ROOT . $oPermalinks->permalink('page.php?i=site-edit&sid=' . $aAccount['pd_profile_sid'])
                ));

                if(!empty($aTemplate)) {
                    sendMail($aUpdateParams['email'], $aTemplate['Subject'], $aTemplate['Body']);

                    $sLog = "---\n";
                    $sLog .= "--- Send Email Notification: {date}\n";
                    $sLog .= "--- Email: " . $aUpdateParams['email'] . "\n";
                    $sLog .= "--- Subject: " . $aTemplate['Subject'] . "\n";
                    $sLog .= "--- Body: " . $aTemplate['Body'] . "\n";
                    $sLog .= "---\n";
                    $this->_logEmail($sLog);
                }
            }

            $this->_oModule->_oDb->updateAccount($aUpdateParams, array('id' => $aAccount['id']));
        }
    }

    public function onProfileCanceled(&$aData)
    {
        $sProfileId = $aData['recurring_payment_id'];

        $aAccount = $this->_oModule->_oDb->getAccount(array('type' => 'profile_id', 'value' => $sProfileId));
        if(!empty($aAccount) && is_array($aAccount)) {
            $this->_oModule->_oDb->updateAccount(array('status' => BX_SITES_ACCOUNT_STATUS_CANCELED), array('id' => $aAccount['id']));

            $sFirstName = bx_process_input($aData['first_name']);
            $sLastName = bx_process_input($aData['last_name']);

            $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('bx_sites_site_canceled',  array(
                'RealName' => $sFirstName . (!empty($sFirstName) && !empty($sLastName) ? ' ' . $sLastName : ''),
                'Domain' => $this->_oModule->getDomain($aAccount['domain']),
            ));

            if(!empty($aTemplate)) {
                sendMail($aAccount['email'], $aTemplate['Subject'], $aTemplate['Body']);

                $sLog = "---\n";
                $sLog .= "--- Send Email Notification: {date}\n";
                $sLog .= "--- Email: " . $aAccount['email'] . "\n";
                $sLog .= "--- Subject: " . $aTemplate['Subject'] . "\n";
                $sLog .= "--- Body: " . $aTemplate['Body'] . "\n";
                $sLog .= "---\n";
                $this->_logEmail($sLog);
            }
        }
    }

    public function onActionPerformed($sProfileId, $sAction)
    {
        if($sAction != BX_SITES_PP_RPA_CANCEL)
            return;

        $aAccount = $this->_oModule->_oDb->getAccount(array('type' => 'profile_id', 'value' => $sProfileId));
        if(!empty($aAccount) && is_array($aAccount)) {
            $this->_oModule->_oDb->updateAccount(array('status' => BX_SITES_ACCOUNT_STATUS_PENDING), array('id' => $aAccount['id']));
        }
    }

    public function onPaymentReceived(&$aData, $fAmount)
    {
        $sProfileId = $aData['recurring_payment_id'];

        $aAccount = $this->_oModule->_oDb->getAccount(array('type' => 'profile_id', 'value' => $sProfileId));
        if(!empty($aAccount) && is_array($aAccount)) {
            $sTransaction = bx_process_input($aData['txn_id']);
            $iWhen = strtotime($aData['payment_date']);
            $iPaidUntil = strtotime($aData['next_payment_date']);

            $sActStatus = $sPmtType = '';
            if($aData['period_type'] == BX_SITES_PP_PERIOD_TRIAL) {
                $sActStatus = BX_SITES_ACCOUNT_STATUS_TRIAL;
                $sPmtType = BX_SITES_TRANSACTION_TRIAL;

                $this->_oModule->_oDb->updateOwner(array('trials' => $aAccount['owner_trials'] + 1), array('id' => $aAccount['owner_id']));
            } else if($aData['period_type'] == BX_SITES_PP_PERIOD_REGULAR) {
                $sActStatus = BX_SITES_ACCOUNT_STATUS_ACTIVE;
                $sPmtType = BX_SITES_TRANSACTION_REGULAR;
            }

            $this->_oModule->_oDb->insertPaymentHistory(array(
                'account_id' => $aAccount['id'],
                'type' => $sPmtType,
                'transaction' => $sTransaction,
                'amount' => $fAmount,
                'when' => $iWhen,
                'when_next' => $iPaidUntil
            ));

            $this->_oModule->_oDb->updateAccount(array('paid' => $iPaidUntil, 'status' => $sActStatus), array('id' => $aAccount['id']));

            $aTemplate = array();
            $sFirstName = bx_process_input($aData['first_name']);
            $sLastName = bx_process_input($aData['last_name']);

            /*--- Site was created and paid ---*/
            if($aAccount['status'] == BX_SITES_ACCOUNT_STATUS_PENDING && in_array($sActStatus, array(BX_SITES_ACCOUNT_STATUS_TRIAL, BX_SITES_ACCOUNT_STATUS_ACTIVE))) {
                $oPermalinks = BxDolPermalinks::getInstance();

                $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('bx_sites_site_created_and_paid',  array(
                    'RealName' => $sFirstName . (!empty($sFirstName) && !empty($sLastName) ? ' ' . $sLastName : ''),
                    'Domain' => $this->_oModule->getDomain($aAccount['domain']),
                    'Email' => $aAccount['email'],
                    'Amount' => $fAmount . ' ' . $this->_oModule->_oConfig->getCurrencyCode(),
                    'Status' => _t('_bx_sites_txt_status_' . $sActStatus),
                    'NextPaymentDate' => bx_time_js($iPaidUntil),
                    'DetailsFormUrl' => BX_DOL_URL_ROOT . $oPermalinks->permalink('page.php?i=site-edit&sid=' . $aAccount['pd_profile_sid'])
                ));
            }
            /*--- Payment was received ---*/
            else {
                $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('bx_sites_payment_received',  array(
                    'RealName' => $sFirstName . (!empty($sFirstName) && !empty($sLastName) ? ' ' . $sLastName : ''),
                    'Amount' => $fAmount . ' ' . $this->_oModule->_oConfig->getCurrencyCode(),
                    'NextPaymentDate' => bx_time_js($iPaidUntil)
                ));
            }

            if(!empty($aTemplate)) {
                sendMail($aAccount['email'], $aTemplate['Subject'], $aTemplate['Body']);

                $sLog = "---\n";
                $sLog .= "--- Send Email Notification: {date}\n";
                $sLog .= "--- Email: " . $aAccount['email'] . "\n";
                $sLog .= "--- Subject: " . $aTemplate['Subject'] . "\n";
                $sLog .= "--- Body: " . $aTemplate['Body'] . "\n";
                $sLog .= "---\n";
                $this->_logEmail($sLog);
            }
        }
    }

    public function onPaymentRefunded(&$aData)
    {
        //TODO: Process refund
    }

    protected function _logEmail($mixedValue)
    {
        if(!$this->bLogEmail)
            return;

        $this->_log($mixedValue);
    }

    protected function _log($mixedValue)
    {
        bx_import('Log', $this->_oModule->_aModule);
        $oLog = new BxSitesLog($this->_oModule->_oConfig->getHomePath() . 'log/emails.log');
        $oLog->log($mixedValue);
    }
}

/** @} */
