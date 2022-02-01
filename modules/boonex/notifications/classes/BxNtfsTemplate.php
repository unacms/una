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
            $this->getAddedCss();

        $sResult = '';
        if(is_string($mixedIncludeJs) && !empty($mixedIncludeJs)) {
            $this->getAddedJs();
            
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

        if(!empty($aEvent['content']) && is_string($aEvent['content']))
            $aEvent['content'] = unserialize($aEvent['content']);

    	if((int)$aEvent['processed'] == 0)
            $this->_processContent($aEvent);

        if((int)$aEvent['processed'] == 0 || empty($aEvent['content']))
            return '';

        $sParamCheck = 'perform_privacy_check';
        if(!isset($aBrowseParams[$sParamCheck]) || $aBrowseParams[$sParamCheck] === true) {
            $sParamCheckFor = 'perform_privacy_check_for';
            $iViewerId = !empty($aBrowseParams[$sParamCheckFor]) ? (int)$aBrowseParams[$sParamCheckFor] : 0;

            $oPrivacyInt = BxDolPrivacy::getObjectInstance($this->_oConfig->getObject('privacy_view'));
            if(!$oPrivacyInt->check($aEvent['id'], $iViewerId))
                return '';

            $oPrivacyExt = $this->_oConfig->getPrivacyObject($aEvent['type'] . '_' . $aEvent['action']);
            if($oPrivacyExt !== false && !$oPrivacyExt->check($aEvent['id'], $iViewerId))
                return '';

            $sSrvModule = $this->_oConfig->getContentModule($aEvent);
            $sSrvMethod = 'check_allowed_with_content_for_profile';
            if($sSrvModule && BxDolRequest::serviceExists($sSrvModule, $sSrvMethod) && BxDolService::call($sSrvModule, $sSrvMethod, array('view', $this->_oConfig->getContentObjectId($aEvent), $iViewerId)) !== CHECK_ACTION_RESULT_ALLOWED)
                return '';
        }

        $bShowRealProfile = !isset($aBrowseParams['show_real_profile']) || $aBrowseParams['show_real_profile'] === true;

        $oOwner = $oModule->getObjectUser($aEvent['owner_id']);
        if(!$bShowRealProfile && $oOwner instanceof BxDolProfileAnonymous)
            $oOwner->setShowRealProfile($bShowRealProfile);

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
        if(empty($iObjectOwner) && !empty($aEvent['content']['entry_author']))
            $iObjectOwner = (int)$aEvent['content']['entry_author'];

        $oObjectOwner = $oModule->getObjectUser($iObjectOwner);
        if($oObjectOwner) {
            if(!$bShowRealProfile && $oObjectOwner instanceof BxDolProfileAnonymous)
                $oObjectOwner->setShowRealProfile($bShowRealProfile);

            $aEvent['content']['object_owner_name'] = strmaxtextlen($oObjectOwner->getDisplayName(), $this->_oConfig->getOwnerNameMaxLen());
            $aEvent['content']['object_owner_link'] = $oObjectOwner->getUrl();
            $aEvent['content']['object_owner_icon'] = $oObjectOwner->getThumb();
        }

        foreach($aEvent['content'] as $sKey => $sValue) {
            if(!is_string($sValue) || substr($sValue, 0, 1) != '_') 
                continue;

            $aCallParams = array($sValue);

            $sKeyParams = $sKey . '_params';
            if(isset($aEvent['content'][$sKeyParams]) && is_array($aEvent['content'][$sKeyParams])) {
                foreach($aEvent['content'][$sKeyParams] as $iParamIndex => $sParamValue)
                    if(is_string($sParamValue) && substr($sParamValue, 0, 1) == '_')
                        $aEvent['content'][$sKeyParams][$iParamIndex] = _t($sParamValue);

                $aCallParams = array_merge($aCallParams, $aEvent['content'][$sKeyParams]);
                unset($aEvent['content'][$sKeyParams]);
            }

            $aEvent['content'][$sKey] = call_user_func_array('_t', $aCallParams);
        }

    	$bEventParsed = false;
        
        $sOwnerUnit = $this->_isInContext($aEvent) && $oObjectOwner ? $oObjectOwner->getUnit(0, array('template' => 'unit_wo_info_links')) : $oOwner->getUnit(0, array('template' => 'unit_wo_info_links'));
        
        bx_alert($this->_oConfig->getName(), 'get_notification', 0, 0, array('event' => &$aEvent, 'event_parsed' => &$bEventParsed, 'owner' => &$oOwner, 'owner_unit' => &$sOwnerUnit, 'browse_params' => $aBrowseParams));
        
        if(!$bEventParsed) {
            $mLk = '';
            if(!empty($aEvent['content']['lang_key'])) {
                $mLk = $aEvent['content']['lang_key'];
                if(!is_string($mLk) && !(is_array($mLk) && isset($mLk['site'], $mLk['email'], $mLk['push'])))
                    $mLk = '';
            }

            if(empty($mLk))
                $mLk = $this->_getContentLangKey($aEvent);

            if(is_array($mLk))
                foreach($mLk as $sDeliveryType => $sLangKey)
                    $aEvent['content_parsed'][$sDeliveryType] = $this->_parseContentLangKey($sLangKey, $aEvent);
            else
                $aEvent['content_parsed'] = $this->_parseContentLangKey($mLk, $aEvent);
        }

        // returns parsed content for React Jot
        if(isset($aBrowseParams['return_parsed_content']))
            return $aEvent;

        return $this->parseHtmlByName('event.html', array (
            'html_id' => $this->_oConfig->getHtmlIds('view', 'event') . $aEvent['id'],
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'js_object' => $this->_oConfig->getJsObject('view'),
            'class' => !empty($aBrowseParams['last_read']) && $aEvent['id'] > $aBrowseParams['last_read'] ? ' bx-def-color-bg-box-active' : '', 
            'id' => $aEvent['id'],
            'author_unit' => $sOwnerUnit,
            'link' => $this->_getContentLink($aEvent),
            'content' => is_array($aEvent['content_parsed']) && isset($aEvent['content_parsed']['site']) ? $aEvent['content_parsed']['site'] : $aEvent['content_parsed'],
            'date' => bx_time_js($aEvent['date']),
        ));
    }

    public function getNotificationEmail($iRecipient, &$aEvent)
    {
        $sEvent = $this->getPost($aEvent, array('perform_privacy_check_for' => $iRecipient, 'show_real_profile' => false));
        if(empty($sEvent) || empty($aEvent['content_parsed']))
            return false;

        $aContent = &$aEvent['content'];
        return array(
            'content' => $this->parseHtmlByName('et_new_event.html', array(
                'icon_url' => !empty($aContent['owner_icon']) ? $aContent['owner_icon'] : $this->getIconUrl('std-icon.svg'),
                'content_url' => $this->_getContentLink($aEvent),
                'content' => is_array($aEvent['content_parsed']) && isset($aEvent['content_parsed']['email']) ? $aEvent['content_parsed']['email'] : $aEvent['content_parsed'],
                'date' => bx_process_output($aEvent['date'], BX_DATA_DATE_TS),
            )),
            'settings' => !empty($aContent['settings']['email']) ? $aContent['settings']['email'] : array()
        );
    }

    public function getNotificationPush($iRecipient, &$aEvent)
    {
        $sEvent = $this->getPost($aEvent, array('perform_privacy_check_for' => $iRecipient, 'show_real_profile' => false));
        if(empty($sEvent) || empty($aEvent['content_parsed']))
            return false;

        $sMessage = is_array($aEvent['content_parsed']) && isset($aEvent['content_parsed']['push']) ? $aEvent['content_parsed']['push'] : $aEvent['content_parsed'];
        $sMessage = preg_replace('/<\/?[a-zA-Z0-9=\'":;\(\)\s_-]+>/i', '"', $sMessage);
        if($sMessage)
            $sMessage = BxTemplFunctions::getInstance()->getStringWithLimitedLength(html_entity_decode($sMessage), $this->_oConfig->getPushMaxLen());

        if(empty($sMessage))
            return false;

        $aContent = &$aEvent['content'];
        return array(
            'content' => array(
                'url' => $this->_getContentLink($aEvent),
                'message' => $sMessage,
                'icon' => !empty($aContent['owner_icon']) ? $aContent['owner_icon'] : ''
            ),
            'settings' => !empty($aContent['settings']['push']) ? $aContent['settings']['push'] : array()
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

        bx_alert($this->_oConfig->getName(), 'get_content', 0, 0, array(
            'event' => $aEvent,
            'override_result' => &$aContent
        ));

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

        if(!empty($aEvent['content'])) {
            if(is_string($aEvent['content']))
                $aEvent['content'] = unserialize($aEvent['content']);

            if(is_array($aEvent['content']))
                $aEvent['content'] = array_merge($aEvent['content'], $aContent);
        }
        else
            $aEvent['content'] = $aContent;

        $aEvent['processed'] = 1;

        $aSet = array_merge($aSet, array(
            'content' => serialize($aEvent['content']), 
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
        if(!empty($aEvent['subobject_id']))
            return '_bx_ntfs_txt_subobject_added';

        if($this->_isInContext($aEvent))
            return '_bx_ntfs_txt_object_added_in_context';

        $sKey = '';
        switch($aEvent['action']) {
            case 'publish_failed':
                $sKey = '_bx_ntfs_txt_object_publish_failed';
                break;

            case 'publish_succeeded':
                $sKey = '_bx_ntfs_txt_object_publish_succeeded';
                break;

            default:
                $sKey = '_bx_ntfs_txt_object_added';
        }

    	return $sKey;
    }

    protected function _parseContentLangKey($sLangKey, &$aEvent)
    {
        $aExclude = array(
            'lang_key' => 1,
            'settings' => 1
        );

        return $this->parseHtmlByContent(_t($sLangKey), array_diff_key($aEvent['content'], $aExclude), array('{', '}'));
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
