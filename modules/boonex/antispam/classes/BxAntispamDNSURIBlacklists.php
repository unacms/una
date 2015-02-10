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

/**
 * Spam detection based on URIs in the message
 */
class BxAntispamDNSURIBlacklists extends BxDol
{
    protected $aZonesUrls = array (
        2 => "http://www.surbl.org/tld/two-level-tlds",
        3 => "http://www.surbl.org/tld/three-level-tlds",
    );

    protected $oDb;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->aZonesUrls = array ( // for some reason original urls are restricted to fetch, so copies are created locally
            2 => BX_DIRECTORY_PATH_MODULES . "boonex/antispam/data/two-level-tlds",
            3 => BX_DIRECTORY_PATH_MODULES . "boonex/antispam/data/three-level-tlds",
        );

        $this->oDb = BxDolDb::getInstance();;
        $this->initZones();
    }

    public function isSpam ($s)
    {
        $aURIs = $this->parseUrls ($s);
        if (!$aURIs)
            return false;

        $aURIs = $this->validateUrls ($aURIs);
        if (!$aURIs)
            return false;

        $oModule = BxDolModule::getInstance('bx_antispam');
        $o = bx_instance('BxAntispamDNSBlacklists', array(), $oModule->_aModule);
        foreach ($aURIs as $sURI) {
            if (BX_DOL_DNSBL_POSITIVE == $o->dnsbl_lookup_uri ($sURI))
                return true;
        }

        return false;
    }

    public function parseUrls (&$s)
    {
        $aMatches = array ();
        if (!preg_match_all("!(https?|ftp|gopher|telnet|file|notes|ms-help):[/\\\\]+([\w\d\.-]*)!", $s, $aMatches))
            return false;

        if (!$aMatches || !isset($aMatches[2]) || !$aMatches[2])
            return false;

        $aUrlsUniq = array ();
        foreach ($aMatches[2] as $sUrl) {
            if (isset($aUrlsUniq[$sUrl]))
                continue;
            $aUrlsUniq[$sUrl] = $sUrl;
        }

        return $aUrlsUniq;
    }

    public function validateUrls ($aUrlsUniq)
    {
        $aUrls = array ();
        foreach ($aUrlsUniq as $sUrl) {

            if (0 == strncasecmp('www.', $sUrl, 4))
                $sUrl = substr($sUrl, 4);

            $aZones = explode ('.', $sUrl);
            $iLevels = count($aZones);

            if ($iLevels <= 2) {

                $aUrls[] = $sUrl;

            } elseif (3 == $iLevels) {

                if ($this->isDbZoneMatch (2, $aZones[1] . '.' . $aZones[2]))
                    $aUrls[] = $sUrl;
                else
                    $aUrls[] = $aZones[1] . '.' . $aZones[2];

            } else {

                $iExt = count($aZones) - 1;
                $iDom = $iExt - 1;
                $iSubDom = $iExt - 2;
                $iSubSubDom = $iExt - 3;

                if ($this->isDbZoneMatch (3, $aZones[$iSubDom] . '.' . $aZones[$iDom] . '.' . $aZones[$iExt])) {
                    $aUrls[] = $aZones[$iSubSubDom] . '.' . $aZones[$iSubDom] . '.' . $aZones[$iDom] . '.' . $aZones[$iExt];
                } else {

                    if ($this->isDbZoneMatch (2, $aZones[$iDom] . '.' . $aZones[$iExt]))
                        $aUrls[] = $aZones[$iSubDom] . '.' . $aZones[$iDom] . '.' . $aZones[$iExt];
                    else
                        $aUrls[] = $aZones[$iDom] . '.' . $aZones[$iExt];
                }
            }

        }

        $aUrlsUniq = array ();
        foreach ($aUrls as $sUrl) {
            if (isset($aUrlsUniq[$sUrl]))
                continue;
            $aUrlsUniq[$sUrl] = $sUrl;
        }

        return array_values($aUrlsUniq);
    }

    public function onPositiveDetection ($sExtraData = '')
    {
        $oModule = BxDolModule::getInstance('bx_antispam');
        $o = bx_instance('BxAntispamDNSBlacklists', array(), $oModule->_aModule);

        $o->onPositiveDetection (getVisitorIP(false), $sExtraData, 'dnsbluri');
    }

    public function getURIDNSBLConfigValues ()
    {
        return array (
            'block' => _t('_bx_antispam_dnsbl_behaviour_join_block'),
            'approval' => _t('_bx_antispam_dnsbl_behaviour_join_approval'),
        );
    }

    /*************** private function ***************/

    private function isDbZoneMatch ($iLevel, $sZone)
    {
        $sQuery = $this->oDb->prepare("SELECT `level` FROM `bx_antispam_dnsbluri_zones` WHERE `level` = ? AND `zone` = ? LIMIT 1", $iLevel, $sZone);
        return $this->oDb->getOne($sQuery) ? true : false;
    }

    private function initZones()
    {
        if (0 == $this->oDb->getOne("SELECT COUNT(*) FROM `bx_antispam_dnsbluri_zones`")) {

            $this->oDb->query("TRUNCATE TABLE `bx_antispam_dnsbluri_zones`");

            foreach ($this->aZonesUrls as $iLevel => $sUrl) {
                $f = fopen ($sUrl, 'r');
                if (!$f)
                    return false;
                while (!feof($f)) {
                    $sZone = fgets($f);
                    $sZone = trim($sZone);
                    if ($sZone) {
                        $sQuery = $this->oDb->prepare("INSERT INTO `bx_antispam_dnsbluri_zones` SET `level` = ?, `zone` = ?", $iLevel, $sZone);
                        $this->oDb->query($sQuery);
                    }
                }
                fclose($f);
            }
        }

        return true;
    }

}

/** @} */
