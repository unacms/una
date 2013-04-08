<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

/**
 * Spam detection based on the message content and logged in user
 */
class BxDolAkismet extends BxDol
{
    var $oAkismet = null;

    /**
     * Constructor
     */
    public function BxDolAkismet($iProfileID = 0)
    {
        parent::BxDol();
        $sKey = getParam('sys_akismet_api_key');
        if ($sKey) {
            require_once (BX_DIRECTORY_PATH_PLUGINS . 'akismet/Akismet.class.php');
            $this->oAkismet = new Akismet(BX_DOL_URL_ROOT, $sKey);
            $aProfile = getProfileInfo($iProfileID);
            if ($aProfile) {
                $this->oAkismet->setCommentAuthor($aProfile['NickName']);
                $this->oAkismet->setCommentAuthorEmail($aProfile['Email']);
                $this->oAkismet->setCommentAuthorURL(getProfileLink($aProfile['ID']));
            }
        }
    }

    public function isSpam ($s, $sPermalink = false) {

        if (!$this->oAkismet)
            return false;

        $this->oAkismet->setCommentContent($s);
        if ($sPermalink)
            $this->oAkismet->setPermalink($sPermalink);

        return $this->oAkismet->isCommentSpam();
    }

    public function onPositiveDetection ($sExtraData = '') {
        $o = bx_instance('BxDolDNSBlacklists');
        $o->onPositiveDetection (getVisitorIP(), $sExtraData, 'akismet');
    }
}

