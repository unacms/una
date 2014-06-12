<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Persons Persons
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModProfileInstaller');

class BxPersonsInstaller extends BxBaseModProfileInstaller 
{
    function __construct($aConfig) 
    {
        parent::__construct($aConfig);
        $this->_aTranscoders = array ('bx_persons_icon', 'bx_persons_thumb', 'bx_persons_avatar', 'bx_persons_picture', 'bx_persons_cover', 'bx_persons_cover_thumb');
        $this->_aStorages = array ('bx_persons_pictures');
        $this->_aConnections = array (
            'sys_profiles_friends' => array ('type' => 'profiles'),
            'sys_profiles_subscriptions' => array ('type' => 'profiles'),
        );
    }
}

/** @} */ 
