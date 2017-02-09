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
class BxTimelineMenuItemManage extends BxTemplMenu
{
    protected $_oModule;

	protected $_iEvent;
	protected $_aEvent;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_timeline');

        if(bx_get('content_id') !== false)
            $this->setEventId((int)bx_get('content_id'));
    }

    public function setEventId($iEventId)
    {
    	$this->_iEvent = $iEventId;
    	$this->_aEvent = $this->_oModule->_oDb->getEvents(array('browse' => 'id', 'value' => $this->_iEvent));

    	$this->addMarkers(array(
            'js_object_view' => $this->_oModule->_oConfig->getJsObject('view'),

        	'content_id' => $this->_iEvent,
        ));
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

		if(empty($this->_aEvent) || !is_array($this->_aEvent))
			return false;

        $sCheckFuncName = '';
        $aCheckFuncParams = array();
        switch ($a['name']) {
        	case 'item-pin':
                $sCheckFuncName = 'isAllowedPin';
				$aCheckFuncParams = array($this->_aEvent);
                break;

			case 'item-unpin':
                $sCheckFuncName = 'isAllowedUnpin';
				$aCheckFuncParams = array($this->_aEvent);
                break;

            case 'item-delete':
                $sCheckFuncName = 'isAllowedDelete';
				$aCheckFuncParams = array($this->_aEvent);
                break;
        }

        if(!$sCheckFuncName || !method_exists($this->_oModule, $sCheckFuncName))
            return true;

        return call_user_func_array(array($this->_oModule, $sCheckFuncName), $aCheckFuncParams) === true;
    }
}

/** @} */
