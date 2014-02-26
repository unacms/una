<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Antispam Antispam
 * @ingroup     DolphinModules
 *
 * @{
 */

// TODO: add checking on join form

bx_import('BxDolModule');

class BxAntispamModule extends BxDolModule 
{
    function __construct(&$aModule) 
    {
        parent::__construct($aModule);
    }

    function serviceIpTable () 
    {
        return $this->_grid('bx_antispam_grid_ip_table');
    }

    function serviceDnsblList () 
    {
        return $this->_grid('bx_antispam_grid_dnsbl');
    }

    function serviceBlockLog () 
    {
        return $this->_grid('bx_antispam_grid_block_log');
    }

    /**
     * Check text for spam.
     * First it check if IP is whitelisted(or under cron execution or user is admin) - for whitelisted IPs check for spam isn't performed,
     * then it checks URLs found in text for DNSURI black lists (@see BxAntispamDNSURIBlacklists),
     * then it checks text in Akismet service (@see BxAntispamAkismet).
     * It can send report if spam is found or tries to inform caller to block the content (depending on configuration).
     * 
     * @param $sContent content to check for spam
     * @param $sIp IP address of content poster
     * @param $isStripSlashes slashes parameter:
     *          BX_SLASHES_AUTO - automatically detect magic_quotes_gpc setting
     *          BX_SLASHES_NO_ACTION - do not perform any action with slashes
     * @return true if spam detected and content shouln't be recorded, false if content should be processed as usual.
     */
    function serviceIsSpam ($sContent, $sIp = '', $isStripSlashes = BX_SLASHES_AUTO) 
    {
        if (defined('BX_DOL_CRON_EXECUTE') || isAdmin())
            return false;

        if ($this->serviceIsIpWhitelisted($sIp))
            return false;

        if (get_magic_quotes_gpc() && $isStripSlashes == BX_SLASHES_AUTO)
            $sContent = stripslashes($sContent);

        $bRet = false;
        if ('on' == $this->_oConfig->getAntispamOption('uridnsbl_enable')) {
            $oDNSURIBlacklists = bx_instance('BxAntispamDNSURIBlacklists', array(), $this->_aModule);
            if ($oDNSURIBlacklists->isSpam($sContent)) {
                $oDNSURIBlacklists->onPositiveDetection($sContent);
                $bRet = true;
            }
        }

        if (!$bRet && 'on' == $this->_oConfig->getAntispamOption('akismet_enable')) {
            $oAkismet = bx_instance('BxAntispamAkismet', array(), $this->_aModule);
            if ($oAkismet->isSpam($sContent)) {
                $oAkismet->onPositiveDetection($sContent);
                $bRet = true;
            }
        }

        bx_alert('bx_antispam', 'is_spam', bx_get_logged_profile_id(), 0, array('content' => $sContent, 'ip' => $sIp, 'result' => &$ret));

        if ($bRet && 'on' == $this->_oConfig->getAntispamOption('antispam_report')) {

            $iProfileId = getLoggedId();
            $aPlus = array(
                'SpammerUrl' => getProfileLink($iProfileId),
                'SpammerNickName' => getNickName($iProfileId),
                'Page' => htmlspecialchars_adv($_SERVER['PHP_SELF']),
                'Get' => print_r($_GET, true),
                'SpamContent' => htmlspecialchars_adv($sContent),
            );

            bx_import('BxDolEmailTemplates');
            $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('t_SpamReportAuto', $aPlus);
            if (!$aTemplate)
                trigger_error('Email template or translation missing: t_SpamReportAuto', E_USER_ERROR);

            sendMail(getParam('site_email'), $aTemplate['Subject'], $aTemplate['Body']);
        }

        if ($bRet && 'on' == $this->_oConfig->getAntispamOption('antispam_block'))
            return true;

        return false;
    }


    /**
     * Perform complex check if user is allowed to login.
     * First it checks if IP is directly blocked (@see serviceIsIpBlocked), 
     * then it checks in DNS black lists (@see serviceIsIpDnsBlacklisted).
     *
     * @param $sCurIP IP to check, or empty for current IP
     * @param $sType [optional] place where checking is performed, for example 'join', 'login'
     * @return empty string - if join should be allowed, error message - if join should be blocked
     */
    function serviceCheckLogin ($sIp = '')
    {
        $sErrorMsg = '';
        $sType = 'login';

        if (!$sIp)
            $sIp = getVisitorIP();
            
        if (!$sErrorMsg && $this->serviceIsIpBlocked($sIp))
            $sErrorMsg = $this->getErrorMessageIpBlocked();

        if (!$sErrorMsg && 'on' == $this->_oConfig->getAntispamOption('dnsbl_enable') && 'block' == $this->_oConfig->getAntispamOption('dnsbl_behaviour') && $this->serviceIsIpDnsBlacklisted($sIp, $sType))
            $sErrorMsg = $this->getErrorMessageSpam();
        
        bx_alert('bx_antispam', 'check_login', bx_get_logged_profile_id(), 0, array('ip' => $sIp, 'error_msg' => &$sErrorMsg));

        return $sErrorMsg;
    }

    /**
     * Perform complex check if user is allowed to join.
     * First it checks if IP is directly blocked (@see serviceIsIpBlocked),
     * then it checks in DNS black lists (@see serviceIsIpDnsBlacklisted),
     * then it checks in StopForumSpam service (@see BxAntispamStopForumSpam).
     *
     * TODO: if dnsbl_behaviour == approval, inform caller to set account to approval status on positive detection
     *
     * @param $sCurIP IP to check, or empty for current IP
     * @param $sType [optional] place where checking is performed, for example 'join', 'login'
     * @return empty string - if join should be allowed, error message - if join should be blocked
     */
    function serviceCheckJoin ($sEmail, $sIp = '') 
    {
        $sErrorMsg = '';
        $sType = 'join';

        if (!$sIp)
            $sIp = getVisitorIP();

        if (!$sErrorMsg && $this->serviceIsIpBlocked($sIp))
            $sErrorMsg = $this->getErrorMessageIpBlocked();

        if (!$sErrorMsg && 'on' == $this->_oConfig->getAntispamOption('dnsbl_enable') && 'block' == $this->_oConfig->getAntispamOption('dnsbl_behaviour') && $this->serviceIsIpDnsBlacklisted($sIp, $sType))
            $sErrorMsg = $this->getErrorMessageSpam();

        
        if (!$sErrorMsg) {
            $oStopForumSpam = bx_instance('BxAntispamStopForumSpam', array(), $this->_aModule);
            if ($oStopForumSpam->isSpammer(array('email' => $sEmail, 'ip' => $sIp), $sType))
                $sErrorMsg = $this->getErrorMessageSpam();
        }

        bx_alert('bx_antispam', 'check_join', bx_get_logged_profile_id(), 0, array('email' => $sEmail, 'ip' => $sIp, 'error_msg' => &$sErrorMsg));

        return $sErrorMsg;
    }

    /**
     * Check if IP is blacklisted in some DNS chain (@see BxAntispamDNSBlacklists).
     *
     * @param $sCurIP IP to check, or empty for current IP
     * @param $sType [optional] place where checking is performed, for example 'join', 'login'
     * @return true if IP blacklisted and not whiteloisted, or false if under cron execution or if IP isn't blacklisted
     */
    function serviceIsIpDnsBlacklisted($sCurIP = '', $sType = '')
    {
        if (defined('BX_DOL_CRON_EXECUTE'))
            return false;

        if (!$sCurIP)
            $sCurIP = getVisitorIP();

        if ($this->serviceIsIpWhitelisted($sCurIP))
            return false;

        $o = bx_instance('BxAntispamDNSBlacklists', array(), $this->_aModule);

        if (BX_DOL_DNSBL_POSITIVE == $o->dnsbl_lookup_ip(BX_DOL_DNSBL_CHAIN_SPAMMERS, $sCurIP) && BX_DOL_DNSBL_POSITIVE != $o->dnsbl_lookup_ip(BX_DOL_DNSBL_CHAIN_WHITELIST, $sCurIP)) {
            $o->onPositiveDetection ($sCurIP, $sType);
            return true;
        }

        return false;
    }

    /**
     * BxAntispamIP::isIpWhitelisted
     */
    function serviceIsIpWhitelisted($sIp = '')
    {
        $o = bx_instance('BxAntispamIP', array(), $this->_aModule);
        return $o->isIpWhitelisted($sIp);
    }

    /**
     * @see BxAntispamIP::isIpBlocked
     */
    function serviceIsIpBlocked($sIp = '')
    {
        $o = bx_instance('BxAntispamIP', array(), $this->_aModule);
        return $o->isIpBlocked($sIp);
    }

    /**
     * @see BxAntispamIP::blockIp
     */
    function serviceBlockIp($mixedIP, $iExpirationInSec = 86400, $sComment = '')
    {
        $o = bx_instance('BxAntispamIP', array(), $this->_aModule);
        return $o->blockIp($mixedIP, $iExpirationInSec, $sComment);
    }

    protected function getErrorMessageIpBlocked () 
    {
        bx_import('BxDolLanguages');
        return _t('_bx_antispam_ip_blocked', $this->getErrorMessageSubmitFalsePositiveReport());
    }

    protected function getErrorMessageSpam ()
    {
        bx_import('BxDolLanguages');
        return _t('_bx_antispam_spam_detected', $this->getErrorMessageSubmitFalsePositiveReport());
    }

    protected function getErrorMessageSubmitFalsePositiveReport ()
    {
        if (BxDolRequest::serviceExists('bx_contact', 'get_contact_page_url') && ($sUrl = BxDolService::call('bx_contact', 'get_contact_page_url')))
            return _t('_bx_antispam_submit_false_positive_report', $sUrl);
        return '';
    }

    protected function _grid ($sObjectGrid) 
    {
        bx_import('BxDolGrid');
        $oGrid = BxDolGrid::getObjectInstance($sObjectGrid);
        return $oGrid ? $oGrid->getCode() : '';
    }
}

/** @} */
