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

    	$sCommentsSystem = '';
    	$iCommentsCount = 0;
    	if(isset($aEvent['comments']) && is_array($aEvent['comments']) && isset($aEvent['comments']['system'])) {
    		$sCommentsSystem = $aEvent['comments']['system'];
    		$iCommentsCount = (int)$aEvent['comments']['count'];
    	}

		$sCommonPrefix = $this->_oModule->_oConfig->getPrefix('common_post');

		$iOwnerId = $this->_oModule->getUserId(); //--- in whose timeline the content will be shared
		$sType = $aEvent['type'];
		$sAction = $aEvent['action'];

		if($this->_oModule->_oConfig->isSystem($sType, $sAction))
			$iObjectId = $aEvent['object_id'];
		else {
			$iObjectId = $aEvent['id'];

			if(str_replace($sCommonPrefix, '', $sType) == BX_TIMELINE_PARSE_TYPE_SHARE) {
				$sType = $aEvent['content']['type'];
				$sAction = $aEvent['content']['action'];
				$iObjectId = $aEvent['content']['object_id'];
			}
		}

    	$this->addMarkers(array(
    		//--- For Share Button
    		'share_onclick' => $this->_oModule->serviceGetShareOnclick($iOwnerId, $sType, $sAction, $iObjectId),
			//--- For Share Button

			'counter_shares' => (int)$aEvent['shares'] > 0 ? ' (' . $aEvent['shares'] . ')' : '', 
    		'counter_comments' => $iCommentsCount > 0 ? ' (' . $iCommentsCount . ')' : '',

			'content_id' => $aEvent['id'],
			'comments_system' => $sCommentsSystem
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
        }

        if(!$sCheckFuncName || !method_exists($this->_oModule, $sCheckFuncName))
			return true;

		return call_user_func_array(array($this->_oModule, $sCheckFuncName), $aCheckFuncParams);
    }

}

/** @} */
