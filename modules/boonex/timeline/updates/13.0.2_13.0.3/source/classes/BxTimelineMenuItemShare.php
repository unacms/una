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
    protected $_sModule;
    protected $_oModule;

    protected $_iEvent;
    protected $_aEvent;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_timeline';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $this->_oModule->_oTemplate);

        $iContentId = 0;
        if(bx_get('content_id') !== false)
            $iContentId = bx_process_input(bx_get('content_id'), BX_DATA_INT);

        $aBrowseParams = array('name' => '', 'view' => '', 'type' => '');
        foreach($aBrowseParams as $sKey => $sValue)
            if(bx_get($sKey) !== false)
                $aBrowseParams[$sKey] = $this->_oModule->_oConfig->prepareParam($sKey);

        $this->setEventById($iContentId, $aBrowseParams);
    }

    public function setEventById($iEventId, $aBrowseParams = array())
    {
    	$this->_iEvent = $iEventId;
    	$this->_aEvent = $this->_oModule->_oDb->getEvents(array('browse' => 'id', 'value' => $this->_iEvent));

        $this->_setBrowseParams($aBrowseParams);

        $sRepostOnclick = $sEtSend = '';
        if(!empty($this->_aEvent) && is_array($this->_aEvent)) {
            $sType = $this->_aEvent['type'];
            $sAction = $this->_aEvent['action'];

            $this->_aEvent['url'] = $this->_oModule->_oConfig->getItemViewUrl($this->_aEvent);
            if($this->_oModule->_oConfig->isSystem($sType, $sAction)) {
                $aResult = $this->_oModule->_oConfig->getSystemData($this->_aEvent);
                if(!empty($aResult['url']))
                    $this->_aEvent['url'] = $aResult['url'];
            }

            //--- Repost and 'Repost To' items
            $iOwnerId = $this->_oModule->getUserId(); //--- in whose timeline the content will be reposted
            $iObjectId = $this->_oModule->_oConfig->isSystem($sType, $sAction) ? $this->_aEvent['object_id'] : $this->_aEvent['id'];
            $sRepostOnclick = $this->_oModule->serviceGetRepostJsClick($iOwnerId, $sType, $sAction, $iObjectId);
            $sRepostWithOnclick = $this->_oModule->serviceGetRepostWithJsClick($iOwnerId, $sType, $sAction, $iObjectId);
            $sRepostToOnclick = $this->_oModule->serviceGetRepostToJsClick($iOwnerId, $sType, $sAction, $iObjectId);

            //--- Send item
    	    $sAetSend = urlencode(base64_encode(serialize(array(
    	        'name' => 'bx_timeline_send',
    	        'params' => array(
    	            'ViewLink' => $this->_aEvent['url']
    	        )
    	    )))); 
        }

    	$this->addMarkers(array(
            'content_id' => $this->_iEvent,
            'content_url' => $this->_aEvent['url'],

            'repost_onclick' => $sRepostOnclick,
            'repost_with_onclick' => $sRepostWithOnclick,
            'repost_to_onclick' => $sRepostToOnclick,
            'et_send' => $sAetSend
    	));
    }

    public function getMenuItems ()
    {
        $aItems = parent::getMenuItems();

        $aMarkers = array(
            'id' => $this->_iEvent,
            'module' => $this->_oModule->_oConfig->getName(),
            'url' => $this->_aEvent['url'],
            'title' => $this->_aEvent['title'],
            'img_url' => '',
            'img_url_encoded' => ''
        );

        $aEventData = $this->_oModule->_oTemplate->getDataCached($this->_aEvent);
        if(!empty($aEventData['content']['images']) && is_array($aEventData['content']['images'])) {
            $aImage = array_shift($aEventData['content']['images']);

            $sImgUrl = isset($aImage['src_medium']) ? $aImage['src_medium'] : $aImage['src'];;
            if(!empty($sImgUrl))
                $aMarkers = array_merge($aMarkers, array(
                    'img_url' => $sImgUrl,
                    'img_url_encoded' => rawurlencode($sImgUrl),
                ));
        }

        if(($oSocial = BxDolMenu::getObjectInstance('sys_social_sharing')) !== false) {
            $oSocial->addMarkers($aMarkers);

            $aItems = array_merge($aItems, $oSocial->getMenuItems());
        }

        return $aItems;
    }

    protected function _setBrowseParams($aBrowseParams = array())
    {
        $aMarkers = array();
        foreach($aBrowseParams as $sKey => $mixedValue)
            if(!is_array($aMarkers))
                $aMarkers[$sKey] = $mixedValue;

        $this->addMarkers($aMarkers);
        $this->addMarkers(array(
            'js_object_view' => $this->_oModule->_oConfig->getJsObjectView($aBrowseParams)
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
