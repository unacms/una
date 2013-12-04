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

bx_import('BxTemplMenu');

/**
 * 'Item' menu.
 */
class BxTimelineMenuItem extends BxTemplMenu {
	protected $_aEvent;
    protected $_oModule;

    public function __construct($aObject, $oTemplate = false) {
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

    	$sVotesOnclick = '';
    	if(isset($aEvent['votes']) && is_array($aEvent['votes']) && isset($aEvent['votes']['system']))
    		$sVotesOnclick = $this->_oModule->getVoteObject($aEvent['votes']['system'], $aEvent['votes']['object_id'])->getJsClick();

    	$sCommentsSystem = '';
    	if(isset($aEvent['comments']) && is_array($aEvent['comments']) && isset($aEvent['comments']['system']))
    		$sCommentsSystem = $aEvent['comments']['system'];

    	$iOwnerId = $this->_oModule->getUserId(); //--- in whose timeline the content will be shared
		$sType = $aEvent['type'];
		$sAction = $aEvent['action'];

		if($this->_oModule->_oConfig->isSystem($sType, $sAction))
			$iObjectId = $aEvent['object_id'];
		else {
			$iObjectId = $aEvent['id'];

			$sCommonPrefix = $this->_oModule->_oConfig->getPrefix('common_post');
			if(str_replace($sCommonPrefix, '', $sType) == BX_TIMELINE_PARSE_TYPE_SHARE) {
				$sType = $aEvent['content']['type'];
				$sAction = $aEvent['content']['action'];
				$iObjectId = $aEvent['content']['object_id'];
			}
		}

    	$this->addMarkers(array(
    		'content_id' => $aEvent['id'],
    		'share_onclick' => $this->_oModule->serviceGetShareJsClick($iOwnerId, $sType, $sAction, $iObjectId),
    		'comment_onclick' => $this->_oModule->_oConfig->getJsObject('view') . ".commentItem(this, '" . $sCommentsSystem . "', " . $aEvent['id'] . ")",
    		'vote_onclick' => $sVotesOnclick
		));    	
    }

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */ 
    protected function _isVisible ($a) {
        if(!parent::_isVisible($a))
        	return false;

		$sCheckFuncName = '';
        $aCheckFuncParams = array();
        switch ($a['name']) {
            case 'item-delete':
                $sCheckFuncName = 'isAllowedDelete';
                if(!empty($this->_aEvent))
                	$aCheckFuncParams = array($this->_aEvent);
                break;

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

    /** 
     * Get menu items array, which are ready to pass to template.
     * @return array
     */
    protected function _getMenuItems() {
    	$aItems = parent::_getMenuItems();

    	foreach($aItems as $iKey => $aItem)
    		switch($aItem['name']) {
    			case 'item-vote':
    				if(isset($this->_aEvent['votes']) && is_array($this->_aEvent['votes']) && isset($this->_aEvent['votes']['system'])) {
			    		$oVote = $this->_oModule->getVoteObject($this->_aEvent['votes']['system'], $this->_aEvent['votes']['object_id']);

			    		$aItems[$iKey]['addon'] = $oVote->getCounter();
			    	}
    				break;

    			case 'item-comment':
    				$aItems[$iKey]['addon'] = (int)$this->_aEvent['comments']['count'] > 0 ? (int)$this->_aEvent['comments']['count'] : 0;
    				break;

    			case 'item-share':
    				$sType = $this->_aEvent['type'];
					$sAction = $this->_aEvent['action'];
					$iObjectId = $this->_oModule->_oConfig->isSystem($sType, $sAction) ? $this->_aEvent['object_id'] : $this->_aEvent['id'];

    				$aItems[$iKey]['addon'] = $this->_oModule->serviceGetShareCounter($sType, $sAction, $iObjectId);
    				break;
    		}

		return $aItems;
    }
}

/** @} */
