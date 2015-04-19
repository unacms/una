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
 * Spam detection based on email and ip powered by StopForumSpam service - http://www.stopforumspam.com/
 */
class BxAntispamStopForumSpam extends BxDol
{
    protected $_aKeys = array (
        'ip' => 1,
        'email' => 1,
        'username' => 1,
    );

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if user is spammer
     * @param $aValues - array with keys: ip, email, username
     * @param $sDesc - desctiption, for example: join
     * @return true - on positive detection, false - on error or no spammer detection
     */
    public function isSpammer ($aValues, $sDesc)
    {
        if (!getParam('bx_antispam_stopforumspam_enable'))
            return false;

        if (!$aValues || !is_array($aValues))
            return false;

        $aRequestParams = array ('f' => 'json');
        foreach ($this->_aKeys as $k => $b)
            if (isset($aValues[$k]))
                $aRequestParams[$k] = rawurlencode($aValues[$k]);

        $s = bx_file_get_contents('http://www.stopforumspam.com/api', $aRequestParams);
        if (!$s)
            return false;

        $aResult = json_decode($s, true);
        if (null === $aResult || !$aResult['success'])
            return false;

        foreach ($this->_aKeys as $k => $b) {
            if (isset($aResult[$k]) && $aResult[$k]['appears']) {
                $this->onPositiveDetection($sDesc);
                return true;
            }
        }

        return false;
    }

    /**
     * Submit spammer
     * @param @aValues - array with keys: ip, email, username
     * @return false - on error, or true - on success
     */
    public function submitSpammer ($aValues, $sEvidences = false)
    {
        if (!getParam('bx_antispam_stopforumspam_enable'))
            return false;

        $sKey = getParam('bx_antispam_stopforumspam_api_key');
        if (!$sKey)
            return false;

        $sData = 'api_key=' . $sKey . '&evidence=' . ($sEvidences ? rawurlencode($sEvidences) : 'spammer');
        foreach ($this->_aKeys as $k => $b)
            if (isset($aValues[$k]))
                $sData .= '&' . ('ip' == $k ? 'ip_addr' : $k) . '=' . rawurlencode($aValues[$k]);

        $fp = fsockopen("www.stopforumspam.com", 80);
        fputs($fp, "POST /add.php HTTP/1.1\n" );
        fputs($fp, "Host: www.stopforumspam.com\n" );
        fputs($fp, "Content-type: application/x-www-form-urlencoded\n" );
        fputs($fp, "Content-length: " . strlen($sData) . "\n" );
        fputs($fp, "Connection: close\n\n" );
        fputs($fp, $sData);
        fclose($fp);

        return true;
    }

    public function onPositiveDetection ($sExtraData = '')
    {
        $o = bx_instance('BxAntispamDNSBlacklists', array(), 'bx_antispam');
        $o->onPositiveDetection (getVisitorIP(false), $sExtraData, 'stopforumspam');
    }
}

/** @} */
