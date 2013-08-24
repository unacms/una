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
                $s .= BxDolService::call($aProfile['type'], 'profile_unit', array($aProfile['content_id']));

        if (!$s) 
            $s = MsgBox(_t('_sys_txt_empty'));

        return $s . '<div class="bx-clear"></div>';

    }

    public function serviceAccountProfileSwitcher ($iAccountId = false) {
        bx_import('BxDolProfileQuery');
        $oProfilesQuery = BxDolProfileQuery::getInstance();

        $aProfiles = $oProfilesQuery->getProfilesByAccount($iAccountId ? $iAccountId : getLoggedId());
        if (!$aProfiles)
            return false;
        
        $iLoggedPofileId = bx_get_logged_profile_id();
        $aVars = array (
            'bx_repeat:row' => array(),
        );
        foreach ($aProfiles as $aProfile) {
            //if ($aProfile['type'] == 'system')
            //    continue;
            $aVars['bx_repeat:row'][] = array (
                'bx_if:active' => array (
                    'condition' => $iLoggedPofileId == $aProfile['id'],
                    'content' => array('id' => $aProfile['id']), 
                ),
                'bx_if:inactive' => array (
                    'condition' => $iLoggedPofileId != $aProfile['id'],
                    'content' => array('id' => $aProfile['id'], 'url_switch' => BxDolPermalinks::getInstance()->permalink('page.php?i=account-profile-switcher', array('switch_to_profile' => $aProfile['id']))),
                ),
                'unit' => BxDolService::call($aProfile['type'], 'profile_unit', array($aProfile['content_id'])),
            );
        }

        if (!$aVars['bx_repeat:row']) 
            return MsgBox(_t('_sys_txt_empty'));

        bx_import('BxDolTemplate');
        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addCss('account.css');
        return $oTemplate->parseHtmlByName('profile_switch_row.html', $aVars);
    }

}

/** @} */
