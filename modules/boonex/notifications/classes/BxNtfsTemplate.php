<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Notifications Notifications
 * @ingroup     TridentModules
 *
 * @{
 */

class BxNtfsTemplate extends BxBaseModNotificationsTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    public function getViewBlock($aParams)
    {
        return $this->parseHtmlByName('block_view.html', array(
        	'html_id_view_block' => $this->_oConfig->getHtmlIds('view', 'block'),
        	'html_id_events' => $this->_oConfig->getHtmlIds('view', 'events'),
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'content' => $this->getPosts($aParams),
            'js_content' => $this->getJsCode('view', array(
        		'oRequestParams' => array(
	                'type' => $aParams['type'],
	                'owner_id' => $aParams['owner_id'],
	                'start' => $aParams['start'],
	                'per_page' => $aParams['per_page'],
	                'modules' => $aParams['modules']
       			)
            ))
        ));
    }

    public function getPosts($aParams)
    {
    	$sJsObject = $this->_oConfig->getJsObject('view');

    	$aParamsDb = $aParams;
    	$aParamsDb['per_page'] = 3 * $aParamsDb['per_page'];

        $aEvents = $this->_oDb->getEvents($aParamsDb);
        if(empty($aEvents))
        	return $this->getEmpty();

        $aTmplVarsEvents = array();
        foreach($aEvents as $aEvent) {
            $sEvent = $this->getPost($aEvent, $aParams);
            if(empty($sEvent))
                continue;

            $aTmplVarsEvents[] = array('event' => $sEvent);
            if(count($aTmplVarsEvents) >= ($aParams['per_page'] + 1))
            	break;
        }

        $oPaginate = new BxTemplPaginate(array(
        	'start' => $aParams['start'],
            'per_page' => $aParams['per_page'],
        	'page_url' => $this->_oConfig->getViewUrl(),
        	'on_change_page' => $sJsObject . ".changePage(this, {start}, {per_page})"
        ));
        $oPaginate->setNumFromDataArray($aTmplVarsEvents);

        return $this->parseHtmlByName('events.html', array(
        	'style_prefix' => $this->_oConfig->getPrefix('style'),
        	'bx_repeat:events' => $aTmplVarsEvents,
        	'paginate' => $oPaginate->getSimplePaginate()
        ));
    }

    public function getPost(&$aEvent, $aBrowseParams = array())
    {
    	$oModule = $this->getModule();

    	if((int)$aEvent['processed'] == 0)
    		$this->_processContent($aEvent);

    	$oPrivacy = $oModule->_oConfig->getPrivacyObject($aEvent['type'] . '_' . $aEvent['action']);
    	if($oPrivacy !== false && !$oPrivacy->check($aEvent['id'])) 
    		return '';

        list($sOwnerName, $sOwnerUrl, $sOwnerIcon) = $oModule->getUserInfo($aEvent['owner_id']);
        $bAuthorIcon = !empty($sOwnerIcon);

        $aEvent['content'] = unserialize($aEvent['content']);
        $aEvent['content']['owner_name'] = $sOwnerName;
        $aEvent['content']['owner_link'] = $sOwnerUrl;
        foreach($aEvent['content'] as $sKey => $sValue)
        	if(substr($sValue, 0, 1) == '_')
        		$aEvent['content'][$sKey] = _t($sValue);

    	$sContent = _t(!empty($aEvent['content']['lang_key']) ? $aEvent['content']['lang_key'] : $this->_getLangKey($aEvent));
    	$sContent = $this->parseHtmlByContent($sContent, $aEvent['content'], array('{', '}'));

        return $this->parseHtmlByName('event.html', array (
        	'html_id' => $this->_oConfig->getHtmlIds('view', 'event') . $aEvent['id'],
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'js_object' => $this->_oConfig->getJsObject('view'),
            'id' => $aEvent['id'],
            'bx_if:show_icon' => array(
                'condition' => $bAuthorIcon,
                'content' => array(
                    'author_icon' => $sOwnerIcon
                )
            ),
            'bx_if:show_icon_empty' => array(
                'condition' => !$bAuthorIcon,
                'content' => array()
            ),
            'content' => $sContent,
            'date' => bx_time_js($aEvent['date']),
        ));
    }

    public function getEmpty($bVisible = true)
    {
        return $this->parseHtmlByName('empty.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'visible' => $bVisible ? 'block' : 'none',
            'content' => MsgBox(_t('_bx_ntfs_txt_msg_no_results'))
        ));
    }

    protected function _processContent(&$aEvent)
    {
    	$aContent = $this->_getContent($aEvent);
		if(empty($aContent) || !is_array($aContent)) 
			return;

		$aSet = array();
		if(!empty($aContent['entry_author'])) {
			$aSet['object_owner_id'] = (int)$aContent['entry_author'];
			unset($aContent['entry_author']);
		}

		if(!empty($aContent['entry_privacy'])) {
			$aSet['allow_view_event_to'] = $aContent['entry_privacy'];
			$aEvent['allow_view_event_to'] = $aContent['entry_privacy'];
			unset($aContent['entry_privacy']);
		}

		$aEvent['content'] = serialize($aContent);
		$aSet = array_merge($aSet, array(
			'content' => $aEvent['content'], 
			'processed' => 1
		));

		$this->_oDb->updateEvent($aSet, array('id' => $aEvent['id']));
		return;
    }

    protected function _getContent(&$aEvent)
    {
        $sHandler = $aEvent['type'] . '_' . $aEvent['action'];
        if(!$this->_oConfig->isHandler($sHandler))
            return array();

        $aHandler = $this->_oConfig->getHandlers($sHandler);
        if(!empty($aHandler['module_name']) && !empty($aHandler['module_class']) && !empty($aHandler['module_method']))
        	return BxDolService::call($aHandler['module_name'], $aHandler['module_method'], array($aEvent), $aHandler['module_class']);

        $sMethod = 'display' . bx_gen_method_name($aHandler['alert_unit'] . '_' . $aHandler['alert_action']);
		if(!method_exists($this, $sMethod))
        	return array();

		return $this->$sMethod($aEvent);
    }

    protected function _getLangKey(&$aEvent)
    {
    	$sResult = '_bx_ntfs_txt_object_added';

    	if(!empty($aEvent['subobject_id']))
    		$sResult = '_bx_ntfs_txt_subobject_added_' . (!empty($aEvent['content']['subentry_url']) ? 'link' : 'text');

    	return $sResult;
    }
}

/** @} */
