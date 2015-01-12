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

define('BX_DOL_DNSBL_NEGATIVE', 0);   // negative
define('BX_DOL_DNSBL_POSITIVE', 1);   // positive match
define('BX_DOL_DNSBL_FAILURE', 2);    // generic failure, not enabled or configured

// Types of queries for dnsbl_lookup_ip() and dnsbl_lookup_domain()
define('BX_DOL_DNSBL_ANYPOSTV_RETFIRST', 0);   // Any positive from chain, stop and return first
define('BX_DOL_DNSBL_ANYPOSTV_RETEVERY', 1);   // Any positive, check all and return every positive
define('BX_DOL_DNSBL_ALLPOSTV_RETEVERY', 2);   // All must check positive, return every positive

define('BX_DOL_DNSBL_MATCH_ANY', "any");

define('BX_DOL_DNSBL_CHAIN_SPAMMERS', "spammers");
define('BX_DOL_DNSBL_CHAIN_WHITELIST', "whitelist");
define('BX_DOL_DNSBL_CHAIN_URIDNS', "uridns");

/**
 *  Spam detection based on spammer IP
 *
 *
 * Example of usage:
 *
 *  if (DNSBL_POSITIVE == $o->dnsbl_lookup_ip(DNSBL_CHAIN_SPAMMERS, $sCurIP) && DNSBL_POSITIVE != $o->dnsbl_lookup_ip(DNSBL_CHAIN_WHITELIST, $sCurIP))
 *  {
 *    // positive detection - block this ip
 *  }
 *  // continue script execution
 *
 *
 *  There is more handy function available:
 *  @see bx_is_ip_dns_blacklisted
 */
