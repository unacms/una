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
    protected $_aEvent;
    protected $_oModule;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_timeline');
    }

    public function setEvent($sView, $aEvent)
    {
        if(empty($aEvent) || !is_array($aEvent))
            return;

        $this->_aEvent = $aEvent;

        $iCommentsObject = 0;
        $sCommentsSystem = $sCommentsOnclick = '';
        if(isset($aEvent['comments']) && is_array($aEvent['comments']) && isset($aEvent['comments']['system'])) {
            $sCommentsSystem = $aEvent['comments']['system'];
            $iCommentsObject = $aEvent['comments']['object_id'];
            $sCommentsOnclick = $this->_oModule->_oConfig->getJsObject('view') . ".commentItem(this, '" . $sCommentsSystem . "', " . $iCommentsObject . ")";
        }

        $this->addMarkers(array(
        	'js_object_view' => $this->_oModule->_oConfig->getJsObject('view'),

            'view' => $sView,
            'content_id' => $aEvent['id'],

            'comment_system' => $sCommentsSystem,
            'comment_object' => $iCommentsObject,
            'comment_onclick' => $sCommentsOnclick,
        ));
    }

    public function isVisible()
    {
    	if(!isset($this->_aObject['menu_items']))
			$this->_aObject['menu_items'] = $this->_oQuery->getMenuItems();

    	$bVisible = false;
    	foreach ($this->_aObject['menu_items'] as $a) {
    		if((isset($a['active']) && !$a['active']) || (isset($a['visible_for_levels']) && !$this->_isVisible($a)))
				continue;
			
			$bVisible = true;
			break;
    	}

    	return $bVisible;
    }

    protected function _getMenuItemItemView($aItem)
    {
        if(!isset($this->_aEvent['views']) || !is_array($this->_aEvent['views']) || !isset($this->_aEvent['views']['system'])) 
        	return false;

		$sViewsSystem = $this->_aEvent['views']['system'];
		$iViewsObject = $this->_aEvent['views']['object_id'];
    	return $this->_oModule->getViewObject($sViewsSystem, $iViewsObject)->getElementInline(array('dynamic_mode' => $this->_bDynamicMode));
    }

    protected function _getMenuItemItemVote($aItem)
    {
        if(!isset($this->_aEvent['votes']) || !is_array($this->_aEvent['votes']) || !isset($this->_aEvent['votes']['system'])) 
        	return false;

		$sVotesSystem = $this->_aEvent['votes']['system'];
		$iVotesObject = $this->_aEvent['votes']['object_id'];
    	return $this->_oModule->getVoteObject($sVotesSystem, $iVotesObject)->getElementInline(array('dynamic_mode' => $this->_bDynamicMode));
    }

	protected function _getMenuItemItemReport($aItem)
    {
        if(!isset($this->_aEvent['reports']) || !is_array($this->_aEvent['reports']) || !isset($this->_aEvent['reports']['system'])) 
        	return false;

		$sReportsSystem = $this->_aEvent['reports']['system'];
		$iReportsObject = $this->_aEvent['reports']['object_id'];
    	return $this->_oModule->getReportObject($sReportsSystem, $iReportsObject)->getElementInline(array('dynamic_mode' => $this->_bDynamicMode));
    }

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        if(!parent::_isVisible($a) || empty($this->_aEvent))
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
        }

        if(!$sCheckFuncName || !method_exists($this->_oModule, $sCheckFuncName))
            return true;

        return call_user_func_array(array($this->_oModule, $sCheckFuncName), $aCheckFuncParams) === true;
    }
}

/** @} */
