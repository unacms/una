<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

/**
 * @see BxBaseServiceProfiles
 */
class BxTemplServiceProfiles extends BxBaseServiceProfiles
{
    public function __construct()
    {
        parent::__construct();
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

        list ($sIcon, $sIconUrl, $sIconA, $sIconHtml) = $this->_getIcon($aAclInfo['icon']);

        $aVars = array(
            'profile_id' => $oProfile->id(),
            'profile_url' => $oProfile->getUrl(),
            'profile_edit_url' => $oProfile->getEditUrl(),
            'profile_title' => $oProfile->getDisplayName(),
            'profile_title_attr' => bx_html_attribute($oProfile->getDisplayName()),
            'profile_ava_url' => $oProfile->getAvatar(),
            'profile_unit' => $oProfile->getUnit(0, array('template' => array(
                'name' => 'unit_wo_info',
                'size' => 'thumb'
            ))),
            'profile_acl_title' => _t($aAclInfo['name']),
            'menu' => BxDolMenu::getObjectInstance('sys_profile_stats')->getCode(),
        );
		
        $aVars['bx_if:image'] = array (
            'condition' => (bool)$sIconUrl,
            'content' => array('icon_url' => $sIconUrl),
        );
        $aVars['bx_if:image_inline'] = array (
            'condition' => false,
            'content' => array('image' => ''),
        );
        $aVars['bx_if:icon'] = array (
            'condition' => (bool)$sIcon,
            'content' => array('icon' => $sIcon),
        );
        $aVars['bx_if:icon-html'] = array (
            'condition' => (bool)$sIconHtml,
            'content' => array('icon' => $sIconHtml),
        );
        $aVars['bx_if:icon-a'] = array (
            'condition' => (bool)$sIconA,
            'content' => array('icon-a' => $sIconA),
        );

        return BxDolTemplate::getInstance()->parseHtmlByName('profile_stats.html', $aVars);
    }
}

/** @} */
