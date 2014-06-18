<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Organizations Organizations
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModProfileInstaller');

class BxOrgsInstaller extends BxBaseModProfileInstaller 
{
    function __construct($aConfig) 
    {
        parent::__construct($aConfig);
        $this->_aTranscoders = array ('bx_organizations_icon', 'bx_organizations_thumb', 'bx_organizations_avatar', 'bx_organizations_picture', 'bx_organizations_cover', 'bx_organizations_cover_thumb');
        $this->_aStorages = array ('bx_organizations_pics');
        $this->_aConnections = array (
            'sys_profiles_friends' => array ('type' => 'profiles'),
            'sys_profiles_subscriptions' => array ('type' => 'profiles'),
        );
        $this->_aMenuTriggers = array ('trigger_profile_view_submenu');
    }
}

/** @} */ 
