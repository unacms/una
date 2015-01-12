<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Antispam Antispam
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolModule');

class BxAntispamModule extends BxDolModule
{
    public function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceIpTable ()
    {
        $o = bx_instance('BxAntispamIP', array(), $this->_aModule);
        $s = _t('_bx_antispam_ip_table_status', mb_strtolower($o->getIpTableConfigTitle((int)getParam('bx_antispam_ip_list_type'))));
        $s .= $this->_grid('bx_antispam_grid_ip_table');
        return $s;
    }

    public function serviceDnsblList ()
    {
        bx_import('BxTemplFunctions');
        $s = _t('_bx_antispam_dnsbl_status',
             BxTemplFunctions::getInstance()->statusOnOff((bool)$this->_oConfig->getAntispamOption('dnsbl_enable'), true),
             BxTemplFunctions::getInstance()->statusOnOff((bool)$this->_oConfig->getAntispamOption('uridnsbl_enable'), true),
             mb_strtolower(_t('_bx_antispam_dnsbl_behaviour_login_' . $this->_oConfig->getAntispamOption('dnsbl_behaviour_login'))),
             mb_strtolower(_t('_bx_antispam_dnsbl_behaviour_join_' . $this->_oConfig->getAntispamOption('dnsbl_behaviour_join')))
        );
        $s .= $this->_grid('bx_antispam_grid_dnsbl');
        return $s;
    }

    public function serviceBlockLog ()
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
    public function serviceIsSpam ($sContent, $sIp = '', $isStripSlashes = BX_SLASHES_AUTO)
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

