<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * System service for profiles handling functionality.
 */
class BxBaseServiceProfiles extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceProfileStats ($iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        $oMenu = BxDolMenu::getObjectInstance('sys_profile_stats');

        $oProfile = BxDolProfile::getInstance($iProfileId);

        $aVars = array(
            'profile_id' => $oProfile->id(),
            'profile_url' => $oProfile->getUrl(),
            'profile_edit_url' => $oProfile->getEditUrl(),
            'profile_title' => $oProfile->getDisplayName(),
            'profile_title_attr' => bx_html_attribute($oProfile->getDisplayName()),
            'profile_ava_url' => $oProfile->getAvatar(),
            'menu' => $oMenu->getCode(),
        );

        $oTemplate = BxDolTemplate::getInstance();
        return $oTemplate->parseHtmlByName('profile_stats.html', $aVars);
    }

    public function serviceProfileMembership ($iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

		return BxDolAcl::getInstance()->getProfileMembership($iProfileId);
    }

    public function serviceProfileNotifications ($iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        $oMenu = BxDolMenu::getObjectInstance('sys_account_notifications');

        $iNum = 0;
        $aMenuItems = $oMenu->getMenuItems ();
        foreach ($aMenuItems as $r) {
            if (isset($r['bx_if:addon']) && $r['bx_if:addon']['condition'])
                $iNum += $r['bx_if:addon']['content']['addon'];
        }

        return $iNum;
    }

    public function serviceGetProfilesModules ()
    {
        if (getParam('sys_db_cache_enable')) { // get list of profiles  modules from db cache, cache is invalidated when new module is installed

            $oDb = BxDolDb::getInstance();

            $oCache = $oDb->getDbCacheObject ();

            $sKey = $oDb->genDbCacheKey('profiles_modules_array');
            $sKeyTs = $oDb->genDbCacheKey('profiles_modules_ts');

            $mixedRetTs = $oCache->getData($sKeyTs);
            $mixedRet = $oCache->getData($sKey);

            $iNewestModuleTs = $this->_getLatestModuleTimestamp ();

            if ($mixedRetTs != null && $mixedRet !== null && $mixedRetTs == $iNewestModuleTs) {

                $aModulesArray = $mixedRet;

            } else {

                $aModulesArray = $this->_getProfilesModules ();

                $oCache->setData($sKey, $aModulesArray);
                $oCache->setData($sKeyTs, $iNewestModuleTs);
            }

        } else {

            $aModulesArray = $this->_getProfilesModules ();

        }

        return $aModulesArray;
    }

    public function serviceProfilesSearch ($sTerm, $iLimit = 20)
    {
        // get list of "profiles" modules
        $aModules = $this->serviceGetProfilesModules();

        // search in each module
        $a = array();
        foreach ($aModules as $aModule) {
            if (!BxDolService::call($aModule['name'], 'act_as_profile'))
                continue;
            $a = array_merge($a, BxDolService::call($aModule['name'], 'profiles_search', array($sTerm, 10)));
        }

        // sort result
        usort($a, function($r1, $r2) {
            return strcmp($r1['label'], $r2['label']);
        });

        // return as array
        return array_slice($a, 0, $iLimit);
    }

    public function serviceProfilesList ($iAccountId = 0)
    {
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

    public function serviceAccountProfileSwitcher ($iAccountId = false, $iActiveProfileId = null, $sUrlProfileAction = '')
    {
    	$oTemplate = BxDolTemplate::getInstance();
    	BxDolInformer::getInstance($oTemplate)->setEnabled(false);

        $oProfilesQuery = BxDolProfileQuery::getInstance();

        $aProfiles = $oProfilesQuery->getProfilesByAccount($iAccountId ? $iAccountId : getLoggedId());
        if (!$aProfiles)
            return false;

        if (null === $iActiveProfileId)
            $iActiveProfileId = bx_get_logged_profile_id();

		$oModuleDb = BxDolModuleQuery::getInstance();

        $aVars = array (
            'bx_repeat:row' => array(),
        );
        foreach ($aProfiles as $aProfile) {
        	if(!$oModuleDb->isEnabledByName($aProfile['type']))
        		continue;

            if (!BxDolService::call($aProfile['type'], 'act_as_profile'))
                continue;
            $aVars['bx_repeat:row'][] = array (
                'class' => $iActiveProfileId == $aProfile['id'] ? '' : 'bx-def-color-bg-box',
                'bx_if:active' => array (
                    'condition' => $iActiveProfileId == $aProfile['id'],
                    'content' => array('id' => $aProfile['id']),
                ),
                'bx_if:inactive' => array (
                    'condition' => $iActiveProfileId != $aProfile['id'],
                    'content' => array('id' => $aProfile['id'], 'url_switch' => $sUrlProfileAction ? str_replace('{profile_id}', $aProfile['id'], $sUrlProfileAction) : BxDolPermalinks::getInstance()->permalink('page.php?i=account-profile-switcher', array('switch_to_profile' => $aProfile['id']))),
                ),
                'unit' => BxDolService::call($aProfile['type'], 'profile_unit', array($aProfile['content_id'])),
            );
        }

        $oTemplate->addCss('account.css');
        return array(
            'content' => $oTemplate->parseHtmlByName('profile_switch_row.html', $aVars),
        );
    }

    protected function _getLatestModuleTimestamp ()
    {
        $aModules = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'modules', 'active' => 1, 'order_by' => '`date` ASC'));
        if (empty($aModules))
            return 0;
        $aModuleNewest = array_pop($aModules);
        return $aModuleNewest['date'];
    }

    protected function _getProfilesModules ()
    {
        $aRet = array();
        $aModules = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'modules', 'active' => 1));
        foreach ($aModules as $aModule) {
            $oModule = BxDolModule::getInstance($aModule['name']);
            if ($oModule instanceof iBxDolProfileService && $oModule->serviceActAsProfile())
                $aRet[] = $aModule;
        }
        return $aRet;
    }

}

/** @} */
