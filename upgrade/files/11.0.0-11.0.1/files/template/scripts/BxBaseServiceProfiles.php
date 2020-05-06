<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
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

    public function serviceGetProfileTypes()
    {
        $aTypes = array(
			'' => _t('_Select_one')
        );

        $aModules = $this->_getProfilesModules();
        foreach($aModules as $aModule) {
			if(empty($aModule) || !is_array($aModule))
				continue;

            $sTitleKey = '_' . $aModule['name'];
            $sTitle = _t($sTitleKey);

            $aTypes[$aModule['name']] = !empty($sTitle) && $sTitle != $sTitleKey ? $sTitle : $aModule['title'];
		}

        return $aTypes;
    }

    public function serviceProfileStats ($iProfileId = 0)
    {
        if (!$iProfileId && !($iProfileId = bx_get_logged_profile_id()))
            return '';

        $oProfile = BxDolProfile::getInstance($iProfileId);
        if(!$oProfile)
            return '';

        $oAcl = BxDolAcl::getInstance();
        $aAcl = $oAcl->getMemberMembershipInfo($iProfileId);
        $aAclInfo = $oAcl->getMembershipInfo($aAcl['id']);

        $aVars = array(
            'profile_id' => $oProfile->id(),
            'profile_url' => $oProfile->getUrl(),
            'profile_edit_url' => $oProfile->getEditUrl(),
            'profile_title' => $oProfile->getDisplayName(),
            'profile_title_attr' => bx_html_attribute($oProfile->getDisplayName()),
            'profile_ava_url' => $oProfile->getAvatar(),
            'profile_unit' => $oProfile->getUnit(0, array('template' => array(
                'name' => 'unit_wo_info',
                'size' => 'ava'
            ))),
            'profile_acl_title' => _t($aAclInfo['name']),
            'profile_acl_icon' => $aAclInfo['icon'],
            'menu' => BxDolMenu::getObjectInstance('sys_profile_stats')->getCode(),
        );

        return BxDolTemplate::getInstance()->parseHtmlByName('profile_stats.html', $aVars);
    }

    public function serviceGetMenuAddonProfileEdit($iProfileId = 0, $sCaption = '')
    {
        /**
         * Disabled.
         */
        return '';

    	if(empty($sCaption))
            $sCaption = _t('_Edit');

        $oProfile = BxDolProfile::getInstance($iProfileId);
        if(!$oProfile)
            return '';

        $sModule = $oProfile->getModule();
        $sMethod = 'profile_edit_url';
        if(!BxDolRequest::serviceExists($sModule, $sMethod))
            return '';

        return BxDolTemplate::getInstance()->parseLink(BxDolService::call($sModule, $sMethod, array($oProfile->getContentId())), $sCaption);
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-profiles Profiles
     * @subsubsection bx_system_general-profile_membership profile_membership
     * 
     * @code bx_srv('system', 'profile_membership', [2], 'TemplServiceProfiles'); @endcode
     * @code {{~system:profile_membership:TemplServiceProfiles[2]~}} @endcode
     * 
     * Get membership level for specified profile.
     * @param $iProfileId profile ID
     * 
     * @see BxBaseServiceProfiles::serviceProfileMembership
     */
    /** 
     * @ref bx_system_general-profile_membership "profile_membership"
     */
    public function serviceProfileMembership ($iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

		return BxDolAcl::getInstance()->getProfileMembership($iProfileId);
    }

    public function serviceProfileMembershipStats ($iProfileId = 0)
    {
        if(!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        $sTxtUnlimited = _t('_unlimited');
        $sTxtFiles = _t('_sys_storage_files');
        $aQuota = BxDolProfileQuery::getInstance()->getProfileQuota($iProfileId);

        $aTmplVarsStats = array();
        if(!empty($aQuota['quota_size'])) {
            $iWidth = (int)round(100 * $aQuota['current_size']/$aQuota['quota_size']);
            $sPercent = $iWidth . '%';
        }
        else {
            $iWidth = 0;
            $sPercent = $sTxtUnlimited;
        }
        $aTmplVarsStats[] = array('title' => _t('_sys_profile_storage_quota_size'), 'width' => $iWidth, 'value' => _t_format_size($aQuota['current_size']), 'percent' => $sPercent);

        if(!empty($aQuota['quota_number'])) {
            $iWidth = (int)round(100 * $aQuota['current_number']/$aQuota['quota_number']);
            $sPercent = $iWidth . '%';
        }
        else {
            $iWidth = 0;
            $sPercent = $sTxtUnlimited;
        } 
        $aTmplVarsStats[] = array('title' => _t('_sys_profile_storage_quota_number'), 'width' => $iWidth, 'value' => $aQuota['current_number'] . ' ' . $sTxtFiles, 'percent' => $sPercent);

        return BxDolTemplate::getInstance()->parseHtmlByName('profile_membership_stats.html', array(
            'membership' => BxDolAcl::getInstance()->getProfileMembership($iProfileId),
            'bx_repeat:stat_items' => $aTmplVarsStats
        ));
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-profiles Profiles
     * @subsubsection bx_system_general-profile_notifications profile_notifications
     * 
     * @code bx_srv('system', 'profile_notifications', [], 'TemplServiceProfiles'); @endcode
     * @code {{~system:profile_notifications:TemplServiceProfiles~}} @endcode
     * 
     * Get number of unread notifications for logged in profile
     * 
     * @see BxBaseServiceProfiles::serviceProfileNotifications
     */
    /** 
     * @ref bx_system_general-profile_notifications "profile_notifications"
     */
    public function serviceProfileNotifications ($iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        $oMenu = BxDolMenu::getObjectInstance('sys_account_notifications');
        if(!$oMenu)
            return 0;

        $iNum = 0;
        $aMenuItems = $oMenu->getMenuItems ();
        foreach ($aMenuItems as $r) {
            if (isset($r['bx_if:addon']) && $r['bx_if:addon']['condition'])
                $iNum += $r['bx_if:addon']['content']['addon'];
        }

        return $iNum;
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-profiles Profiles
     * @subsubsection bx_system_general-get_count_online_profiles get_count_online_profiles
     * 
     * @code bx_srv('system', 'get_count_online_profiles', [], 'TemplServiceProfiles'); @endcode
     * @code {{~system:get_count_online_profiles:TemplServiceProfiles~}} @endcode
     * 
     * Get number of online profiles
     * 
     * @see BxBaseServiceProfiles::serviceGetCountOnlineProfiles
     */
    /** 
     * @ref bx_system_general-get_count_online_profiles "get_count_online_profiles"
     */
    public function serviceGetCountOnlineProfiles ()
    {
        $oProfilesQuery = BxDolProfileQuery::getInstance();
        return $oProfilesQuery->getOnlineCount();
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

    public function serviceProfilesFriends ($iLimit = 20)
    {
        if (!($iProfileId = bx_get_logged_profile_id()))
            return array();
        
        $oConnection = BxDolConnection::getObjectInstance('sys_profiles_friends');
        if (!$oConnection)
            return array();

        if (!($a = $oConnection->getConnectedContent ($iProfileId, true, 0, $iLimit)))
            return array();

        $aRet = array();
        foreach ($a as $iId) {
            $oProfile = BxDolProfile::getInstance($iId);

            $aRet[] = array (
            	'label' => $oProfile->getDisplayName(), 
                'value' => $iId, 
                'url' => $oProfile->getUrl(),
            	'thumb' => $oProfile->getThumb(),
                'unit' => $oProfile->getUnit(0, array('template' => 'unit_wo_info'))
            );
        }
        return $aRet;
    }

    public function serviceProfilesSearch ($sTerm, $iLimit = 20)
    {
        // display friends by default
        if (!$sTerm)
            return $this->serviceProfilesFriends($iLimit);

        // get list of "profiles" modules
        $aModules = $this->serviceGetProfilesModules();

        // search in each module
        $a = array();
        foreach ($aModules as $aModule) {
            if (!BxDolService::call($aModule['name'], 'act_as_profile'))
                continue;
            $a = array_merge($a, BxDolService::call($aModule['name'], 'profiles_search', array($sTerm, getParam('sys_per_page_search_keyword_single'))));
        }

        // sort result
        usort($a, function($r1, $r2) {
            return strcmp($r1['label'], $r2['label']);
        });

        bx_alert('system', 'profiles_search', 0, 0, array(
           'term' => $sTerm,
           'result' => &$a
        ));
        
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

    public function serviceAccountProfileSwitcher ($iAccountId = false, $iActiveProfileId = null, $sUrlProfileAction = '', $bShowAll = 0, $sButtonTitle = '', $sProfileTemplate = '')
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
            if (!$bShowAll && $iActiveProfileId == $aProfile['id'])
                continue;

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
                    'content' => array(
                        'id' => $aProfile['id'], 
                        'button_title' => $sButtonTitle ? $sButtonTitle : _t('_sys_txt_switch_profile_context'),
                        'url_switch' => $sUrlProfileAction ? str_replace('{profile_id}', $aProfile['id'], $sUrlProfileAction) : BxDolPermalinks::getInstance()->permalink('page.php?i=account-profile-switcher', array('switch_to_profile' => $aProfile['id'], 'redirect_back' => 1))
                    ),
                ),
                'unit' => BxDolService::call($aProfile['type'], 'profile_unit', array($aProfile['content_id'], array('template' => $sProfileTemplate))),
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
