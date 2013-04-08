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
class BxBaseServices extends BxDol {
    public function __construct() {
        parent::__construct();
    }

    public function serviceProfileUnit ($iContentId) {
        if (!$iContentId)
            return false;
        if (!($oAccount = BxDolAccount::getInstance($iContentId)))
            return false;        
        return $oAccount->getUnit();
    }

    public function serviceProfileThumb ($iContentId) {
        if (!$iContentId)
            return false;
        if (!($oAccount = BxDolAccount::getInstance($iContentId)))
            return false;        
        return $oAccount->getThumb();
    }

    public function serviceProfileName ($iContentId) {
        if (!$iContentId)
            return false;
        if (!($oAccount = BxDolAccount::getInstance($iContentId)))
            return false;        
        return $oAccount->getDisplayName();
    }

    public function serviceProfileUrl ($iContentId) {
        if (!$iContentId)
            return false;
        if (!($oAccount = BxDolAccount::getInstance($iContentId)))
            return false;        
        return $oAccount->getUrl();
    }

}

/** @} */
