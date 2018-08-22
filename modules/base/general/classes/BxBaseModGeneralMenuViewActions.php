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

/**
 * View entry social actions menu
 */
class BxBaseModGeneralMenuViewActions extends BxTemplMenuCustom
{
    protected $_sModule;
    protected $_oModule;

    protected $_oMenuAction;
    protected $_oMenuSocialSharing;

    protected $_iContentId;
    protected $_aContentInfo;

    protected $_bDynamicMode;
    protected $_bShowAsButton;
    protected $_bShowTitle;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->setContentId(bx_process_input(bx_get('id'), BX_DATA_INT));

        $this->_oMenuActions = null;
        $this->_oMenuSocialSharing = null;

        $this->_bShowAsButton = true;
        $this->_bShowTitle = false;
    }

    public function setContentId($iContentId)
    {
        $this->_iContentId = (int)$iContentId;

        $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_iContentId);
        if($this->_aContentInfo)
            $this->addMarkers(array('content_id' => (int)$this->_iContentId));
    }

    protected function _getMenuItemDefault ($aItem)
    {
        if($this->_bShowAsButton)
            $aItem['class_link'] = 'bx-btn' . (!empty($aItem['class_link']) ? ' ' . $aItem['class_link'] : '');

        if(!$this->_bShowTitle)
            $aItem['bx_if:title']['condition'] = false;

        return parent::_getMenuItemDefault ($aItem);
    }

    protected function _getMenuItemView($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oObject = !empty($CNF['OBJECT_VIEWS']) ? BxDolView::getObjectInstance($CNF['OBJECT_VIEWS'], $this->_iContentId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

    	return $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_view_as_button' => $this->_bShowAsButton,
            'show_do_view_label' => $this->_bShowTitle
        ));
    }

    protected function _getMenuItemComment($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oObject = !empty($CNF['OBJECT_COMMENTS']) ? BxTemplCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $this->_iContentId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

        return $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_comment_as_button' => $this->_bShowAsButton,
            'show_do_comment_label' => $this->_bShowTitle
        ));
    }

    protected function _getMenuItemVote($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oObject = !empty($CNF['OBJECT_VOTES']) ? BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $this->_iContentId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

    	return $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_vote_as_button' => $this->_bShowAsButton,
            'show_do_vote_label' => $this->_bShowTitle
        ));
    }
    
    protected function _getMenuItemScore($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oObject = !empty($CNF['OBJECT_SCORES']) ? BxDolScore::getObjectInstance($CNF['OBJECT_SCORES'], $this->_iContentId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

    	return $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_vote_as_button' => $this->_bShowAsButton,
            'show_do_vote_label' => $this->_bShowTitle
        ));
    }

    protected function _getMenuItemFavorite($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oObject = !empty($CNF['OBJECT_FAVORITES']) ? BxDolFavorite::getObjectInstance($CNF['OBJECT_FAVORITES'], $this->_iContentId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

    	return $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_favorite_as_button' => $this->_bShowAsButton,
            'show_do_favorite_label' => $this->_bShowTitle
        ));
    }

    protected function _getMenuItemFeature($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oObject = !empty($CNF['OBJECT_FEATURED']) ? BxDolFeature::getObjectInstance($CNF['OBJECT_FEATURED'], $this->_iContentId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

    	return $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_feature_as_button' => $this->_bShowAsButton,
            'show_do_feature_label' => $this->_bShowTitle
        ));
    }

    protected function _getMenuItemRepost($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!BxDolRequest::serviceExists('bx_timeline', 'get_repost_element_block'))
            return '';

    	return BxDolService::call('bx_timeline', 'get_repost_element_block', array(bx_get_logged_profile_id(), $this->_oModule->_oConfig->getName(), 'added', $this->_iContentId, array(
            'show_do_repost_as_button' => $this->_bShowAsButton,
            'show_do_repost_text' => $this->_bShowTitle
        )));
    }

    protected function _getMenuItemReport($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oObject = !empty($CNF['OBJECT_REPORTS']) ? BxDolReport::getObjectInstance($CNF['OBJECT_REPORTS'], $this->_iContentId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

    	return $oObject->getElementBlock(array(
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_report_as_button' => $this->_bShowAsButton,
            'show_do_report_label' => $this->_bShowTitle
        ));
    }

    protected function _getMenuItemSocialSharingFacebook($aItem)
    {
        return $this->_getMenuItemByNameSocialSharing($aItem);
    }

    protected function _getMenuItemSocialSharingGoogleplus($aItem)
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

    protected function _getMenuItemByNameActions($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($this->_oMenuActions)) {
            if(empty($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY']))
                return '';

            $this->_oMenuActions = BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY']);
            $this->_oMenuActions->setContentId($this->_iContentId);
        }

        $aItem = $this->_oMenuActions->getMenuItem($aItem['name']);
        if(empty($aItem) || !is_array($aItem))
            return false;

        return $this->_getMenuItemDefault($aItem);
    }

    protected function _getMenuItemByNameSocialSharing($aItem)
    {
        if(empty($this->_oMenuSocialSharing)) {
            $this->_oMenuSocialSharing = BxDolMenu::getObjectInstance('sys_social_sharing');
            $this->_oMenuSocialSharing->addMarkers(array_merge($this->_aMarkers, array(
                'id' => $this->_iContentId,
                'module' => $this->_oModule->_oConfig->getName(),
            )));
        }

        $aItem = $this->_oMenuSocialSharing->getMenuItem($aItem['name']);
        if(empty($aItem) || !is_array($aItem))
            return false;

        return $this->_getMenuItemDefault($aItem);
    }
}

/** @} */
