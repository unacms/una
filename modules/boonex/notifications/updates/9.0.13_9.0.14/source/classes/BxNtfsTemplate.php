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

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

class BxNtfsTemplate extends BxBaseModNotificationsTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    public function getInclude($bIncludeCss = true, $mixedIncludeJs = false)
    {
        if($bIncludeCss)
            $this->getCss();

        $sResult = '';
        if(is_string($mixedIncludeJs) && !empty($mixedIncludeJs)) {
            $this->getJs();
            
            $sResult = $this->getJsCode($mixedIncludeJs);
        }
            
        return $sResult;
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

    public function getSettingsBlock($sDeliveryType, $aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $iUserId = !empty($aParams['user_id']) ? (int)$aParams['user_id'] : bx_get_logged_profile_id();
        $sGrid = !empty($aParams['grid']) ? $aParams['grid'] : $CNF['OBJECT_GRID_SETTINGS_COMMON'];
        $oTemplate = !empty($aParams['template']) ? $aParams['template'] : $this;

        $oGrid = BxDolGrid::getObjectInstance($sGrid, $oTemplate);
        if(!$oGrid)
            return '';

        $oGrid->setDeliveryType($sDeliveryType);
        if(method_exists($oGrid, 'setUserId'))
            $oGrid->setUserId($iUserId);

        return $oGrid->getCode();
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

        $aEvent['content'] = unserialize($aEvent['content']);

        $sParam = 'perform_privacy_check';
        if(!isset($aBrowseParams[$sParam]) || $aBrowseParams[$sParam] === true) {
            $oPrivacyInt = BxDolPrivacy::getObjectInstance($this->_oConfig->getObject('privacy_view'));
            if(!$oPrivacyInt->check($aEvent['id']))
                return '';

            $oPrivacyExt = $oModule->_oConfig->getPrivacyObject($aEvent['type'] . '_' . $aEvent['action']);
            if($oPrivacyExt !== false && !$oPrivacyExt->check($aEvent['id']))
                return '';

            $sService = 'check_allowed_with_content';
            if(BxDolRequest::serviceExists($aEvent['type'], $sService) && BxDolService::call($aEvent['type'], $sService, array('view', $this->_getContentObjectId($aEvent))) !== CHECK_ACTION_RESULT_ALLOWED)
                return '';
        }

        $oOwner = $oModule->getObjectUser($aEvent['owner_id']);

        $aEvent['content']['owner_name'] = strmaxtextlen($oOwner->getDisplayName(), $this->_oConfig->getOwnerNameMaxLen());
        $aEvent['content']['owner_link'] = $oOwner->getUrl();
        $aEvent['content']['owner_icon'] = $oOwner->getThumb();

        if(!empty($aEvent['content']['entry_caption'])) {
            $sEntryCaption = bx_process_output($aEvent['content']['entry_caption'], BX_DATA_TEXT);
            if(get_mb_substr($sEntryCaption, 0, 1) == '_')
                $sEntryCaption = _t($sEntryCaption);

            $aEvent['content']['entry_caption'] = strmaxtextlen($sEntryCaption, $this->_oConfig->getContentMaxLen());
        }

        $iObjectOwner = (int)$aEvent['object_owner_id'];
        if(empty($iObjectOwner) || !empty($aEvent['content']['entry_author']))
            $iObjectOwner = (int)$aEvent['content']['entry_author'];

        $oObjectOwner = $oModule->getObjectUser($iObjectOwner);
        if($oObjectOwner) {
            $aEvent['content']['object_owner_name'] = strmaxtextlen($oObjectOwner->getDisplayName(), $this->_oConfig->getOwnerNameMaxLen());
            $aEvent['content']['object_owner_link'] = $oObjectOwner->getUrl();
            $aEvent['content']['object_owner_icon'] = $oObjectOwner->getThumb();
        }

        foreach($aEvent['content'] as $sKey => $sValue)
            if(substr($sValue, 0, 1) == '_')
                    $aEvent['content'][$sKey] = _t($sValue);        

    	$bEventParsed = false;
        bx_alert($this->_oConfig->getName(), 'get_notification', 0, 0, array('event' => &$aEvent, 'event_parsed' => &$bEventParsed, 'owner' => &$oOwner));
        if (!$bEventParsed){
    	    $aEvent['content_parsed'] = _t(!empty($aEvent['content']['lang_key']) ? $aEvent['content']['lang_key'] : $this->_getContentLangKey($aEvent));
    	    $aEvent['content_parsed'] = $this->parseHtmlByContent($aEvent['content_parsed'], $aEvent['content'], array('{', '}'));
        }

        return $this->parseHtmlByName('event.html', array (
            'html_id' => $this->_oConfig->getHtmlIds('view', 'event') . $aEvent['id'],
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'js_object' => $this->_oConfig->getJsObject('view'),
            'class' => !empty($aBrowseParams['last_read']) && $aEvent['id'] > $aBrowseParams['last_read'] ? ' bx-def-color-bg-box-active' : '', 
            'id' => $aEvent['id'],
            'author_unit' => $this->_isInContext($aEvent) && $oObjectOwner ? $oObjectOwner->getUnit(0, array('template' => 'unit_wo_info_links')) : $oOwner->getUnit(0, array('template' => 'unit_wo_info_links')),
            'link' => $this->_getContentLink($aEvent),
            'content' => $aEvent['content_parsed'],
            'date' => bx_time_js($aEvent['date']),
        ));
    }

    public function getNotificationEmail(&$aEvent)
    {
        $sEvent = $this->getPost($aEvent, array('perform_privacy_check' => false));
        if(empty($sEvent) || empty($aEvent['content_parsed']))
            return false;

        return $this->parseHtmlByName('et_new_event.html', array(
        	'icon_url' => !empty($aEvent['content']['owner_icon']) ? $aEvent['content']['owner_icon'] : $this->getIconUrl('std-icon.svg'),
            'content_url' => $this->_getContentLink($aEvent),
            'content' => $aEvent['content_parsed'],
            'date' => bx_process_output($aEvent['date'], BX_DATA_DATETIME_TS),
        ));
    }

    public function getNotificationPush(&$aEvent)
    {
        $sEvent = $this->getPost($aEvent, array('perform_privacy_check' => false));
        if(empty($sEvent) || empty($aEvent['content_parsed']))
            return false;

        $sMessage = preg_replace('/<\/?[a-zA-Z0-9=\'":;\(\)\s_-]+>/i', '"', $aEvent['content_parsed']);
		if($sMessage)
            $sMessage = BxTemplFunctions::getInstance()->getStringWithLimitedLength(html_entity_decode($sMessage), $this->_oConfig->getPushMaxLen());

        if(empty($sMessage))
            return false;

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

    protected function _getContentObjectId(&$aEvent)
    {
        return !empty($aEvent['content']['object_id']) ? (int)$aEvent['content']['object_id'] : (int)$aEvent['object_id'];
    }

    protected function _getContentLangKey(&$aEvent)
    {
        if(!empty($aEvent['subobject_id']))
            return '_bx_ntfs_txt_subobject_added';

        if($this->_isInContext($aEvent))
            return '_bx_ntfs_txt_object_added_in_context';

    	return '_bx_ntfs_txt_object_added';
    }

    protected function _getContentLink(&$aEvent)
    {
        return !empty($aEvent['subobject_id']) && !empty($aEvent['content']['subentry_url']) ? $aEvent['content']['subentry_url'] : $aEvent['content']['entry_url'];
    }

    protected function _isInContext(&$aEvent)
    {
        return (int)$aEvent['object_privacy_view'] < 0;
    }
}

/** @} */
