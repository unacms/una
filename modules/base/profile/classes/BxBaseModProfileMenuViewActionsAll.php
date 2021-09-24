<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry all actions menu
 */
class BxBaseModProfileMenuViewActionsAll extends BxBaseModGeneralMenuViewActions
{
    protected $_oProfile;
    protected $_aProfileInfo;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        if(empty($this->_iContentId) && bx_get('profile_id') !== false)
            $this->setContentId(BxDolProfile::getInstance(bx_process_input(bx_get('profile_id'), BX_DATA_INT))->getContentId());
    }
    
    public function setContentId($iContentId)
    {
        parent::setContentId($iContentId);

        $this->_oProfile = BxDolProfile::getInstanceByContentAndType($this->_iContentId, $this->_sModule);
        if(!$this->_oProfile) 
            return;

        $this->_aProfileInfo = $this->_oProfile->getInfo();     

        $this->addMarkers($this->_aProfileInfo);
        $this->addMarkers(array(
            'profile_id' => $this->_oProfile->id()
        ));
    }

    protected function _getMenuItemProfileFriendAdd($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemProfileFriendRemove($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemProfileRelationAdd($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemProfileRelationRemove($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemProfileSubscribeAdd($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemProfileSubscribeRemove($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemProfileSetAclLevel($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemView($aItem, $aParams = array())
    {
        return parent::_getMenuItemView($aItem, array_merge($aParams, [
            'object_options' => ['show_counter' => false]
        ]));
    }

    protected function _getMenuItemComment($aItem, $aParams = array())
    {
        return parent::_getMenuItemComment($aItem, array_merge($aParams, [
            'object_options' => ['show_counter' => false]
        ]));
    }

    protected function _getMenuItemVote($aItem, $aParams = array())
    {
        return parent::_getMenuItemVote($aItem, array_merge($aParams, [
            'object_options' => ['show_counter' => false]
        ]));
    }

    protected function _getMenuItemReaction($aItem, $aParams = array())
    {
        return parent::_getMenuItemReaction($aItem, array_merge($aParams, [
            'object_options' => ['show_counter' => false]
        ]));
    }

    protected function _getMenuItemScore($aItem, $aParams = array())
    {
        return parent::_getMenuItemScore($aItem, array_merge($aParams, [
            'object_options' => ['show_counter' => false]
        ]));
    }
}

/** @} */
