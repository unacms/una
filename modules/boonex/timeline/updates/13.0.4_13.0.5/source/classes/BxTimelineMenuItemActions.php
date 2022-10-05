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
    protected static $_sModeActions = 'actions';
    protected static $_sModeCounters = 'counters';

    protected $_sModule;
    protected $_oModule;

    protected $_iEvent;
    protected $_aEvent;

    protected $_sType;
    protected $_sView;

    protected $_sMode;
    protected $_bShowTitles;
    protected $_bShowCounters;
    protected $_bShowCountersEmpty;
    protected $_bShowCountersIcons;

    protected $_sTmplNameItem;

    protected $_aItem2CheckFunc;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_timeline';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $this->_oModule->_oTemplate);

        $this->_setBrowseParams();

        $this->_sMode = self::$_sModeActions;
        $this->_bShowTitles = true;
        $this->_bShowCounters = false;
        $this->_bShowCountersEmpty = false;
        $this->_bShowCountersIcons = true;
        $this->_sTmplNameItem = 'menu_custom_item_hor.html';

        $this->_aItem2CheckFunc = array(
            'item-view' => 'isAllowedViewCounter',
            'item-comment' => 'isAllowedComment',
            'item-vote' => 'isAllowedVote',
            'item-reaction' => 'isAllowedReaction',
            'item-score' => 'isAllowedScore',
            'item-report' => 'isAllowedReport',
            'item-mute' => 'isAllowedMute',
            'item-more' => 'isAllowedMore',
            'item-pin' => 'isAllowedPin',
            'item-unpin' => 'isAllowedUnpin',
            'item-stick' => 'isAllowedStick',
            'item-unstick' => 'isAllowedUnstick',
            'item-promote' => 'isAllowedPromote',
            'item-unpromote' => 'isAllowedUnpromote',
            'item-edit' => 'isAllowedEdit',
            'item-delete' => 'isAllowedDelete',
            'item-notes' => 'isAllowedNotes',
        );
    }

    public function setEvent($aEvent, $aBrowseParams = array())
    {
        if(empty($aEvent) || !is_array($aEvent))
            return false;

        $this->_aEvent = $aEvent;
        $this->_iEvent = (int)$this->_aEvent['id'];

        $sType = $this->_aEvent['type'];
        $sAction = $this->_aEvent['action'];

        $this->_setBrowseParams($aBrowseParams);

        //--- Comment item
        $iCommentsObject = 0;
        $sCommentsSystem = $sCommentsOnclick = '';
        if(isset($aEvent['comments']) && is_array($aEvent['comments']) && isset($aEvent['comments']['system'])) {
            $sCommentsSystem = $aEvent['comments']['system'];
            $iCommentsObject = $aEvent['comments']['object_id'];
            $sCommentsOnclick = bx_replace_markers("{js_object_view}.commentItem(this, '" . $sCommentsSystem . "', " . $iCommentsObject . ")", $this->_aMarkers);
        }

        //--- Repost item
        $iOwnerId = $this->_oModule->getUserId(); //--- in whose timeline the content will be reposted
        $iObjectId = $this->_oModule->_oConfig->isSystem($sType, $sAction) ? $this->_aEvent['object_id'] : $this->_aEvent['id'];
        $sRepostOnclick = $this->_oModule->serviceGetRepostJsClick($iOwnerId, $sType, $sAction, $iObjectId);

        $bSystem = $this->_oModule->_oConfig->isSystem($sType, $sAction);

        $this->addMarkers(array(
            'content_id' => $this->_iEvent,

            'comment_system' => $sCommentsSystem,
            'comment_object' => $iCommentsObject,
            'comment_onclick' => $sCommentsOnclick,

            'repost_onclick' => $sRepostOnclick,

            'delete_title' => _t('_bx_timeline_menu_item_title_item_delete_' . ($bSystem || $this->_oModule->_oConfig->isHideUponDelete() ? 'system' : 'common'))
        ));

        return true;
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

    public function setTemplateNameItem($sName)
    {
        $this->_sTmplNameItem = $sName;
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
        $aViewsParams = array(
            'show_do_view_label' => $this->_bShowTitles,
            'show_counter' => $this->_bShowCounters, 
            'show_counter_label_icon' => $this->_bShowCountersIcons,
            'dynamic_mode' => $this->_bDynamicMode
        );

        switch($this->_sMode) {
            case self::$_sModeActions:
                $sViewsMethod = 'getElementInline';
                break;

            case self::$_sModeCounters:
                $sViewsMethod = 'getCounter';
                break;
        }

        return $this->_oModule->getViewObject($sViewsSystem, $iViewsObject)->$sViewsMethod($aViewsParams);
    }

    protected function _getMenuItemItemVote($aItem)
    {
        if(!isset($this->_aEvent['votes']) || !is_array($this->_aEvent['votes']) || !isset($this->_aEvent['votes']['system'])) 
            return false;

        $sVotesSystem = $this->_aEvent['votes']['system'];
        $iVotesObject = $this->_aEvent['votes']['object_id'];
        $aVotesParams = array(
            'show_do_vote_label' => $this->_bShowTitles,
            'show_counter' => $this->_bShowCounters,
            'show_counter_empty' => $this->_bShowCountersEmpty,
            'show_counter_label_icon' => $this->_bShowCountersIcons,
            'dynamic_mode' => $this->_bDynamicMode
        );

        switch($this->_sMode) {
            case self::$_sModeActions:
                $sVotesMethod = 'getElementInline';
                break;

            case self::$_sModeCounters:
                $sVotesMethod = 'getCounter';
                break;
        }

        return $this->_oModule->getVoteObject($sVotesSystem, $iVotesObject)->$sVotesMethod($aVotesParams);
    }

    protected function _getMenuItemItemReaction($aItem)
    {
        if(!isset($this->_aEvent['reactions']) || !is_array($this->_aEvent['reactions']) || !isset($this->_aEvent['reactions']['system'])) 
            return false;

        $sReactionsSystem = $this->_aEvent['reactions']['system'];
        $iReactionsObject = $this->_aEvent['reactions']['object_id'];
        $aReactionsParams = array(
            'show_do_vote_label' => $this->_bShowTitles,
            'show_counter' => $this->_bShowCounters,
            'show_counter_empty' => $this->_bShowCountersEmpty,
            'show_counter_style' => 'compound',
            'show_counter_label_icon' => $this->_bShowCountersIcons,
            'dynamic_mode' => $this->_bDynamicMode
        );
        
        switch($this->_sMode) {
            case self::$_sModeActions:
                $sReactionsMethod = 'getElementInline';
                break;

            case self::$_sModeCounters:
                $sReactionsMethod = 'getCounter';
                break;
        }

    	return $this->_oModule->getReactionObject($sReactionsSystem, $iReactionsObject)->$sReactionsMethod($aReactionsParams);
    }

    protected function _getMenuItemItemScore($aItem)
    {
        if(!isset($this->_aEvent['scores']) || !is_array($this->_aEvent['scores']) || !isset($this->_aEvent['scores']['system'])) 
            return false;

        $sScoresSystem = $this->_aEvent['scores']['system'];
        $iScoresObject = $this->_aEvent['scores']['object_id'];
        $aScoresParams = array(
            'show_do_vote_label' => $this->_bShowTitles,
            'show_counter' => $this->_bShowCounters,
            'show_counter_empty' => $this->_bShowCountersEmpty,
            'show_counter_label_icon' => $this->_bShowCountersIcons,
            'dynamic_mode' => $this->_bDynamicMode
        );

        switch($this->_sMode) {
            case self::$_sModeActions:
                $sScoresMethod = 'getElementInline';
                break;

            case self::$_sModeCounters:
                $sScoresMethod = 'getCounter';
                break;
        }

        return $this->_oModule->getScoreObject($sScoresSystem, $iScoresObject)->$sScoresMethod($aScoresParams);
    }

    protected function _getMenuItemItemReport($aItem)
    {
        if(!isset($this->_aEvent['reports']) || !is_array($this->_aEvent['reports']) || !isset($this->_aEvent['reports']['system'])) 
            return false;

        $sReportsSystem = $this->_aEvent['reports']['system'];
        $iReportsObject = $this->_aEvent['reports']['object_id'];
        $aReportsParams = array(
            'show_do_report_label' => $this->_bShowTitles,
            'show_counter' => $this->_bShowCounters,
            'dynamic_mode' => $this->_bDynamicMode
        );
        
        switch($this->_sMode) {
            case self::$_sModeActions:
                $sReportsMethod = 'getElementInline';
                break;

            case self::$_sModeCounters:
                $sReportsMethod = 'getCounter';
                break;
        }

        return $this->_oModule->getReportObject($sReportsSystem, $iReportsObject)->$sReportsMethod($aReportsParams);
    }

    protected function _getMenuItemItemRepost($aItem)
    {
        if(!isset($this->_aEvent['reposts'])) 
            return false;

        $aRepostsParams = array(
            'show_do_repost_text' => $this->_bShowTitles,
            'show_counter' => $this->_bShowCounters,
            'show_counter_label_icon' => $this->_bShowCountersIcons,
            'dynamic_mode' => $this->_bDynamicMode
        );

        $sType = $this->_aEvent['type'];
        $sAction = $this->_aEvent['action'];
        $iObjectId = $this->_oModule->_oConfig->isSystem($sType, $sAction) ? $this->_aEvent['object_id'] : $this->_aEvent['id'];

        $sMethodName = '';
        $aMethodParams = [];
        switch($this->_sMode) {
            case self::$_sModeActions:
                $sMethodName = 'serviceGetRepostElementBlock';
                $aMethodParams = [$this->_aEvent['owner_id'], $sType, $sAction, $iObjectId, $aRepostsParams];
                break;

            case self::$_sModeCounters:
                $sMethodName = 'serviceGetRepostCounter';
                $aMethodParams = [$sType, $sAction, $iObjectId, $aRepostsParams];
                break;
        }

        return call_user_func_array([$this->_oModule, $sMethodName], $aMethodParams);
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
            case 'item-comment':
                if($this->_sView == BX_TIMELINE_VIEW_ITEM)
                    return false;

                $sCheckFuncName = $this->_aItem2CheckFunc[$a['name']];
                break;

            case 'item-pin':
                if($this->_sType != BX_BASE_MOD_NTFS_TYPE_OWNER)
                    return false;

                $sCheckFuncName = $this->_aItem2CheckFunc[$a['name']];
                break;

            case 'item-unpin':
                if($this->_sType != BX_BASE_MOD_NTFS_TYPE_OWNER)
                    return false;

                $sCheckFuncName = $this->_aItem2CheckFunc[$a['name']];
                break;
        }

        if(empty($sCheckFuncName) && isset($this->_aItem2CheckFunc[$a['name']]))
            $sCheckFuncName = $this->_aItem2CheckFunc[$a['name']];

        if(!$sCheckFuncName || !method_exists($this->_oModule, $sCheckFuncName))
            return true;

        return call_user_func_array(array($this->_oModule, $sCheckFuncName), $aCheckFuncParams) === true;
    }
    
    protected function _getTmplContentItem()
    {
        if(empty($this->_sTmplNameItem))
           return parent::_getTmplContentItem();

        return $this->_oModule->_oTemplate->getHtml($this->_sTmplNameItem);
    }
}

/** @} */
