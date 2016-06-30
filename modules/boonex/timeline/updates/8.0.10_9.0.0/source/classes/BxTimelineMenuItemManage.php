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

/**
 * 'Item' menu.
 */
class BxTimelineMenuItemManage extends BxTemplMenu
{
	protected $_iEvent;
    protected $_oModule;


    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_timeline');

        $this->_iEvent = 0;
        if(bx_get('content_id') !== false)
        	$this->_iEvent = (int)bx_get('content_id');

        $this->addMarkers(array(
            'js_object_view' => $this->_oModule->_oConfig->getJsObject('view'),

        	'content_id' => $this->_iEvent,
        ));
    }

    public function setEventId($iEventId)
    {
    	$this->_iEvent = $iEventId;
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

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        if(!parent::_isVisible($a))
            return false;

		$aEvent = $this->_oModule->_oDb->getEvents(array('browse' => 'id', 'value' => $this->_iEvent));
		if(empty($aEvent) || !is_array($aEvent))
			return false;

        $sCheckFuncName = '';
        $aCheckFuncParams = array();
        switch ($a['name']) {
        	case 'item-pin':
                $sCheckFuncName = 'isAllowedPin';
				$aCheckFuncParams = array($aEvent);
                break;

			case 'item-unpin':
                $sCheckFuncName = 'isAllowedUnpin';
				$aCheckFuncParams = array($aEvent);
                break;

            case 'item-delete':
                $sCheckFuncName = 'isAllowedDelete';
				$aCheckFuncParams = array($aEvent);
                break;
        }

        if(!$sCheckFuncName || !method_exists($this->_oModule, $sCheckFuncName))
            return true;

        return call_user_func_array(array($this->_oModule, $sCheckFuncName), $aCheckFuncParams) === true;
    }
}

/** @} */
