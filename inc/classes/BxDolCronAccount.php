<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolCronAccount extends BxDolCron
{
    protected function start()
    {
        set_time_limit(0);
        ignore_user_abort();
        ob_start();
    }

    protected function finish()
    {
        $sOutput = ob_get_clean();
		if(!$sOutput || getParam('enable_notification_account') != 'on')
			return;

        $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('t_Account', array(
        	'site_title' => getParam('site_title'),
        	'account_output' => $sOutput
        ));
        if(empty($aTemplate))
        	return;

        $aSent = array();
        $aProfiles = BxDolAclQuery::getInstance()->getProfilesByMembership(array(MEMBERSHIP_ID_MODERATOR, MEMBERSHIP_ID_ADMINISTRATOR));
        foreach($aProfiles as $aProfile) {
        	$oProfile = BxDolProfile::getInstance($aProfile['id']);
        	if(!$oProfile)
        		continue;
        	
        	$oAccount = $oProfile->getAccountObject();
        	if(!$oAccount || !$oAccount->isConfirmed())
        		continue;

        	$aAccount = $oAccount->getInfo();
        	if((int)$aAccount['receive_news'] != 1)
        		continue;

        	if(in_array($aAccount['email'], $aSent))
        		continue;

            if(sendMail($aAccount['email'], $aTemplate['Subject'], $aTemplate['Body'], $aProfile['id']))
            	$aSent[] = $aAccount['email'];
        }
    }

    protected function processNewlyJoined()
    {
        $aAccounts = BxDolAccountQuery::getInstance()->getAccounts(array('type' => 'by_join_date', 'date' => time() - 86400));
        if(empty($aAccounts) || !is_array($aAccounts))
            return;

        $sAccounts = "";
        foreach($aAccounts as $aAccount) {
        	$oProfile = BxDolProfile::getInstance($aAccount['profile_id']);
        	if(!$oProfile)
        	    continue;

        	$sAccounts .= _t('_sys_notification_account_link', $oProfile->getUrl(), $oProfile->getDisplayName(), $aAccount['email']);
        }

        if(!$sAccounts)
            return;

        echo _t('_sys_notification_account', $sAccounts);
    }

    public function processing()
    {
        $this->start();

        $this->processNewlyJoined();

        $this->finish();
    }
}

/** @} */
