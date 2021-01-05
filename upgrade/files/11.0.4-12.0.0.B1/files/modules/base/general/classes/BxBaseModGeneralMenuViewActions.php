<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolPrivacy');

/**
 * View entry social actions menu
 */
class BxBaseModGeneralMenuViewActions extends BxTemplMenuCustom
{
    protected $_sModule;
    protected $_oModule;

    protected $_oMenuAction;
    protected $_oMenuActionsMore;
    protected $_oMenuSocialSharing;

    protected $_iContentId;
    protected $_aContentInfo;

    protected $_bDynamicMode;
    protected $_bShowAsButton;
    protected $_bShowTitle;
    protected $_sClassMiSa; // Separate class for Social Actions (View, Vote, Comment, Report, etc) menu items.

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->setContentId(bx_process_input(bx_get('id'), BX_DATA_INT));

        $this->_oMenuActions = null;
        $this->_oMenuActionsMore = null;
        $this->_oMenuSocialSharing = null;

        $this->_bShowAsButton = true;
        $this->_bShowTitle = true;
        $this->_sClassMiSa = 'bx-base-general-ea-sa';
    }

    public function addMarkers($a)
    {
        $bResult = parent::addMarkers($a);
        if($bResult && !empty($this->_oMenuSocialSharing))
            $this->_oMenuSocialSharing->addMarkers($a);

        return $bResult;
    }

    public function setContentId($iContentId)
    {
        $this->_iContentId = (int)$iContentId;

        $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_iContentId);
        if($this->_aContentInfo)
            $this->addMarkers(array('content_id' => (int)$this->_iContentId));
    }

    /**
     * Check if menu items is visible with extended checking linked to "allow*" method of particular module
     * Associated "allow*" method with particular menu item is stored in module config in MENU_ITEM_TO_METHOD array.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible($a)
    {
        return $this->_oModule->isMenuItemVisible($this->_sObject, $a, $this->_aContentInfo);
    }

    protected function _isContentPublic($iContentId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['FIELD_ALLOW_VIEW_TO'])) 
            return true;

        $aContentInfo = $iContentId == $this->_iContentId ? $this->_aContentInfo : $this->_oModule->_oDb->getContentInfoById($iContentId);
        if(!isset($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]))
            return true;

        return in_array($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']], array(BX_DOL_PG_ALL, BX_DOL_PG_MEMBERS));
    }

    protected function _getMenuItemDefault($aItem)
    {
        $aItem['class_wrp'] = 'bx-base-general-entity-action' . (!empty($aItem['class_wrp']) ? ' ' . $aItem['class_wrp'] : '');

        if($this->_bShowAsButton)
            $aItem['class_link'] = 'bx-btn' . (!empty($aItem['class_link']) ? ' ' . $aItem['class_link'] : '');

        if(!$this->_bShowTitle)
            $aItem['bx_if:title']['condition'] = false;

        return parent::_getMenuItemDefault ($aItem);
    }

    protected function _getMenuItemView($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sObject = !empty($aParams['object']) ? $aParams['object'] : '';
        if(empty($sObject) && !empty($CNF['OBJECT_VIEWS']))
            $sObject = $CNF['OBJECT_VIEWS'];

        $iId = !empty($aParams['id']) ? (int)$aParams['id'] : '';
        if(empty($iId))
            $iId = $this->_iContentId;

        $oObject = !empty($sObject) ? BxDolView::getObjectInstance($sObject, $iId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

        $sResult = $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_view_as_button' => $this->_bShowAsButton,
            'show_do_view_label' => $this->_bShowTitle
        ));
        if(empty($sResult))
            return '';

    	return array($sResult, $this->_sClassMiSa);
    }

    protected function _getMenuItemComment($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sObject = !empty($aParams['object']) ? $aParams['object'] : '';
        if(empty($sObject) && !empty($CNF['OBJECT_COMMENTS']))
            $sObject = $CNF['OBJECT_COMMENTS'];

        $iId = !empty($aParams['id']) ? (int)$aParams['id'] : '';
        if(empty($iId))
            $iId = $this->_iContentId;

        $oObject = !empty($sObject) ? BxTemplCmts::getObjectInstance($sObject, $iId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

        $sResult = $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_comment_as_button' => $this->_bShowAsButton,
            'show_do_comment_label' => $this->_bShowTitle,
            'show_counter' => false
        ));
        if(empty($sResult))
            return '';

        return array($sResult, $this->_sClassMiSa);
    }

    protected function _getMenuItemVote($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sObject = !empty($aParams['object']) ? $aParams['object'] : '';
        if(empty($sObject) && !empty($CNF['OBJECT_VOTES']))
            $sObject = $CNF['OBJECT_VOTES'];

        $iId = !empty($aParams['id']) ? (int)$aParams['id'] : '';
        if(empty($iId))
            $iId = $this->_iContentId;

        $oObject = !empty($sObject) ? BxDolVote::getObjectInstance($sObject, $iId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

        $sResult = $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_vote_as_button' => $this->_bShowAsButton,
            'show_do_vote_label' => $this->_bShowTitle
        ));
        if(empty($sResult))
            return '';

    	return array($sResult, $this->_sClassMiSa);
    }

    protected function _getMenuItemReaction($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sObject = !empty($aParams['object']) ? $aParams['object'] : '';
        if(empty($sObject) && !empty($CNF['OBJECT_REACTIONS']))
            $sObject = $CNF['OBJECT_REACTIONS'];

        $iId = !empty($aParams['id']) ? (int)$aParams['id'] : '';
        if(empty($iId))
            $iId = $this->_iContentId;

        $oObject = !empty($sObject) ? BxDolVote::getObjectInstance($sObject, $iId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

        $sResult = $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_vote_as_button' => $this->_bShowAsButton,
            'show_do_vote_label' => $this->_bShowTitle
        ));
        if(empty($sResult))
            return '';

    	return array($sResult, $this->_sClassMiSa);
    }

    protected function _getMenuItemScore($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sObject = !empty($aParams['object']) ? $aParams['object'] : '';
        if(empty($sObject) && !empty($CNF['OBJECT_SCORES']))
            $sObject = $CNF['OBJECT_SCORES'];

        $iId = !empty($aParams['id']) ? (int)$aParams['id'] : '';
        if(empty($iId))
            $iId = $this->_iContentId;

        $oObject = !empty($sObject) ? BxDolScore::getObjectInstance($sObject, $iId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

        $sResult = $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_vote_as_button' => $this->_bShowAsButton,
            'show_do_vote_label' => $this->_bShowTitle
        ));
        if(empty($sResult))
            return '';

    	return array($sResult, $this->_sClassMiSa);
    }

    protected function _getMenuItemFavorite($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sObject = !empty($aParams['object']) ? $aParams['object'] : '';
        if(empty($sObject) && !empty($CNF['OBJECT_FAVORITES']))
            $sObject = $CNF['OBJECT_FAVORITES'];

        $iId = !empty($aParams['id']) ? (int)$aParams['id'] : '';
        if(empty($iId))
            $iId = $this->_iContentId;

        $oObject = !empty($sObject) ? BxDolFavorite::getObjectInstance($sObject, $iId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

        $sResult = $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_favorite_as_button' => $this->_bShowAsButton,
            'show_do_favorite_label' => $this->_bShowTitle
        ));
        if(empty($sResult))
            return '';

    	return array($sResult, $this->_sClassMiSa);
    }

    protected function _getMenuItemFeature($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sObject = !empty($aParams['object']) ? $aParams['object'] : '';
        if(empty($sObject) && !empty($CNF['OBJECT_FEATURED']))
            $sObject = $CNF['OBJECT_FEATURED'];

        $iId = !empty($aParams['id']) ? (int)$aParams['id'] : '';
        if(empty($iId))
            $iId = $this->_iContentId;

        if(!$this->_isContentPublic($iId))
            return '';

        $oObject = !empty($sObject) ? BxDolFeature::getObjectInstance($sObject, $iId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

        $sResult = $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_feature_as_button' => $this->_bShowAsButton,
            'show_do_feature_label' => $this->_bShowTitle
        ));
        if(empty($sResult))
            return '';

    	return array($sResult, $this->_sClassMiSa);
    }

    protected function _getMenuItemRepost($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sAction = !empty($aParams['action']) ? $aParams['action'] : '';
        if(empty($sAction))
            $sAction = 'added';

        $iId = !empty($aParams['id']) ? (int)$aParams['id'] : '';
        if(empty($iId))
            $iId = $this->_iContentId;

        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iId);
        if(!empty($CNF['FIELD_PUBLISHED']) && isset($aContentInfo[$CNF['FIELD_PUBLISHED']]) && (int)$aContentInfo[$CNF['FIELD_PUBLISHED']] > time())
            return '';

        if(!empty($CNF['FIELD_ALLOW_VIEW_TO']) && isset($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]) && !in_array((int)$aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']], array(BX_DOL_PG_ALL, BX_DOL_PG_MEMBERS)))
            return '';

        $sStatus = 'active';
        if(!empty($CNF['FIELD_STATUS']) && isset($aContentInfo[$CNF['FIELD_STATUS']]) && $aContentInfo[$CNF['FIELD_STATUS']] != $sStatus)
            return '';
        if(!empty($CNF['FIELD_STATUS_ADMIN']) && isset($aContentInfo[$CNF['FIELD_STATUS_ADMIN']]) && $aContentInfo[$CNF['FIELD_STATUS_ADMIN']] != $sStatus)
            return '';

        if(!BxDolRequest::serviceExists('bx_timeline', 'get_repost_element_block'))
            return '';

        $sResult = BxDolService::call('bx_timeline', 'get_repost_element_block', array(bx_get_logged_profile_id(), $this->_oModule->_oConfig->getName(), $sAction, $iId, array(
            'show_do_repost_as_button' => $this->_bShowAsButton,
            'show_do_repost_text' => $this->_bShowTitle
        )));
        if(empty($sResult))
            return '';

    	return array($sResult, $this->_sClassMiSa);
    }

    protected function _getMenuItemReport($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sObject = !empty($aParams['object']) ? $aParams['object'] : '';
        if(empty($sObject) && !empty($CNF['OBJECT_REPORTS']))
            $sObject = $CNF['OBJECT_REPORTS'];

        $iId = !empty($aParams['id']) ? (int)$aParams['id'] : '';
        if(empty($iId))
            $iId = $this->_iContentId;

        $oObject = !empty($sObject) ? BxDolReport::getObjectInstance($sObject, $iId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

        $sResult = $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_report_as_button' => $this->_bShowAsButton,
            'show_do_report_label' => $this->_bShowTitle
        ));
        if(empty($sResult))
            return '';

    	return array($sResult, $this->_sClassMiSa);
    }

    protected function _getMenuItemNotes($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sObject = !empty($aParams['object']) ? $aParams['object'] : '';
        if(empty($sObject) && !empty($CNF['OBJECT_NOTES']))
            $sObject = $CNF['OBJECT_NOTES'];

        $iId = !empty($aParams['id']) ? (int)$aParams['id'] : '';
        if(empty($iId))
            $iId = $this->_iContentId;

        if(!$this->_oModule->_isModerator())
            return false;

        $this->addMarkers(array(
            'module' => $this->_oModule->_oConfig->getName(),
            'module_uri' => $this->_oModule->_oConfig->getUri(),
            'content_id' => $iId
        ));

        return true;
    }

    protected function _getMenuItemSocialSharingFacebook($aItem)
    {
        return $this->_getMenuItemByNameSocialSharing($aItem);
    }

    protected function _getMenuItemSocialSharingTwitter($aItem)
    {
        return $this->_getMenuItemByNameSocialSharing($aItem);
    }

    protected function _getMenuItemSocialSharingPinterest($aItem)
    {
        return $this->_getMenuItemByNameSocialSharing($aItem);
    }

    protected function _getMenuItemByNameActions($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($this->_oMenuActions)) {
            $sObjectMenu = !empty($aParams['object_menu']) ? $aParams['object_menu'] : '';
            if(empty($sObjectMenu) && !empty($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY']))
                $sObjectMenu = $CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY'];

            if(empty($sObjectMenu))
                return '';

            $this->_oMenuActions = BxDolMenu::getObjectInstance($sObjectMenu);
            if(!$this->_oMenuActions)
                return '';

            $this->_oMenuActions->setContentId($this->_iContentId);

            $this->addMarkers($this->_oMenuActions->getMarkers());
        }

        $aItem = $this->_oMenuActions->getMenuItem($aItem['name']);
        if(empty($aItem) || !is_array($aItem))
            return false;

        return $this->_getMenuItemDefault($aItem);
    }

    protected function _getMenuItemByNameSocialSharing($aItem, $aParams = array())
    {
        if(empty($this->_oMenuSocialSharing)) {
            $this->_oMenuSocialSharing = BxDolMenu::getObjectInstance('sys_social_sharing');
            if(!$this->_oMenuSocialSharing)
                return false;

            $this->_oMenuSocialSharing->addMarkers($this->_aMarkers);
        }

        $aItem = $this->_oMenuSocialSharing->getMenuItem($aItem['name']);
        if(empty($aItem) || !is_array($aItem))
            return false;

        return $this->_getMenuItemDefault($aItem);
    }

    protected function _getMenuItemByNameActionDelete($aItem)
    {
        $oProfile = $this->_oModule->getProfileObject(($this->_iContentId));
        if (!$this->_oModule->isAllowDeleteOrDisable(bx_get_logged_profile_id(), $oProfile->id()))
            return false;
        
        return $this->_getMenuItemByNameActionsMore($aItem);
    }
    
    protected function _getMenuItemByNameActionsMore($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($this->_oMenuActionsMore)) {
            if(empty($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY_MORE']))
                return '';

            $this->_oMenuActionsMore = BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY_MORE']);
            if(!$this->_oMenuActionsMore)
                return '';

            $this->_oMenuActionsMore->setContentId($this->_iContentId);
            
            $this->addMarkers($this->_oMenuActionsMore->getMarkers());
        }

        $aItem = $this->_oMenuActionsMore->getMenuItem($aItem['name']);
        if(empty($aItem) || !is_array($aItem))
            return false;

        return $this->_getMenuItemDefault($aItem);
    }
}

/** @} */