        if ($bRet && 'on' == $this->_oConfig->getAntispamOption('antispam_report')) {

            $oProfile = BxDolProfile::getInstance();
            $aPlus = array(
                'SpammerUrl' => $oProfile->getUrl(),
                'SpammerNickName' => $oProfile->getDisplayName(),
                'Page' => htmlspecialchars_adv($_SERVER['PHP_SELF']),
                'Get' => print_r($_GET, true),
                'Post' => print_r($_POST, true),
                'SpamContent' => htmlspecialchars_adv($sContent),
            );

            bx_import('BxDolEmailTemplates');
            $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('bx_antispam_spam_report', $aPlus);
            if (!$aTemplate)
                trigger_error('Email template or translation missing: bx_antispam_spam_report', E_USER_ERROR);

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
     * @param $sIp IP to check, or empty for current IP
     * @return empty string - if join should be allowed, error message - if join should be blocked
     */
    public function serviceCheckLogin ($sIp = '')
    {
        $bLoginBlock = ('block' == $this->_oConfig->getAntispamOption('dnsbl_behaviour_login'));
        $sErrorMsg = '';
        $sNote = $bLoginBlock ? 'login block' : 'login log';

        if (!$sIp)
            $sIp = getVisitorIP();

        if (!$sErrorMsg && $this->serviceIsIpBlocked($sIp))
            $sErrorMsg = $this->getErrorMessageIpBlocked();

        if (!$sErrorMsg && 'on' == $this->_oConfig->getAntispamOption('dnsbl_enable') && $this->serviceIsIpDnsBlacklisted($sIp, $sNote) && $bLoginBlock)
            $sErrorMsg = $this->getErrorMessageSpam();

        return $sErrorMsg;
    }

    /**
     * Perform complex check if user is allowed to join.
     * First it checks if IP is directly blocked (@see serviceIsIpBlocked),
     * then it checks in DNS black lists (@see serviceIsIpDnsBlacklisted),
     * then it checks in StopForumSpam service (@see BxAntispamStopForumSpam).
     *
     * @param $sCurIP IP to check, or empty for current IP
     * @return empty string - if join should be allowed, error message - if join should be blocked
     */
    public function serviceCheckJoin ($sEmail, &$bApproval, $sIp = '')
    {
        $bJoinBlock = ('block' == $this->_oConfig->getAntispamOption('dnsbl_behaviour_join'));
        $bApproval = false;
        $sErrorMsg = '';
        $sNote = $bJoinBlock ? 'join block' : 'join approval';

        if (!$sIp)
            $sIp = getVisitorIP();

        if (!$sErrorMsg && $this->serviceIsIpBlocked($sIp))
            $sErrorMsg = $this->getErrorMessageIpBlocked();

        if (!$sErrorMsg && 'on' == $this->_oConfig->getAntispamOption('dnsbl_enable') && $this->serviceIsIpDnsBlacklisted($sIp, $sNote)) {
            if ('approval' == $this->_oConfig->getAntispamOption('dnsbl_behaviour_join'))
                $bApproval = true;
            else
                $sErrorMsg = $this->getErrorMessageSpam();
        }

        if (!$sErrorMsg) {
            $oStopForumSpam = bx_instance('BxAntispamStopForumSpam', array(), $this->_aModule);
            if ($oStopForumSpam->isSpammer(array('email' => $sEmail, 'ip' => $sIp), $sNote))
                $sErrorMsg = $this->getErrorMessageSpam();
        }

        return $sErrorMsg;
    }

    /**
     * Check if IP is blacklisted in some DNS chain (@see BxAntispamDNSBlacklists).
     *
     * @param $sCurIP IP to check, or empty for current IP
     * @param $sNote [optional] place where checking is performed, for example 'join', 'login'
     * @return true if IP blacklisted and not whiteloisted, or false if under cron execution or if IP isn't blacklisted
     */
    public function serviceIsIpDnsBlacklisted($sCurIP = '', $sNote = '')
    {
        if (defined('BX_DOL_CRON_EXECUTE'))
            return false;

        if (!$sCurIP)
            $sCurIP = getVisitorIP();

        if ($this->serviceIsIpWhitelisted($sCurIP))
            return false;

        $o = bx_instance('BxAntispamDNSBlacklists', array(), $this->_aModule);

        if (BX_DOL_DNSBL_POSITIVE == $o->dnsbl_lookup_ip(BX_DOL_DNSBL_CHAIN_SPAMMERS, $sCurIP) && BX_DOL_DNSBL_POSITIVE != $o->dnsbl_lookup_ip(BX_DOL_DNSBL_CHAIN_WHITELIST, $sCurIP)) {
            $o->onPositiveDetection ($sCurIP, $sNote);
            return true;
        }

        return false;
    }

    /**
     * @see BxAntispamIP::isIpWhitelisted
     */
    public function serviceIsIpWhitelisted($sIp = '')
    {
        $o = bx_instance('BxAntispamIP', array(), $this->_aModule);
        return $o->isIpWhitelisted($sIp);
    }

    /**
     * @see BxAntispamIP::isIpBlocked
     */
    public function serviceIsIpBlocked($sIp = '')
    {
        $o = bx_instance('BxAntispamIP', array(), $this->_aModule);
        return $o->isIpBlocked($sIp);
    }

    /**
     * @see BxAntispamIP::blockIp
     */
    public function serviceBlockIp($mixedIP, $iExpirationInSec = 86400, $sComment = '')
    {
        $o = bx_instance('BxAntispamIP', array(), $this->_aModule);
        return $o->blockIp($mixedIP, $iExpirationInSec, $sComment);
    }

    /**
     * @see BxAntispamIP::pruning
     */
    public function servicePruning()
    {
        $o = bx_instance('BxAntispamIP', array(), $this->_aModule);
        return $o->pruning();
    }

    /**
     * @see BxAntispamIP::pruning
     */
    public function serviceConfigValues($s)
    {
        switch ($s) {
            case 'ip_table':
                $o = bx_instance('BxAntispamIP', array(), $this->_aModule);
                return $o->getIpTableConfigValues();
            case 'dnsbl_login':
                $o = bx_instance('BxAntispamDNSBlacklists', array(), $this->_aModule);
                return $o->getDNSBLConfigValues();
            case 'dnsbl_join';
                $o = bx_instance('BxAntispamDNSURIBlacklists', array(), $this->_aModule);
                return $o->getURIDNSBLConfigValues();
        }
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
