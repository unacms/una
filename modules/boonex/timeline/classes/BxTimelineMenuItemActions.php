<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * 'Item' menu.
 */
class BxTimelineMenuItemActions extends BxTemplMenuCustom
{
    protected $_oModule;

    protected $_iEvent;
    protected $_aEvent;

    protected $_sType;
    protected $_sView;
    protected $_bShowTitles;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_oModule = BxDolModule::getInstance('bx_timeline');

        parent::__construct($aObject, $this->_oModule->_oTemplate);

        $this->_setBrowseParams();

        $this->_bShowTitles = false;
    }

    public function setEvent($aEvent, $aBrowseParams = array())
    {
        if(empty($aEvent) || !is_array($aEvent))
            return;

        $this->_aEvent = $aEvent;
        $this->_iEvent = (int)$this->_aEvent['id'];

        $this->_setBrowseParams($aBrowseParams);

        $iCommentsObject = 0;
        $sCommentsSystem = $sCommentsOnclick = '';
        if(isset($aEvent['comments']) && is_array($aEvent['comments']) && isset($aEvent['comments']['system'])) {
            $sCommentsSystem = $aEvent['comments']['system'];
            $iCommentsObject = $aEvent['comments']['object_id'];
            $sCommentsOnclick = bx_replace_markers("{js_object_view}.commentItem(this, '" . $sCommentsSystem . "', " . $iCommentsObject . ")", $this->_aMarkers);
        }

        $this->addMarkers(array(
            'content_id' => $this->_iEvent,

            'comment_system' => $sCommentsSystem,
            'comment_object' => $iCommentsObject,
            'comment_onclick' => $sCommentsOnclick,
        ));
    }

    public function setEventById($iEventId, $aBrowseParams = array())
    {
    	$aEvent = $this->_oModule->_oDb->getEvents(array('browse' => 'id', 'value' => $iEventId));
    	if(empty($aEvent) || !is_array($aEvent))
            return;

    	$aEventData = $this->_oModule->_oTemplate->getDataCached($aEvent);
    	if($aEventData === false)
            return;

    	$aEvent['views'] = $aEventData['views'];
        $aEvent['votes'] = $aEventData['votes'];
        $aEvent['reactions'] = $aEventData['reactions'];
        $aEvent['scores'] = $aEventData['scores'];
        $aEvent['reports'] = $aEventData['reports'];
        $aEvent['comments'] = $aEventData['comments'];

    	$this->setEvent($aEvent, $aBrowseParams);
    }

    protected function _setBrowseParams($aBrowseParams = array())
    {
        $this->_sType = !empty($aBrowseParams['type']) ? $aBrowseParams['type'] : BX_TIMELINE_TYPE_DEFAULT;
        $this->_sView = !empty($aBrowseParams['view']) ? $aBrowseParams['view'] : BX_TIMELINE_VIEW_DEFAULT;

        $aMarkers = array();
        foreach($aBrowseParams as $sKey => $mixedValue)
            if(!is_array($mixedValue))
                $aMarkers[$sKey] = $mixedValue;

        $this->addMarkers($aMarkers);
        $this->addMarkers(array(
            'js_object_view' => $this->_oModule->_oConfig->getJsObjectView($aBrowseParams)
        ));
    }

    protected function _getMenuItemItemView($aItem)
    {
        if(!isset($this->_aEvent['views']) || !is_array($this->_aEvent['views']) || !isset($this->_aEvent['views']['system'])) 
            return false;

        $sViewsSystem = $this->_aEvent['views']['system'];
        $iViewsObject = $this->_aEvent['views']['object_id'];
        $aViewsParams = array('dynamic_mode' => $this->_bDynamicMode);
        if($this->_bShowTitles)
            $aViewsParams['show_do_view_label'] = true;

        return $this->_oModule->getViewObject($sViewsSystem, $iViewsObject)->getElementInline($aViewsParams);
    }

    protected function _getMenuItemItemComment($aItem)
    {
        if($this->_sView == BX_TIMELINE_VIEW_ITEM)
            return false;

        $aItem = BxTemplMenu::_getMenuItem($aItem);
        if($aItem === false)
            return false;

        return $this->_getMenuItemDefault($aItem);
    }

    protected function _getMenuItemItemVote($aItem)
    {
        if(!isset($this->_aEvent['votes']) || !is_array($this->_aEvent['votes']) || !isset($this->_aEvent['votes']['system'])) 
            return false;

        $sVotesSystem = $this->_aEvent['votes']['system'];
        $iVotesObject = $this->_aEvent['votes']['object_id'];
        $aVotesParams = array('dynamic_mode' => $this->_bDynamicMode);
        if($this->_bShowTitles)
            $aVotesParams['show_do_vote_label'] = true;

        return $this->_oModule->getVoteObject($sVotesSystem, $iVotesObject)->getElementInline($aVotesParams);
    }

    protected function _getMenuItemItemReaction($aItem)
    {
        if(!isset($this->_aEvent['reactions']) || !is_array($this->_aEvent['reactions']) || !isset($this->_aEvent['reactions']['system'])) 
            return false;

        $sReactionsSystem = $this->_aEvent['reactions']['system'];
        $iReactionsObject = $this->_aEvent['reactions']['object_id'];
        $aReactionsParams = array('dynamic_mode' => $this->_bDynamicMode);
        if($this->_bShowTitles)
            $aReactionsParams['show_do_vote_label'] = true;

    	return $this->_oModule->getReactionObject($sReactionsSystem, $iReactionsObject)->getElementInline($aReactionsParams);
    }

    protected function _getMenuItemItemScore($aItem)
    {
        if(!isset($this->_aEvent['scores']) || !is_array($this->_aEvent['scores']) || !isset($this->_aEvent['scores']['system'])) 
            return false;

        $sScoresSystem = $this->_aEvent['scores']['system'];
        $iScoresObject = $this->_aEvent['scores']['object_id'];
        $aScoresParams = array('dynamic_mode' => $this->_bDynamicMode);
        if($this->_bShowTitles)
            $aScoresParams['show_do_vote_label'] = true;

        return $this->_oModule->getScoreObject($sScoresSystem, $iScoresObject)->getElementInline($aScoresParams);
    }

    protected function _getMenuItemItemReport($aItem)
    {
        if(!isset($this->_aEvent['reports']) || !is_array($this->_aEvent['reports']) || !isset($this->_aEvent['reports']['system'])) 
            return false;

        $sReportsSystem = $this->_aEvent['reports']['system'];
        $iReportsObject = $this->_aEvent['reports']['object_id'];
        $aReportsParams = array('dynamic_mode' => $this->_bDynamicMode);
        if($this->_bShowTitles)
            $aReportsParams['show_do_report_label'] = true;

        return $this->_oModule->getReportObject($sReportsSystem, $iReportsObject)->getElementInline($aReportsParams);
    }

    protected function _getMenuItemDefault ($aItem)
    {
        if(!isset($aItem['class_link']))
            $aItem['class_link'] = '';

        $aItem['class_link'] = 'bx-menu-item-link' . (!empty($aItem['class_link']) ? ' ' : '') . $aItem['class_link'];

        return parent::_getMenuItemDefault ($aItem);
    }

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        if(!parent::_isVisible($a) || empty($this->_aEvent) || !is_array($this->_aEvent))
            return false;

        $sCheckFuncName = '';
        $aCheckFuncParams = array($this->_aEvent);
        switch ($a['name']) {
            case 'item-view':
                $sCheckFuncName = 'isAllowedViewCounter';
                break;

            case 'item-comment':
                $sCheckFuncName = 'isAllowedComment';
                break;

            case 'item-vote':
                $sCheckFuncName = 'isAllowedVote';
                break;
            
            case 'item-reaction':
                $sCheckFuncName = 'isAllowedReaction';
                break;

            case 'item-score':
                $sCheckFuncName = 'isAllowedScore';
                break;

            case 'item-report':
                $sCheckFuncName = 'isAllowedReport';
                break;

            case 'item-more':
            	$sCheckFuncName = 'isAllowedMore';
            	break;

            case 'item-pin':
                if($this->_sType != BX_BASE_MOD_NTFS_TYPE_OWNER)
                    return false;

                $sCheckFuncName = 'isAllowedPin';
                break;

            case 'item-unpin':
                if($this->_sType != BX_BASE_MOD_NTFS_TYPE_OWNER)
                    return false;

                $sCheckFuncName = 'isAllowedUnpin';
                break;

            case 'item-stick':
                $sCheckFuncName = 'isAllowedStick';
                break;

            case 'item-unstick':
                $sCheckFuncName = 'isAllowedUnstick';
                break;

            case 'item-promote':
                $sCheckFuncName = 'isAllowedPromote';
                break;

			case 'item-unpromote':
                $sCheckFuncName = 'isAllowedUnpromote';
                break;

            case 'item-edit':
                $sCheckFuncName = 'isAllowedEdit';
                break;

            case 'item-delete':
                $sCheckFuncName = 'isAllowedDelete';
                break;
        }

        if(!$sCheckFuncName || !method_exists($this->_oModule, $sCheckFuncName))
            return true;

        return call_user_func_array(array($this->_oModule, $sCheckFuncName), $aCheckFuncParams) === true;
    }
}

/** @} */
