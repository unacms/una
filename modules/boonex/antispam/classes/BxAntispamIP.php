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

/**
 * Blocking/whitelisting by IP using local database
 */
class BxAntispamIP extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if IP is directly whitelisted by IP address or by IP address range
     *
     * @param $sCurIP IP to check, or empty for current IP
     * @return true - if IP is whitelisted or under cron execution; false - if IP is not whitelisted, or feature is not enabled
     */
    public function isIpWhitelisted ($sCurIP = '')
    {
        if (defined('BX_DOL_CRON_EXECUTE'))
            return true;

        $iIPGlobalType = (int)getParam('bx_antispam_ip_list_type');
        if ($iIPGlobalType != 1 && $iIPGlobalType != 2) // 0 - disabled
            return false;

        if (!$sCurIP)
            $sCurIP = getVisitorIP();
        $iCurIP = sprintf("%u", ip2long($sCurIP));
        $iCurrTime = time();

        return db_value("SELECT `ID` FROM `sys_ip_list` WHERE `Type` = 'allow' AND `LastDT` > $iCurrTime AND `From` <= '$iCurIP' AND `To` >= '$iCurIP' LIMIT 1") ? true : false;
    }

    /**
     * Check if IP is directly bloked by IP address or by IP address range
     *
     * @param $sCurIP IP to check, or empty for current IP
     * @return true - if IP is blocked; false - if IP is not blocked, or feature is not enabled, or it is run under cron
     */
    public function isIpBlocked ($sCurIP = '')
    {
        if (defined('BX_DOL_CRON_EXECUTE'))
            return false;

        $iIPGlobalType = (int)getParam('bx_antispam_ip_list_type');
        if ($iIPGlobalType != 1 && $iIPGlobalType != 2) // 0 - disabled
            return false;

        if (!$sCurIP)
            $sCurIP = getVisitorIP();
        $iCurIP = sprintf("%u", ip2long($sCurIP));
        $iCurrTime = time();

        if ($this->isIpWhitelisted($sCurIP))
            return false;

        $isBlocked = db_value("SELECT `ID` FROM `sys_ip_list` WHERE `Type` = 'deny' AND `LastDT` > $iCurrTime AND `From` <= '$iCurIP' AND `To` >= '$iCurIP' LIMIT 1");
        if ($isBlocked)
            return true;

        // 1 - all allowed except listed
        // 2 - all blocked except listed
        return $iIPGlobalType == 2 ? true : false;
    }


    /**
     * Add IP to blocklist
     *
     * @param $sIP IP as string or long integer
     * @param $iExpirationInSec set expiration in seconds
     * @param $sComment comment about blocking
     * @return false on error, not false on success
     */
    public function blockIp($mixedIP, $iExpirationInSec = 86400, $sComment = '')
    {
        if (preg_match('/^[0-9]+$/', $mixedIP))
            $iIP = $mixedIP;
        else
            $iIP = sprintf("%u", ip2long($sIP));

        $iExpirationInSec = time() + (int)$iExpirationInSec;

        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT ID FROM `sys_ip_list` WHERE `From` = ? AND `To` = ? LIMIT 1", $iIP, $iIP);
        if (!$oDb->getOne($sQuery)) {
            $sQuery = $oDb->prepare("INSERT INTO `sys_ip_list` SET `From` = ?, `To` = ?, `Type` = 'deny', `LastDT` = ?, `Desc` = ?", $iIP, $iIP, $iExpirationInSec, $sComment);
            return $oDb->res($sQuery);
        }
        return false;
    }
}

/** @} */
