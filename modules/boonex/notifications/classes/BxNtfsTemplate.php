<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Notifications Notifications
 * @ingroup     UnaModules
 *
 * @{
 */

class BxNtfsTemplate extends BxBaseModNotificationsTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    public function getInclude($iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;

        $sAppId = getParam($CNF['PARAM_PUSH_APP_ID']);
        if(empty($sAppId))
            return;

        $sShortName = getParam($CNF['PARAM_PUSH_SHORT_NAME']);
        $sSafariWebId = getParam($CNF['PARAM_PUSH_SAFARI_WEB_ID']);

        $this->addJs(array(
        	'https://cdn.onesignal.com/sdks/OneSignalSDK.js',
        	'push.js',
        ));

        $this->addJsTranslation(array(
            '_bx_ntfs_push_notification_request',
            '_bx_ntfs_push_notification_request_yes',
            '_bx_ntfs_push_notification_request_no'
        ));

        return $this->getJsCode('push', array(
            'sSiteName' => getParam('site_title'),
            'iProfileId' => $iProfileId,
            'sAppId' => $sAppId,
            'sShortName' => $sShortName,
            'sSafariWebId' => $sSafariWebId,
            'sNotificationUrl' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']),
        ));
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

    /**
     * Enter description here ...
     * @example Available keys are:
     * 1. owner_name and owner_link
     * 2. entry_caption and entry_url
     * 3. subentry_url and subentry_sample
     * 
     */
    public function getPost(&$aEvent, $aBrowseParams = array())
    {
    	$oModule = $this->getModule();

    	if((int)$aEvent['processed'] == 0)
    		$this->_processContent($aEvent);

    	$oPrivacy = $oModule->_oConfig->getPrivacyObject($aEvent['type'] . '_' . $aEvent['action']);
    	if($oPrivacy !== false && !$oPrivacy->check($aEvent['id'])) 
    		return '';

        $sService = 'check_allowed_with_content';
        if(BxDolRequest::serviceExists($aEvent['type'], $sService) && BxDolService::call($aEvent['type'], $sService, array('view', $aEvent['object_id'])) !== CHECK_ACTION_RESULT_ALLOWED)
            return '';

        list($sOwnerName, $sOwnerUrl, $sOwnerIcon) = $oModule->getUserInfo($aEvent['owner_id']);
        $bAuthorIcon = !empty($sOwnerIcon);

        $aEvent['content'] = unserialize($aEvent['content']);
        $aEvent['content']['owner_name'] = $sOwnerName;
        $aEvent['content']['owner_link'] = $sOwnerUrl;
        $aEvent['content']['owner_icon'] = $sOwnerIcon;
        if(!empty($aEvent['content']['entry_caption']))
            $aEvent['content']['entry_caption'] = bx_process_output($aEvent['content']['entry_caption'], BX_DATA_TEXT_MULTILINE);

        foreach($aEvent['content'] as $sKey => $sValue)
        	if(substr($sValue, 0, 1) == '_')
        		$aEvent['content'][$sKey] = _t($sValue);        

    	$aEvent['content_parsed'] = _t(!empty($aEvent['content']['lang_key']) ? $aEvent['content']['lang_key'] : $this->_getContentLangKey($aEvent));
    	$aEvent['content_parsed'] = $this->parseHtmlByContent($aEvent['content_parsed'], $aEvent['content'], array('{', '}'));

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
            'link' => $this->_getContentLink($aEvent),
            'content' => $aEvent['content_parsed'],
            'date' => bx_time_js($aEvent['date']),
        ));
    }

    public function getNotificationEmail(&$aEvent)
    {
        $this->getPost($aEvent);

        $bAuthorIcon = !empty($aEvent['content']['owner_icon']);
        return $this->parseHtmlByName('et_new_event.html', array(
        	'bx_if:show_icon' => array(
                'condition' => $bAuthorIcon,
                'content' => array(
                    'icon_url' => $aEvent['content']['owner_icon']
                )
            ),
            'bx_if:show_icon_empty' => array(
                'condition' => !$bAuthorIcon,
                'content' => array()
            ),
            'content_url' => $this->_getContentLink($aEvent),
            'content' => $aEvent['content_parsed'],
            'date' => bx_process_output($aEvent['date'], BX_DATA_DATETIME_TS),
        ));
    }

    public function getNotificationPush(&$aEvent)
    {
        $this->getPost($aEvent);

        $sMessage = preg_replace('/<\/?[a-zA-Z0-9=\'":;\(\)\s_-]+>/i', '"', $aEvent['content_parsed']);
		if($sMessage)
            $sMessage = BxTemplFunctions::getInstance()->getStringWithLimitedLength(html_entity_decode($sMessage), $this->_oConfig->getPushMaxLen());

        return array(
            'url' => $this->_getContentLink($aEvent),
            'message' => $sMessage,
            'icon' => !empty($aEvent['content']['owner_icon']) ? $aEvent['content']['owner_icon'] : ''
        );
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

    protected function _getContentLangKey(&$aEvent)
    {
    	return !empty($aEvent['subobject_id']) ? '_bx_ntfs_txt_subobject_added' : '_bx_ntfs_txt_object_added';
    }

    protected function _getContentLink(&$aEvent)
    {
        return !empty($aEvent['subobject_id']) && !empty($aEvent['content']['subentry_url']) ? $aEvent['content']['subentry_url'] : $aEvent['content']['entry_url'];
    }
}

/** @} */
