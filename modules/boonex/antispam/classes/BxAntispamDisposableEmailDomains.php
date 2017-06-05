<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Antispam Antispam
 * @ingroup     UnaModules
 *
 * @{
 */


/**
 *  Check for disposable email domain
 */
class BxAntispamDisposableEmailDomains extends BxDol
{
    protected $oDb;

    public function __construct()
    {
        parent::__construct();
        $this->oDb = BxDolDb::getInstance();
    }

    public function isWhitelisted($sEmail)
    {
        return $this->isEmailInList($sEmail, 'custom_whitelist');
    }

    public function isBlacklisted($sEmail)
    {
        return !$this->isEmailInList($sEmail, 'whitelist') && ($this->isEmailInList($sEmail, 'blacklist') || $this->isEmailInList($sEmail, 'custom_blacklist'));
    }

    public function isEmailInList($sEmail, $sList)
    {
        $a = explode('@', $sEmail);
        if (!isset($a[1]))
            return false;
        $sEmailDomain = $a[1];
        return $this->oDb->getOne("SELECT `id` FROM `bx_antispam_disposable_email_domains` WHERE `list` = ? AND `domain` = ?", array($sList, $sEmailDomain));
    }

    public function updateList ($sList, $sUrl)
    {
        if (!($aDomains = file($sUrl, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)))
            return false;

        $this->oDb->query("DELETE FROM `bx_antispam_disposable_email_domains` WHERE `list` = ?", array($sList));
        foreach ($aDomains as $sDomain)
            $this->oDb->query("INSERT INTO `bx_antispam_disposable_email_domains` SET `list` = ?, `domain` = ?", array($sList, $sDomain));

        return true;
    }

    public function getJoinBehaviourValues ()
    {
        return array (
            'block' => _t('_bx_antispam_disposable_email_domains_behaviour_join_block'),
            'approval' => _t('_bx_antispam_disposable_email_domains_behaviour_join_approval'),
        );
    }

    public function getJoinBehaviourModes ()
    {
        return array (
            'disable' => _t('_bx_antispam_disposable_email_domains_mode_disable'),
            'blacklist' => _t('_bx_antispam_disposable_email_domains_mode_blacklist'),
            // TODO: uncomment after adding interface for whitelisting
            // 'whitelist' => _t('_bx_antispam_disposable_email_domains_mode_whitelist'),             
        );
    }

    public function getErrorMessageBlacklisted ()
    {
        return _t('_bx_antispam_disposable_email_domains_msg_blacklisted');
    }

    public function getErrorMessageNotWhitelisted ()
    {
        return $this->getErrorMessageBlacklisted ();
    }    
}

/** @} */
