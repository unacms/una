<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

/**
 * System services.
 */
class BxBaseServices extends BxDol implements iBxDolProfileService {
    public function __construct() {
        parent::__construct();
    }

    public function serviceProfileUnit ($iContentId) {
        return $this->_serviceProfileFunc('getUnit', $iContentId);
    }

    public function serviceProfileAvatar ($iContentId) {
        return $this->_serviceProfileFunc('getAvatar', $iContentId);
    }

    public function serviceProfileEditUrl ($iContentId) {
        bx_import('BxDolPermalinks');
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=account-settings-info');
    }

    public function serviceProfileThumb ($iContentId) {
        return $this->_serviceProfileFunc('getThumb', $iContentId);
    }

    public function serviceProfileIcon ($iContentId) {
        return $this->_serviceProfileFunc('getIcon', $iContentId);
    }

    public function serviceProfileName ($iContentId) {
        return $this->_serviceProfileFunc('getDisplayName', $iContentId);
    }

    public function serviceProfileUrl ($iContentId) {
        return $this->_serviceProfileFunc('getUrl', $iContentId);
    }

    public function _serviceProfileFunc ($sFunc, $iContentId) {
        if (!$iContentId)
            return false;
        bx_import('BxDolAccount');
        if (!($oAccount = BxDolAccount::getInstance($iContentId)))
            return false;        
        return $oAccount->$sFunc();
    }
}

/** @} */