class BxAntispamDNSBlacklists extends BxDol
{
    private $aChains = array ();

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->initChains();
    }

    public function dnsbl_lookup_ip($mixedChain, $sIp, $querymode = BX_DOL_DNSBL_ANYPOSTV_RETFIRST)
    {
        $lookupkey = $this->ipreverse($sIp);
        if (false === $lookupkey)
            return BX_DOL_DNSBL_FAILURE;	// unable to prepare lookup string from address

        if (is_array($mixedChain))
            $aChain = $mixedChain;
        else
            $aChain = &$this->aChains[$mixedChain];
        return $this->dnsbl_lookup($aChain, $lookupkey, $querymode);
    }

    public function dnsbl_lookup_uri($sUri, $mixedChain = BX_DOL_DNSBL_CHAIN_URIDNS, $querymode = BX_DOL_DNSBL_ANYPOSTV_RETFIRST)
    {
        if (!$sUri)
            return BX_DOL_DNSBL_FAILURE;

        if (is_array($mixedChain))
            $aChain = $mixedChain;
        else
            $aChain = &$this->aChains[$mixedChain];
        return $this->dnsbl_lookup($aChain, $sUri, $querymode);
    }

    public function onPositiveDetection ($sIP, $sNote = '', $sType = 'dnsbl')
    {
        $iIP = sprintf("%u", ip2long($sIP));
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("INSERT INTO `bx_antispam_block_log` SET `ip` = ?, `profile_id` = ?, `type` = ?, `extra` = ?, `added` = ?", $iIP, bx_get_logged_profile_id(), $sType, $sNote, time());
        return $oDb->query($sQuery);
    }

    public function getRules ($aChains)
    {
        bx_import('BxDolLanguages');
        $oDb = BxDolDb::getInstance();
        $sChains = $oDb->implode_escape($aChains);
        $a = $oDb->getAll("SELECT * FROM `bx_antispam_dnsbl_rules` WHERE `chain` IN($sChains) AND `active` = 1 ORDER BY `chain`, `added`");
        foreach ($a as $k => $r) {
            $a[$k]['chain_title'] = _t('_bx_antispam_chain_' . $a[$k]['chain']);
        }
        return $a;
    }

    public function getRule ($iId)
    {
        $oDb = BxDolDb::getInstance();
        return $oDb->getAll("SELECT `zonedomain`, `postvresp` FROM `bx_antispam_dnsbl_rules` WHERE `id` = '" . (int)$iId . "' AND `active` = 1");
    }

    public function clearCache ()
    {
        $oDb = BxDolDb::getInstance();
        $oDb->cleanCache('sys_dnsbl_'.BX_DOL_DNSBL_CHAIN_SPAMMERS);
        $oDb->cleanCache('sys_dnsbl_'.BX_DOL_DNSBL_CHAIN_WHITELIST);
    }

    public function getDNSBLConfigValues ()
    {
        return array (
            'block' => _t('_bx_antispam_dnsbl_behaviour_login_block'),
            'log' => _t('_bx_antispam_dnsbl_behaviour_login_log'),
        );
    }

    /*************** private function ***************/

    private function dnsbl_lookup(&$zones, $key, $querymode)
    {
        $numpositive = 0;
        $numservers = count ($zones);
        $servers = $zones;

        if (!$servers)
            return BX_DOL_DNSBL_FAILURE; // no servers defined

        if (($querymode!=BX_DOL_DNSBL_ANYPOSTV_RETFIRST) && ($querymode!=BX_DOL_DNSBL_ANYPOSTV_RETEVERY)
             && ($querymode!=BX_DOL_DNSBL_ALLPOSTV_RETEVERY))
             return BX_DOL_DNSBL_FAILURE;	// invalid querymode

        foreach ($servers as $r) {
            $resultaddr = gethostbyname ($key . "." . $r['zonedomain']);

            if ($resultaddr && $resultaddr != $key . "." . $r['zonedomain']) {
                // we got some result from the DNS query, not NXDOMAIN. should we consider 'positive'?
                $postvresp = $r['postvresp'];	// check positive match criteria
                if (
                    BX_DOL_DNSBL_MATCH_ANY == $postvresp ||
                    (preg_match("/^\d+\.\d+\.\d+\.\d+$/", $postvresp) && $resultaddr == $postvresp) ||
                    (is_numeric($postvresp) && (ip2long($resultaddr) & $postvresp))
                ) {
                    $numpositive++;
                    if ($querymode == BX_DOL_DNSBL_ANYPOSTV_RETFIRST)
                        return BX_DOL_DNSBL_POSITIVE;	// found one positive, returning single
                }
            }
        }
        // all servers were queried
        if ($numpositive == $numservers)
            return BX_DOL_DNSBL_POSITIVE;
        else if (($querymode == BX_DOL_DNSBL_ANYPOSTV_RETEVERY) && ($numpositive > 0))
            return BX_DOL_DNSBL_POSITIVE;
        else
            return BX_DOL_DNSBL_NEGATIVE;
    }

    private function ipreverse ($sIp)
    {
        if (!preg_match ('/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/', $sIp, $m))
            return false;

        return "{$m[4]}.{$m[3]}.{$m[2]}.{$m[1]}";
    }

    private function initChains()
    {
        $oDb = BxDolDb::getInstance();

        if (!isset($GLOBALS['bx_dol_dnsbl_'.BX_DOL_DNSBL_CHAIN_SPAMMERS])) {
            $sQuery = $oDb->prepare("SELECT `zonedomain`, `postvresp` FROM `bx_antispam_dnsbl_rules` WHERE `chain` = ? AND `active` = 1", BX_DOL_DNSBL_CHAIN_SPAMMERS);
            $GLOBALS['bx_dol_dnsbl_'.BX_DOL_DNSBL_CHAIN_SPAMMERS] = $oDb->fromCache('sys_dnsbl_'.BX_DOL_DNSBL_CHAIN_SPAMMERS, 'getAll', $sQuery);
        }

        if (!isset($GLOBALS['bx_dol_dnsbl_'.BX_DOL_DNSBL_CHAIN_WHITELIST]))
            $GLOBALS['bx_dol_dnsbl_'.BX_DOL_DNSBL_CHAIN_WHITELIST] = $oDb->fromCache('sys_dnsbl_'.BX_DOL_DNSBL_CHAIN_WHITELIST, 'getAll', "SELECT `zonedomain`, `postvresp` FROM `bx_antispam_dnsbl_rules` WHERE `chain` = '".BX_DOL_DNSBL_CHAIN_WHITELIST."' AND `active` = 1");

        if (!isset($GLOBALS['bx_dol_dnsbl_'.BX_DOL_DNSBL_CHAIN_URIDNS]))
            $GLOBALS['bx_dol_dnsbl_'.BX_DOL_DNSBL_CHAIN_URIDNS] = $oDb->fromCache('sys_dnsbl_'.BX_DOL_DNSBL_CHAIN_URIDNS, 'getAll', "SELECT `zonedomain`, `postvresp` FROM `bx_antispam_dnsbl_rules` WHERE `chain` = '".BX_DOL_DNSBL_CHAIN_URIDNS."' AND `active` = 1");

        $this->aChains[BX_DOL_DNSBL_CHAIN_SPAMMERS] = &$GLOBALS['bx_dol_dnsbl_'.BX_DOL_DNSBL_CHAIN_SPAMMERS];
        $this->aChains[BX_DOL_DNSBL_CHAIN_WHITELIST] = &$GLOBALS['bx_dol_dnsbl_'.BX_DOL_DNSBL_CHAIN_WHITELIST];
        $this->aChains[BX_DOL_DNSBL_CHAIN_URIDNS] = &$GLOBALS['bx_dol_dnsbl_'.BX_DOL_DNSBL_CHAIN_URIDNS];

    }

}

/** @} */
