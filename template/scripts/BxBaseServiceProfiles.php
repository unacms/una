<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

/**
 * System service for profiles handling functionality.
 */
class BxBaseServiceProfiles extends BxDol {

    public function __construct() {
        parent::__construct();
    }

    public function serviceProfilesList ($iAccountId = 0) {

        bx_import('BxDolProfileQuery');
        $oProfilesQuery = BxDolProfileQuery::getInstance();

        $aProfiles = $oProfilesQuery->getProfilesByAccount($iAccountId ? $iAccountId : getLoggedId());
        if (!$aProfiles)
            return false;
        
        $s = '';
        foreach ($aProfiles as $aProfile)
            if ($aProfile['type'] != 'system')
            $s .= BxDolService::call($aProfile['type'], 'profile_thumb', array($aProfile['content_id']));

        if (!$s) 
            $s = MsgBox(_t('_sys_txt_empty'));

        return $s . '<div class="bx-clear"></div>';

    }

}

/** @} */
