<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Timeline Timeline
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplMenu');

/**
 * 'Item' menu.
 */
class BxTimelineMenuItemActions extends BxTemplMenu
{
    protected $_aEvent;
    protected $_oModule;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_timeline');

        $this->addMarkers(array(
            'js_object_view' => $this->_oModule->_oConfig->getJsObject('view'),
        ));
    }

    public function setEvent($aEvent)
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

        $iOwnerId = $this->_oModule->getUserId(); //--- in whose timeline the content will be shared
        $sShareType = $sReshareType = $aEvent['type'];
        $sShareAction = $sReshareAction = $aEvent['action'];

        if($this->_oModule->_oConfig->isSystem($sShareType, $sShareAction))
            $iShareObject = $iReshareObject = $aEvent['object_id'];
        else {
            $iShareObject = $iReshareObject = $aEvent['id'];

            $sCommonPrefix = $this->_oModule->_oConfig->getPrefix('common_post');
            if(str_replace($sCommonPrefix, '', $sShareType) == BX_TIMELINE_PARSE_TYPE_SHARE) {
                $sReshareType = $aEvent['content']['type'];
                $sReshareAction = $aEvent['content']['action'];
                $iReshareObject = $aEvent['content']['object_id'];
            }
        }

        $this->addMarkers(array(
            'content_id' => $aEvent['id'],

            'comment_system' => $sCommentsSystem,
            'comment_object' => $iCommentsObject,
            'comment_onclick' => $sCommentsOnclick,

            'share_type' => $sShareType,
            'share_action' => $sShareAction,
            'share_object' => $iShareObject,
            'share_onclick' => $this->_oModule->serviceGetShareJsClick($iOwnerId, $sReshareType, $sReshareAction, $iReshareObject),
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

    protected function _getMenuItem ($aItem)
    {
    	if (isset($aItem['active']) && !$aItem['active'])
			return false;

		if (isset($aItem['visible_for_levels']) && !$this->_isVisible($aItem))
        	return false;

    	$sMethod = '_getMenuItem' . str_replace(' ', '', ucwords(str_replace('-', ' ', $aItem['name'])));

    	if(!method_exists($this, $sMethod)) {
    		$aItem = parent::_getMenuItem($aItem);
    		if($aItem === false)
    			return false;

			$sItem = $this->_oModule->_oTemplate->parseHtmlByName('menu_item.html', $aItem);
    	}
    	else
    		$sItem = $this->$sMethod($aItem);

    	if(empty($sItem))
    		return false;

		return array(
			'class' => $this->_isSelected($aItem) ? 'bx-menu-tab-active' : '',
			'item' => $sItem
		);
    }

    protected function _getMenuItemItemVote($aItem)
    {
        if(!isset($this->_aEvent['votes']) || !is_array($this->_aEvent['votes']) || !isset($this->_aEvent['votes']['system'])) 
        	return false;

		$sVotesSystem = $this->_aEvent['votes']['system'];
		$iVotesObject = $this->_aEvent['votes']['object_id'];
    	return $this->_oModule->getVoteObject($sVotesSystem, $iVotesObject)->getElementInline();
    }

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        if(!parent::_isVisible($a))
            return false;

        $sCheckFuncName = '';
        $aCheckFuncParams = array();
        switch ($a['name']) {
            case 'item-comment':
                $sCheckFuncName = 'isAllowedComment';
                if(!empty($this->_aEvent))
                    $aCheckFuncParams = array($this->_aEvent);
                break;

            case 'item-vote':
                $sCheckFuncName = 'isAllowedVote';
                if(!empty($this->_aEvent))
                    $aCheckFuncParams = array($this->_aEvent);
                break;

            case 'item-share':
                $sCheckFuncName = 'isAllowedShare';
                if(!empty($this->_aEvent))
                    $aCheckFuncParams = array($this->_aEvent);
                break;
        }

        if(!$sCheckFuncName || !method_exists($this->_oModule, $sCheckFuncName))
            return true;

        return call_user_func_array(array($this->_oModule, $sCheckFuncName), $aCheckFuncParams) === true;
    }
}

/** @} */
