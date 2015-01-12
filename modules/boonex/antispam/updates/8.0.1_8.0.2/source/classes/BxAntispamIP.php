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

define('BX_ANTISPAM_IP_TABLE_DISABLED', 0);
define('BX_ANTISPAM_IP_TABLE_ALL_ALLOWED_EXCEPT_LISTED', 1);
define('BX_ANTISPAM_IP_TABLE_ALL_BLOCKED_EXCEPT_LISTED', 2);

/**
 * Blocking/whitelisting by IP using local database
 */
class BxAntispamIP extends BxDol
{
    protected $_aIpTableConfigMap;

    public function __construct()
    {
        parent::__construct();
        $this->_aIpTableConfigMap = array (
            0 => _t('_bx_antispam_ip_table_disabled'),
            1 => _t('_bx_antispam_ip_table_all_allowed_except_listed'),
            2 => _t('_bx_antispam_ip_table_all_blocked_except_listed'),
        );
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
        if (BX_ANTISPAM_IP_TABLE_DISABLED == $iIPGlobalType)
            return false;

        if (!$sCurIP)
            $sCurIP = getVisitorIP();

        return $this->_isIpListed('allow', $sCurIP);
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
        if (BX_ANTISPAM_IP_TABLE_DISABLED == $iIPGlobalType)
            return false;

        if (!$sCurIP)
            $sCurIP = getVisitorIP();

        if ($this->isIpWhitelisted($sCurIP))
            return false;

        if ($this->_isIpListed('deny', $sCurIP))
            return true;

        return BX_ANTISPAM_IP_TABLE_ALL_BLOCKED_EXCEPT_LISTED == $iIPGlobalType ? true : false;
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
            $iIP = sprintf("%u", ip2long($mixedIP));

        $iExpirationInSec = time() + (int)$iExpirationInSec;

        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT ID FROM `bx_antispam_ip_table` WHERE `From` = ? AND `To` = ? LIMIT 1", $iIP, $iIP);
        if (!$oDb->getOne($sQuery)) {
            $sQuery = $oDb->prepare("INSERT INTO `bx_antispam_ip_table` SET `From` = ?, `To` = ?, `Type` = 'deny', `LastDT` = ?, `Desc` = ?", $iIP, $iIP, $iExpirationInSec, $sComment);
            return $oDb->res($sQuery);
        }
        return false;
    }

    /**
     * Get IP table direcitve by ID
     *
     * @param $sId
     * @return array with IP table directive data
     */
    public function getIpTableDirective($iId)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `bx_antispam_ip_table` WHERE `ID` = ? LIMIT 1", $iId);
        return $oDb->getRow($sQuery);
    }

    public function getIpTableConfigValues ()
    {
        return $this->_aIpTableConfigMap;
    }

    public function getIpTableConfigTitle ($i)
    {
        return $this->_aIpTableConfigMap[$i];
    }

    /**
     * Clean up expired entries
     * @return number of affected rows
     */
    public function pruning ()
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("DELETE FROM `bx_antispam_ip_table` WHERE `LastDT` <= ?", time());
        $iAffectedRows = $oDb->query($sQuery);

        if ($iAffectedRows) {
            $oDb->query("OPTIMIZE TABLE `bx_antispam_ip_table`");
            return $iAffectedRows;
        }

        return 0;
    }

    protected function _isIpListed($sType, $sIp)
    {
        $iIp = sprintf("%u", ip2long($sIp));
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT `ID` FROM `bx_antispam_ip_table` WHERE `Type` = ? AND `LastDT` > ? AND `From` <= ? AND `To` >= ? LIMIT 1", $sType, time(), $iIp, $iIp);
        return $oDb->getOne($sQuery) ? true : false;
    }
}

/** @} */
