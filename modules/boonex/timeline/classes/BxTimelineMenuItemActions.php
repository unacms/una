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

    protected $_sView;
    protected $_bShowTitles;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_oModule = BxDolModule::getInstance('bx_timeline');

        parent::__construct($aObject, $this->_oModule->_oTemplate);

        $this->_bShowTitles = false;

        $this->addMarkers(array(
            'js_object_view' => $this->_oModule->_oConfig->getJsObject('view')
        ));
    }

    public function setView($sView)
    {
        $this->_sView = $sView;

        $this->addMarkers(array(
            'view' => $this->_sView
        ));
    }

    public function setEvent($aEvent)
    {
        if(empty($aEvent) || !is_array($aEvent))
            return;

        $this->_aEvent = $aEvent;
        $this->_iEvent = (int)$this->_aEvent['id'];

        $iCommentsObject = 0;
        $sCommentsSystem = $sCommentsOnclick = '';
        if(isset($aEvent['comments']) && is_array($aEvent['comments']) && isset($aEvent['comments']['system'])) {
            $sCommentsSystem = $aEvent['comments']['system'];
            $iCommentsObject = $aEvent['comments']['object_id'];
            $sCommentsOnclick = $this->_oModule->_oConfig->getJsObject('view') . ".commentItem(this, '" . $sCommentsSystem . "', " . $iCommentsObject . ")";
        }

        $this->addMarkers(array(
            'content_id' => $this->_iEvent,

            'comment_system' => $sCommentsSystem,
            'comment_object' => $iCommentsObject,
            'comment_onclick' => $sCommentsOnclick,
        ));
    }

    public function setEventById($iEventId)
    {
    	$aEvent = $this->_oModule->_oDb->getEvents(array('browse' => 'id', 'value' => $iEventId));
    	if(empty($aEvent) || !is_array($aEvent))
            return;

    	$aEventData = $this->_oModule->_oTemplate->getData($aEvent);
    	if($aEventData === false)
            return;

    	$aEvent['views'] = $aEventData['views'];
        $aEvent['votes'] = $aEventData['votes'];
        $aEvent['reports'] = $aEventData['reports'];
        $aEvent['comments'] = $aEventData['comments'];

    	$this->setEvent($aEvent);
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

			case 'item-report':
                $sCheckFuncName = 'isAllowedReport';
                break;

            case 'item-more':
            	$sCheckFuncName = 'isAllowedMore';
            	break;
            case 'item-pin':
                $sCheckFuncName = 'isAllowedPin';
                break;

			case 'item-unpin':
                $sCheckFuncName = 'isAllowedUnpin';
                break;

            case 'item-promote':
                $sCheckFuncName = 'isAllowedPromote';
                break;

			case 'item-unpromote':
                $sCheckFuncName = 'isAllowedUnpromote';
                break;

            case 'item-edit':
                $sCheckFuncName = 'isAllowedEdit';
				$aCheckFuncParams = array($this->_aEvent);
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
