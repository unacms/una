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
    protected $_aParseParams;

    public function __construct()
    {
        parent::__construct();

        $this->_aParseParams = array(
            'site_title' => getParam('site_title')
        );
    }

    public function processing()
    {
        if(getParam('enable_notification_account') != 'on')
            return;

        set_time_limit(0);
        ignore_user_abort();

        $this->processNewlyJoined();

        if(empty($this->_aParseParams['account_count']) || empty($this->_aParseParams['account_output']))
			return;

        $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('t_Account', $this->_aParseParams);
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

        $iAccounts = 0;
        $sAccounts = "";
        foreach($aAccounts as $aAccount) {
        	$oProfile = BxDolProfile::getInstance($aAccount['profile_id']);
        	if(!$oProfile)
        	    continue;

        	$sAccounts .= _t('_sys_notification_account_link', $oProfile->getUrl(), $oProfile->getDisplayName(), $aAccount['email']);
        	$iAccounts += 1;
        }

        if(!$sAccounts)
            return;

        $this->_aParseParams['account_count'] = $iAccounts;
        $this->_aParseParams['account_output'] = _t('_sys_notification_account', $sAccounts);
    }
}

/** @} */
