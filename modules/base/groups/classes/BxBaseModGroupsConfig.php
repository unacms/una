<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Groups Groups
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxBaseModGroupsConfig extends BxBaseModProfileConfig
{
    protected $_bRoles;
    protected $_aRoles;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_aMenuItems2MethodsActions = array (
            'profile-fan-add' => 'checkAllowedFanAdd',
            'profile-fan-remove' => 'checkAllowedFanRemove',
            'profile-subscribe-add' => 'checkAllowedSubscribeAdd',
            'profile-subscribe-remove' => 'checkAllowedSubscribeRemove',
            'profile-actions-more' => 'checkAllowedViewMoreMenu',
            'convos-compose' => 'checkAllowedCompose',
        );

        $this->_bRoles = false;
        $this->_aRoles = false;
    }

    public function isRoles()
    {
        if($this->_aRoles === false)
            $this->_initRoles();

        return $this->_bRoles;
    }

    public function getRoles()
    {
        if($this->_aRoles === false)
            $this->_initRoles();

        return $this->_aRoles;
    }
    
    protected function _initRoles()
    {
        if(empty($this->CNF['OBJECT_PRE_LIST_ROLES'])) 
            return;

        $this->_aRoles = BxDolFormQuery::getDataItems($this->CNF['OBJECT_PRE_LIST_ROLES']);
        $this->_bRoles = !empty($this->_aRoles) && is_array($this->_aRoles);
    }
}

/** @} */
