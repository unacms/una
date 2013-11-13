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
    	if(isset($aEvent['comments']) && is_array($aEvent['comments']) && isset($aEvent['comments']['system']))
    		$sCommentsSystem = $aEvent['comments']['system'];

    	$this->addMarkers(array(
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
