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
class BxTimelineMenuItemShare extends BxTemplMenu
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
        $bEvent = !empty($this->_aEvent) && is_array($this->_aEvent);
    	
    	//--- Repost item
    	$sRepostOnclick = '';
    	if($bEvent) {
        	$iOwnerId = $this->_oModule->getUserId(); //--- in whose timeline the content will be reposted
            $sType = $this->_aEvent['type'];
            $sAction = $this->_aEvent['action'];
            $iObjectId = $this->_oModule->_oConfig->isSystem($sType, $sAction) ? $this->_aEvent['object_id'] : $this->_aEvent['id'];

            $sRepostOnclick = $this->_oModule->serviceGetRepostJsClick($iOwnerId, $sType, $sAction, $iObjectId);
    	}

    	//--- Send item
    	$sEtSend = '';
    	if($bEvent) {
    	    $aEt = array(
    	        'name' => 'bx_timeline_send',
    	        'params' => array(
    	            'ViewLink' => $this->_oModule->_oConfig->getItemViewUrl($this->_aEvent)
    	        )
    	    );

    	    $sAetSend = urlencode(base64_encode(serialize($aEt))); 
    	}

    	$this->addMarkers(array(
        	'js_object_view' => $this->_oModule->_oConfig->getJsObject('view'),

        	'content_id' => $this->_iEvent,

    	    'js_onclick_repost' => $sRepostOnclick,
    		'et_send' => $sAetSend
    	));
    }

    public function getMenuItems ()
    {
        $aItems = parent::getMenuItems();

        $oSocial = BxDolMenu::getObjectInstance('sys_social_sharing');
        $oSocial->addMarkers(array(
            'id' => $this->_iEvent,
        	'module' => $this->_oModule->_oConfig->getName(),
        	'url' => $this->_oModule->_oConfig->getItemViewUrl($this->_aEvent),
        	'title' => $this->_aEvent['title'],
        ));

        return array_merge($aItems, $oSocial->getMenuItems());
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
        	case 'item-repost':
                $sCheckFuncName = 'isAllowedRepost';
				$aCheckFuncParams = array($this->_aEvent);
                break;

			case 'item-send':
                $sCheckFuncName = 'isAllowedSend';
				$aCheckFuncParams = array($this->_aEvent);
                break;
        }

        if(!$sCheckFuncName || !method_exists($this->_oModule, $sCheckFuncName))
            return true;

        return call_user_func_array(array($this->_oModule, $sCheckFuncName), $aCheckFuncParams) === true;
    }
}

/** @} */
