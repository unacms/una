<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Timeline Timeline
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxTemplMenuCustom');

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

        $this->addMarkers(array(
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

    protected function _getMenuItemItemVote($aItem)
    {
        if(!isset($this->_aEvent['votes']) || !is_array($this->_aEvent['votes']) || !isset($this->_aEvent['votes']['system'])) 
        	return false;

		$sVotesSystem = $this->_aEvent['votes']['system'];
		$iVotesObject = $this->_aEvent['votes']['object_id'];
    	return $this->_oModule->getVoteObject($sVotesSystem, $iVotesObject)->getElementInline();
    }

	protected function _getMenuItemItemShare($aItem)
    {
		$iOwnerId = $this->_oModule->getUserId(); //--- in whose timeline the content will be shared
        $sType = $this->_aEvent['type'];
        $sAction = $this->_aEvent['action'];
        $iObjectId = $this->_oModule->_oConfig->isSystem($sType, $sAction) ? $this->_aEvent['object_id'] : $this->_aEvent['id'];

        return $this->_oModule->serviceGetShareElementBlock($iOwnerId, $sType, $sAction, $iObjectId);
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
        }

        if(!$sCheckFuncName || !method_exists($this->_oModule, $sCheckFuncName))
            return true;

        return call_user_func_array(array($this->_oModule, $sCheckFuncName), $aCheckFuncParams) === true;
    }
}

/** @} */
