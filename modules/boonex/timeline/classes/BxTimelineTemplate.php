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

class BxTimelineTemplate extends BxBaseModNotificationsTemplate
{
    protected static $_sTmplContentItemItem;
    protected static $_sTmplContentItemOutline;
    protected static $_sTmplContentItemTimeline;
    protected static $_sTmplContentItemSearch;
    protected static $_sTmplContentTypePost;
    protected static $_sTmplContentTypeRepost;

    protected $_bShowTimelineDividers;

    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);

        $this->_bShowTimelineDividers = false;
    }

    public function getCssJs($bDynamic = false)
    {
    	parent::getCssJs($bDynamic);

        $this->addCss(array(
        	BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'flickity/|flickity.css',
        	BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'emoji/css/|emoji.css',
            'jquery-ui/jquery-ui.css',
			'cmts.css',
            'post.css',
            'repost.css',
        ));

        $this->addJs(array(
            'jquery-ui/jquery-ui.custom.min.js',
            'jquery.form.min.js',
            'jquery.ba-resize.min.js',
        	'emoji/js/util.js',
        	'emoji/js/config.js',
            'emoji/js/emoji-picker.js',
        	'autosize.min.js',
            'masonry.pkgd.min.js',
            'modernizr.min.js',
        	'flickity/flickity.pkgd.min.js',
			'emoji/js/jquery.emojiarea.js',
            'embedly-player.min.js',
            'BxDolCmts.js',            
            'post.js',
            'repost.js',
        ));
    }

    public function getPostBlock($iOwnerId, $aParams = array())
    {
        $aForm = $this->getModule()->getFormPost($aParams);

        return $this->parseHtmlByName('block_post.html', array (
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'js_object' => $this->_oConfig->getJsObject('post'),
            'js_content' => $this->getJsCode('post', array(
        		'sVideosAutoplay' => $this->_oConfig->getVideosAutoplay(),
            	'oRequestParams' => array(
            		'type' => isset($aParams['type']) ? $aParams['type'] : BX_TIMELINE_TYPE_DEFAULT, 
            		'owner_id' => $iOwnerId
                )
        	)),
            'form' => $aForm['form']
        ));
    }

    public function getViewBlock($aParams)
    {
        $oModule = $this->getModule();

        //--- Add live update
		$oModule->actionResumeLiveUpdate($aParams['type'], $aParams['owner_id']);

		$sServiceCall = BxDolService::getSerializedService($this->_oConfig->getName(), 'get_live_updates', array($aParams['type'], $aParams['owner_id'], $oModule->getUserId(), '{count}'));
		$sLiveUpdatesCode = BxDolLiveUpdates::getInstance()->add($this->_oConfig->getLiveUpdateKey($aParams['type'], $aParams['owner_id']), 1, $sServiceCall);
		//--- Add live update

        list($sContent, $sLoadMore, $sBack, $sEmpty) = $this->getPosts($aParams);

        return $sLiveUpdatesCode . $this->parseHtmlByName('block_view.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
        	'html_id' => $this->_oConfig->getHtmlIds('view', 'main_' . $aParams['view']),
            'view' => $aParams['view'],
            'back' => $sBack,
            'empty' => $sEmpty, 
            'content' => $sContent,
            'load_more' =>  $sLoadMore,
            'show_more' => $this->_getShowMore($aParams),
        	'view_image_popup' => $this->_getImagePopup($aParams),
            'js_content' => $this->getJsCode('view', array(
                'sVideosAutoplay' => $this->_oConfig->getVideosAutoplay(),
            	'oRequestParams' => array(
	                'type' => $aParams['type'],
	                'owner_id' => $aParams['owner_id'],
	                'start' => $aParams['start'],
	                'per_page' => $aParams['per_page'],
	                'filter' => $aParams['filter'],
	                'modules' => $aParams['modules'],
	                'timeline' => $aParams['timeline'],
        		)
            )) . $this->getJsCode('repost')
        ));
    }

    public function getSearchBlock($sContent)
    {
        $oModule = $this->getModule();
        $aParams = $oModule->getParams(BX_TIMELINE_VIEW_SEARCH);

        return $this->parseHtmlByName('block_search.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
        	'html_id' => $this->_oConfig->getHtmlIds('view', 'main_' . $aParams['view']),
            'view' => $aParams['view'],
            'content' => $sContent,
        	'view_image_popup' => $this->_getImagePopup($aParams),
            'js_content' => $this->getJsCode('view', array(
            	'oRequestParams' => array(
	                'type' => $aParams['type'],
	                'owner_id' => $aParams['owner_id'],
	                'start' => $aParams['start'],
	                'per_page' => $aParams['per_page'],
	                'filter' => $aParams['filter'],
	                'modules' => $aParams['modules'],
	                'timeline' => $aParams['timeline'],
        		)
            ))
        ));
    }

    public function getItemBlock($iId)
    {
        $CNF = $this->_oConfig->CNF;

        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));
        if(empty($aEvent))
            return array('content' => MsgBox(_t('_Empty')), 'designbox_id' => 13);

        $iProfile = (int)$aEvent[$CNF['FIELD_OWNER_ID']];
        if(!empty($iProfile)) {
            $oProfile = BxDolProfile::getInstance($iProfile);
            if(!$oProfile)
                return array('content' => MsgBox(_t('_Empty')), 'designbox_id' => 13);

            $mixedResult = $oProfile->checkAllowedProfileView();
            if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
                return array('content' => MsgBox($mixedResult), 'designbox_id' => 13);
        }

        $aParams = array(
        	'view' => BX_TIMELINE_VIEW_ITEM, 
        	'type' => BX_TIMELINE_TYPE_ITEM
        );
        $sContent = $this->getPost($aEvent, $aParams);

        $oModule = $this->getModule();
        if($oModule->isAllowedViewCounter($aEvent) !== true)
            return array('content' => MsgBox(_t('_Access denied')), 'designbox_id' => 13);

        if(!$this->_oConfig->isSystem($aEvent['type'], $aEvent['action'])) {
            $mixedViews = $oModule->getViewsData($aEvent['views']);
            if($mixedViews !== false) {
                list($sSystem, $iObjectId) = $mixedViews;
                $oModule->getViewObject($sSystem, $iObjectId)->doView();
            }
        }

        list($sAuthorName) = $oModule->getUserInfo($aEvent['object_owner_id']);

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageHeader(strip_tags($sAuthorName . ' ' . _t($aEvent['sample_action'], _t($aEvent['sample']))));
        $oTemplate->setPageDescription(strip_tags($aEvent['title']));

        $oMetatags = BxDolMetatags::getObjectInstance($this->_oConfig->getObject('metatags'));
        if($oMetatags)
            $oMetatags->addPageMetaInfo($aEvent[$CNF['FIELD_ID']]);

        $sReferrer = '';
        if(isset($_SERVER['HTTP_REFERER']) && mb_stripos($_SERVER['HTTP_REFERER'], BX_DOL_URL_ROOT) === 0)
            $sReferrer = $_SERVER['HTTP_REFERER'];
        else 
            $sReferrer = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($this->_oConfig->CNF['URL_HOME']);

        return array('content' => $this->parseHtmlByName('block_item.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
        	'html_id' => $this->_oConfig->getHtmlIds('view', 'main_item'),
        	'content' => $sContent,
        	'view_image_popup' => $this->_getImagePopup($aParams),
            'js_content' => $this->getJsCode('view', array(
                'sReferrer' => $sReferrer
            )) . $this->getJsCode('repost')
        )));
    }

    /**
     * Get event's content.
     * @param integer $iId - event ID.
     * @param string $sMode - 'photo' is only one mode which is available for now.
     */
    public function getItemBlockContent($iId, $sMode) {
        $CNF = $this->_oConfig->CNF;
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));
        if(empty($aEvent))
            return '';

        $aTmplVars = array(
        	'style_prefix' => $sStylePrefix,
            'bx_if:show_image' => array(
                'condition' => false,
                'content' => array()
            )
        );

        switch($sMode) {
            case 'photo':
                $aTmplVars['bx_if:show_image']['condition'] = true;
                $aTmplVars['bx_if:show_image']['content'] = array(
                    'style_prefix' => $sStylePrefix,
                    'src' => base64_decode(bx_process_input(bx_get('src'))),
                );
                break;
        }

        return $this->parseHtmlByName('block_item_content.html', $aTmplVars);
    }

    public function getItemBlockInfo($iId) {
        $CNF = $this->_oConfig->CNF;

        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));
        if(empty($aEvent))
            return '';

        $aResult = $this->getData($aEvent);
        if($aResult === false)
            return '';

        list($sAuthorName, $sAuthorUrl, $sAuthorIcon, $sAuthorUnit) = $this->getModule()->getUserInfo($aResult['owner_id']);

        return $this->parseHtmlByName('block_item_info.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'author' => $sAuthorUnit
        ));
    }

    public function getItemBlockComments($iId) {
        $CNF = $this->_oConfig->CNF;

        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));
        if(empty($aEvent))
            return '';

        $aResult = $this->getData($aEvent);
        if($aResult === false)
            return '';
            
        return $this->_getComments($aResult['comments']);
    }

    public function getUnit(&$aEvent, $aBrowseParams = array())
    {
        $oModule = $this->getModule();

        if(empty($aBrowseParams) || !is_array($aBrowseParams))
            $aBrowseParams = $oModule->getParams(BX_TIMELINE_VIEW_SEARCH);

        return $this->getPost($aEvent, $aBrowseParams);
    }

    public function getPost(&$aEvent, $aBrowseParams = array())
    {
        $oPrivacy = BxDolPrivacy::getObjectInstance($this->_oConfig->getObject('privacy_view'));
        if($oPrivacy) {
            $oPrivacy->setTableFieldAuthor($this->_oConfig->isSystem($aEvent['type'], $aEvent['action']) ? 'owner_id' : 'object_id');
            if(!$oPrivacy->check($aEvent['id']))
                return '';
        }

        $aResult = $this->getData($aEvent, $aBrowseParams);
        if($aResult === false)
            return '';

        $aEvent['object_owner_id'] = $aResult['owner_id'];
        $aEvent['icon'] = !empty($aResult['icon']) ? $aResult['icon'] : '';
        $aEvent['sample'] = !empty($aResult['sample']) ? $aResult['sample'] : '_bx_timeline_txt_sample';
        $aEvent['sample_action'] = !empty($aResult['sample_action']) ? $aResult['sample_action'] : '_bx_timeline_txt_added_sample';
        $aEvent['content'] = $aResult['content'];
        $aEvent['views'] = $aResult['views'];
        $aEvent['votes'] = $aResult['votes'];
        $aEvent['reports'] = $aResult['reports'];
        $aEvent['comments'] = $aResult['comments'];

        $sType = !empty($aResult['content_type']) ? $aResult['content_type'] : BX_TIMELINE_PARSE_TYPE_DEFAULT;
        return $this->_getPost($sType, $aEvent, $aBrowseParams);
    }

    public function getPosts($aParams)
    {
        $bViewTimeline = $aParams['view'] == BX_TIMELINE_VIEW_TIMELINE;

        $iStart = $aParams['start'];
        $iPerPage = $aParams['per_page'];

        $aParamsDb = $aParams;

        //--- Check for Previous
        $iDays = -1;
        $bPrevious = false;
        if($iStart - 1 >= 0) {
            $aParamsDb['start'] -= 1;
            $aParamsDb['per_page'] += 1;
            $bPrevious = true;
        }

        //--- Check for Next
        $aParamsDb['per_page'] += 1;
        $aEvents = $this->_oDb->getEvents($aParamsDb);

        //--- Check for Previous
        if($bPrevious) {
            $aEvent = array_shift($aEvents);
            $iDays = (int)$aEvent['days'];
        }

        //--- Check for Next
        $bNext = false;
        if(count($aEvents) > $iPerPage) {
            $aEvent = array_pop($aEvents);
            $bNext = true;
        }

        $iEvents = count($aEvents);
        $sContent = '';
        if($bViewTimeline && $iEvents <= 0)
        	$sContent .= $this->getDividerToday();

        $bFirst = true;
        foreach($aEvents as $aEvent) {
            $sEvent = $this->getPost($aEvent, $aParams);
            $bEvent = !empty($sEvent);
            if(!$bEvent)
                continue;

            if($bViewTimeline && $bFirst) {
                $sContent .= $this->getDividerToday($aEvent);
                $bFirst = false;
            }

            $sContent .= $bViewTimeline && $bEvent ? $this->getDivider($iDays, $aEvent) : '';
            $sContent .= $sEvent;
        }

        $sBack = $this->getBack($aParams);
        $sLoadMore = $this->getLoadMore($aParams, $bNext, $iEvents > 0);
        $sEmpty = $this->getEmpty($iEvents <= 0);
        return array($sContent, $sLoadMore, $sBack, $sEmpty);
    }

    public function getEmpty($bVisible)
    {
        return $this->parseHtmlByName('empty.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'visible' => $bVisible ? 'block' : 'none',
            'content' => MsgBox(_t('_bx_timeline_txt_msg_no_results'))
        ));
    }

    public function getDivider(&$iDays, &$aEvent)
    {
        if(!$this->_bShowTimelineDividers || $iDays == $aEvent['days'])
            return '';

        $iDays = $aEvent['days'];
        $iDaysAgo = (int)$aEvent['ago_days'];
        if($aEvent['today'] == $aEvent['days'] || (($aEvent['today'] - $aEvent['days']) == 1 && $iDaysAgo == 0))
            return '';

        return $this->parseHtmlByName('divider.html', array(
        	'style_prefix' => $this->_oConfig->getPrefix('style'),
            'type' => 'common',
            'bx_if:show_hidden' => array(
                'condition' => false,
                'content' => array()
            ),
            'content' => bx_time_js($aEvent['date'])
        ));
    }

    public function getDividerToday($aEvent = array())
    {
        if(!$this->_bShowTimelineDividers)
            return '';

    	$bToday = !empty($aEvent) && ($aEvent['today'] == $aEvent['days'] || (($aEvent['today'] - $aEvent['days']) == 1 && (int)$aEvent['ago_days'] == 0));

        return $this->parseHtmlByName('divider.html', array(
        	'style_prefix' => $this->_oConfig->getPrefix('style'),
            'type' => 'today',
        	'bx_if:show_hidden' => array(
                'condition' => !$bToday,
                'content' => array()
            ),
            'content' => _t('_bx_timeline_txt_today')
        ));
    }

    public function getBack($aParams)
    {
        $iYearSel = (int)$aParams['timeline'];
        if($iYearSel == 0)
            return '';

        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('view');

        $iYearNow = date('Y', time());
        return $this->parseHtmlByName('back.html', array(
        	'style_prefix' => $sStylePrefix,
            'content' => $this->parseLink('javascript:void(0)', _t('_bx_timeline_txt_jump_to_recent'), array(
                'title' => _t('_bx_timeline_txt_jump_to_n_year', $iYearNow),
        		'onclick' => 'javascript:' . $sJsObject . '.changeTimeline(this, 0)'
            ))
        ));
    }

    public function getLoadMore($aParams, $bEnabled, $bVisible = true)
    {
        $iStart = $aParams['start'];
        $iPerPage = $aParams['per_page'];
        $iYearSel = (int)$aParams['timeline'];
        $iYearMin = $this->_oDb->getMaxDuration($aParams);

        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('view');

        $sYears = '';
        if(!empty($iYearMin)) {
            $iYearMax = date('Y', time()) - 1;
            for($i = $iYearMax; $i >= $iYearMin; $i--)
                $sYears .= ($i != $iYearSel ? $this->parseLink('javascript:void(0)', $i, array(
                    'title' => _t('_bx_timeline_txt_jump_to_n_year', $i),
                    'onclick' => 'javascript:' . $sJsObject . '.changeTimeline(this, ' . $i . ')'
                )) : $i) . ', ';

            $sYears = substr($sYears, 0, -2);
        }

        $aTmplVars = array(
            'style_prefix' => $sStylePrefix,
            'visible' => ($aParams['view'] == BX_TIMELINE_VIEW_TIMELINE && $bVisible) || ($aParams['view'] == BX_TIMELINE_VIEW_OUTLINE && $bEnabled && $bVisible) ? 'block' : 'none',
            'bx_if:is_disabled' => array(
                'condition' => !$bEnabled,
                'content' => array()
            ),
            'bx_if:show_on_click' => array(
                'condition' => $bEnabled,
                'content' => array(
                    'on_click' => 'javascript:' . $sJsObject . '.changePage(this, ' . ($iStart + $iPerPage) . ', ' . $iPerPage . ')'
                )
            ),
            'bx_if:show_jump_to' => array(
                'condition' => !empty($sYears),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'years' => $sYears
                )
            )
        );
        return $this->parseHtmlByName('load_more.html', $aTmplVars);
    }

    public function getComments($sSystem, $iId, $bDynamic = false)
    {
        $oModule = $this->getModule();
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $oCmts = $oModule->getCmtsObject($sSystem, $iId);
        if($oCmts === false)
            return '';

        $aComments = $oCmts->getCommentsBlock(array(), array('in_designbox' => false, 'dynamic_mode' => $bDynamic));
        return $this->parseHtmlByName('comments.html', array(
            'style_prefix' => $sStylePrefix,
            'id' => $iId,
            'content' => $aComments['content']
        ));
    }

    public function getRepostElement($iOwnerId, $sType, $sAction, $iObjectId, $aParams = array())
    {
        $aReposted = $this->_oDb->getReposted($sType, $sAction, $iObjectId);
        if(empty($aReposted) || !is_array($aReposted))
            return '';

		$oModule = $this->getModule();
		$bDisabled = $oModule->isAllowedRepost($aReposted) !== true || $this->_oDb->isReposted($aReposted['id'], $iOwnerId, $oModule->getUserId());
		if($bDisabled && (int)$aReposted['reposts'] == 0)
            return '';

		$sStylePrefix = $this->_oConfig->getPrefix('style');
        $sStylePrefixRepost = $sStylePrefix . '-repost-';

        $bShowDoRepostAsButtonSmall = isset($aParams['show_do_repost_as_button_small']) && $aParams['show_do_repost_as_button_small'] == true;
        $bShowDoRepostAsButton = !$bShowDoRepostAsButtonSmall && isset($aParams['show_do_repost_as_button']) && $aParams['show_do_repost_as_button'] == true;

        $bShowDoRepostIcon = isset($aParams['show_do_repost_icon']) && $aParams['show_do_repost_icon'] == true;
        $bShowDoRepostLabel = isset($aParams['show_do_repost_label']) && $aParams['show_do_repost_label'] == true;
        $bShowCounter = isset($aParams['show_counter']) && $aParams['show_counter'] === true;

        //--- Do repost link ---//
		$sClass = $sStylePrefixRepost . 'do-repost';
		if($bShowDoRepostAsButton)
			$sClass .= ' bx-btn';
		else if($bShowDoRepostAsButtonSmall)
			$sClass .= ' bx-btn bx-btn-small';

		$sOnClick = '';
		if(!$bDisabled) {
			$sCommonPrefix = $this->_oConfig->getPrefix('common_post');
			if(str_replace($sCommonPrefix, '', $sType) == BX_TIMELINE_PARSE_TYPE_REPOST) {
				$aRepostedData = $this->_getCommonData($aReposted);
	
	            $sOnClick = $this->_getRepostJsClick($iOwnerId, $aRepostedData['content']['type'], $aRepostedData['content']['action'], $aRepostedData['content']['object_id']);
			}
			else
				$sOnClick = $this->_getRepostJsClick($iOwnerId, $sType, $sAction, $iObjectId);
		}
		else
			$sClass .= $bShowDoRepostAsButton || $bShowDoRepostAsButtonSmall ? ' bx-btn-disabled' : ' ' . $sStylePrefixRepost . 'disabled';

		$aOnClickAttrs = array(
			'title' => _t('_bx_timeline_txt_do_repost')
		);
		if(!empty($sClass))
			$aOnClickAttrs['class'] = $sClass;
		if(!empty($sOnClick))
			$aOnClickAttrs['onclick'] = $sOnClick;

		$sDoRepost = '';
        if($bShowDoRepostIcon)
            $sDoRepost .= $this->parseIcon('repeat');

        if($bShowDoRepostLabel)
            $sDoRepost .= ($sDoRepost != '' ? ' ' : '') . _t('_bx_timeline_txt_do_repost');

        return $this->parseHtmlByName('repost_element_block.html', array(
            'style_prefix' => $sStylePrefix,
            'html_id' => $this->_oConfig->getHtmlIds('repost', 'main') . $aReposted['id'],
            'class' => ($bShowDoRepostAsButton ? $sStylePrefixRepost . 'button' : '') . ($bShowDoRepostAsButtonSmall ? $sStylePrefixRepost . 'button-small' : ''),
            'count' => $aReposted['reposts'],
            'do_repost' => $this->parseLink('javascript:void(0)', $sDoRepost, $aOnClickAttrs),
            'bx_if:show_counter' => array(
                'condition' => $bShowCounter,
                'content' => array(
                    'style_prefix' => $sStylePrefix,
        			'bx_if:show_hidden' => array(
        				'condition' => (int)$aReposted['reposts'] == 0,
        				'content' => array()
        			),
                    'counter' => $this->getRepostCounter($aReposted, $aParams)
                )
            ),
            'script' => $this->getRepostJsScript()
        ));
    }

    public function getRepostCounter($aEvent, $aParams = array())
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('repost');

        $bShowDoRepostAsButtonSmall = isset($aParams['show_do_repost_as_button_small']) && $aParams['show_do_repost_as_button_small'] == true;
        $bShowDoRepostAsButton = !$bShowDoRepostAsButtonSmall && isset($aParams['show_do_repost_as_button']) && $aParams['show_do_repost_as_button'] == true;

        $sClass = $sStylePrefix . '-repost-counter';
        if($bShowDoRepostAsButtonSmall)
            $sClass .= ' bx-btn-small-height';
        if($bShowDoRepostAsButton)
            $sClass .= ' bx-btn-height';

        return $this->parseLink('javascript:void(0)', !empty($aEvent['reposts']) && (int)$aEvent['reposts'] > 0 ? $aEvent['reposts'] : '', array(
        	'id' => $this->_oConfig->getHtmlIds('repost', 'counter') . $aEvent['id'],
        	'class' => $sClass,
            'title' => _t('_bx_timeline_txt_reposted_by'),
        	'onclick' => 'javascript:' . $sJsObject . '.toggleByPopup(this, ' . $aEvent['id'] . ')'
        ));
    }

    public function getRepostedBy($iId)
    {
        $aTmplUsers = array();
        $oModule = $this->getModule();
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $aUserIds = $this->_oDb->getRepostedBy($iId);
        foreach($aUserIds as $iUserId) {
            list($sUserName, $sUserUrl, $sUserIcon, $sUserUnit) = $oModule->getUserInfo($iUserId);
            $aTmplUsers[] = array(
                'style_prefix' => $sStylePrefix,
                'user_unit' => $sUserUnit
            );
        }

        if(empty($aTmplUsers))
            $aTmplUsers = MsgBox(_t('_Empty'));

        return $this->parseHtmlByName('repost_by_list.html', array(
            'style_prefix' => $sStylePrefix,
            'bx_repeat:list' => $aTmplUsers
        ));
    }

    public function getRepostJsScript()
    {
        $this->addCss(array('repost.css'));
        $this->addJs(array('main.js', 'repost.js'));

        return $this->getJsCode('repost');
    }

    public function getRepostJsClick($iOwnerId, $sType, $sAction, $iObjectId)
    {
        $aReposted = $this->_oDb->getReposted($sType, $sAction, $iObjectId);
        if(empty($aReposted) || !is_array($aReposted))
            return '';

        $sResult = '';
        $sCommonPrefix = $this->_oConfig->getPrefix('common_post');
        if(str_replace($sCommonPrefix, '', $sType) == BX_TIMELINE_PARSE_TYPE_REPOST) {
            $aRepostedData = $this->_getCommonData($aReposted);

            $sResult = $this->_getRepostJsClick($iOwnerId, $aRepostedData['content']['type'], $aRepostedData['content']['action'], $aRepostedData['content']['object_id']);
        }
        else
            $sResult = $this->_getRepostJsClick($iOwnerId, $sType, $sAction, $iObjectId);

        return $sResult;
    }

    public function getAttachLinkForm()
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('post');

        $aForm = $this->getModule()->getFormAttachLink();

        return $this->parseHtmlByName('attach_link_form.html', array(
            'style_prefix' => $sStylePrefix,
            'js_object' => $sJsObject,
            'form_id' => $aForm['form_id'],
            'form' => $aForm['form'],
        ));
    }

    public function getAttachLinkField($iUserId)
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $aLinks = $this->_oDb->getUnusedLinks($iUserId);

        $sLinks = '';
        foreach($aLinks as $aLink)
            $sLinks .= $this->getAttachLinkItem($iUserId, $aLink);

        return $this->parseHtmlByName('attach_link_form_field.html', array(
            'html_id' => $this->_oConfig->getHtmlIds('post', 'attach_link_form_field'),
            'style_prefix' => $sStylePrefix,
            'links' => $sLinks
        ));
    }

    public function getAttachLinkItem($iUserId, $mixedLink)
    {
        $aLink = is_array($mixedLink) ? $mixedLink : $this->_oDb->getUnusedLinks($iUserId, (int)$mixedLink);
        if(empty($aLink) || !is_array($aLink))
            return '';

        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('post');
        $sLinkIdPrefix = $this->_oConfig->getHtmlIds('post', 'attach_link_item');

        $bTmplVarsEmbed = false;
        $aTmplVarsEmbed = array();
        $oEmbed = BxDolEmbed::getObjectInstance();
        if($oEmbed) {
            $bTmplVarsEmbed = true;
            $aTmplVarsEmbed = array(
                'style_prefix' => $sStylePrefix,
                'embed' => $oEmbed->getLinkHTML($aLink['url'], $aLink['title'], 300),
            );
        }
        else {
            $aLinkAttrs = array(
            	'title' => bx_html_attribute($aLink['title'])
            );
            if(!$this->_oConfig->isEqualUrls(BX_DOL_URL_ROOT, $aLink['url'])) {
                $aLinkAttrs['target'] = '_blank';
    
                if($this->_oDb->getParam('sys_add_nofollow') == 'on')
            	    $aLinkAttrs['rel'] = 'nofollow';
            }

            $sThumbnail = "";
            if((int)$aLink['media_id'] != 0)
                $sThumbnail = BxDolTranscoderImage::getObjectInstance($this->_oConfig->getObject('transcoder_photos_preview'))->getFileUrl($aLink['media_id']);

            $aTmplVarsEmbed = array(
                'style_prefix' => $sStylePrefix,
            	'bx_if:show_thumbnail' => array(
            		'condition' => !empty($sThumbnail),
            		'content' => array(
            			'style_prefix' => $sStylePrefix,
            			'thumbnail' => $sThumbnail
            		)
            	),
    			'url' => $aLink['url'],
            	'link' => $this->parseLink($aLink['url'], $aLink['title'], $aLinkAttrs)
            );
        }

        return $this->parseHtmlByName('attach_link_item.html', array(
            'html_id' => $sLinkIdPrefix . $aLink['id'],
            'style_prefix' => $sStylePrefix,
            'js_object' => $sJsObject,
            'id' => $aLink['id'],
        	'bx_if:show_embed_outer' => array(
        		'condition' => $bTmplVarsEmbed,
        		'content' => $aTmplVarsEmbed
        	),
        	'bx_if:show_embed_inner' => array(
        		'condition' => !$bTmplVarsEmbed,
        		'content' => $aTmplVarsEmbed
            ),
        ));
    }

    public function getData(&$aEvent, $aBrowseParams = array())
    {
        $aResult = $this->_oConfig->isSystem($aEvent['type'], $aEvent['action']) ? $this->_getSystemData($aEvent, $aBrowseParams) : $this->_getCommonData($aEvent, $aBrowseParams);
        if(empty($aResult) || empty($aResult['owner_id']) || empty($aResult['content']))
            return false;

        list($sUserName) = $this->getModule()->getUserInfo($aResult['owner_id']);

        $sSample = !empty($aResult['sample']) ? $aResult['sample'] : '_bx_timeline_txt_sample';
        if(empty($aEvent['title']) || empty($aEvent['description'])) {
            $sTitle = !empty($aResult['title']) ? $this->_oConfig->getTitle($aResult['title']) : _t($sSample);

            $sDescription = !empty($aResult['description']) ? $aResult['description'] : _t('_bx_timeline_txt_user_added_sample', $sUserName, _t($sSample));
            if($sDescription == '' && !empty($aResult['content']['text']))
                $sDescription = $aResult['content']['text'];

            $this->_oDb->updateEvent(array(
                'title' => bx_process_input(strip_tags($sTitle)),
                'description' => bx_process_input(strip_tags($sDescription))
            ), array('id' => $aEvent['id']));
        }

        return $aResult;
    }

    public function getVideo(&$aEvent, &$aVideo)
    {
        $sVideoId = $this->_oConfig->getHtmlIds('view', 'video') . $aEvent['id'] . '-' . $aVideo['id'];
        return $this->parseHtmlByName('video_player.html', array(
            'player' => BxTemplFunctions::getInstance()->videoPlayer($aVideo['src_poster'], $aVideo['src_mp4'], $aVideo['src_webm'], array('id' => $sVideoId)),
            'html_id' => $sVideoId
        ));
    }

    public function getItemIcon($bT, $bL, $bP, $bV)
    {
        $sResult = '';

        if($bT && !$bL && !$bP && !$bV)
            $sResult = 'file-text-o';
        else if(!$bT && $bL && !$bP && !$bV)
            $sResult = 'link';
        else if(!$bT && !$bL && $bP && !$bV)
            $sResult = 'picture-o';
        else if(!$bT && !$bL && !$bP && $bV)
            $sResult = 'film';
        else 
            $sResult = 'file-o';

        return '<i class="sys-icon ' . $sResult . '"></i>';
    }

    function getLiveUpdateNotification($sType, $iOwnerId, $iProfileId, $iCountOld = 0, $iCountNew = 0)
    {
        $oModule = $this->getModule();

    	$iCount = (int)$iCountNew - (int)$iCountOld;
    	if($iCount < 0)
    	    return '';

        $aParams = $oModule->getParams(BX_TIMELINE_VIEW_DEFAULT, $sType, $iOwnerId, 0, $iCount, BX_TIMELINE_FILTER_OTHER_VIEWER);
        $aEvents = $this->_oDb->getEvents($aParams);
        if(empty($aEvents) || !is_array($aEvents))
			return '';

		$sJsObject = $this->_oConfig->getJsObject('view');
		$sStylePrefix = $this->_oConfig->getPrefix('style');

		$aEvents = array_reverse($aEvents);
		$iEvents = count($aEvents);

		$aTmplVarsItems = array();
		foreach($aEvents as $iIndex => $aEvent) {
		    $aData = $this->getData($aEvent);
            if($aData === false)
                continue;

			$iEventId = $aEvent['id'];
			$iEventAuthorId = $this->_oConfig->isSystem($aEvent['type'], $aEvent['action']) ? $aEvent['owner_id'] : $aEvent['object_id'];

			list($sAuthorName, $sAuthorLink, $sAuthorIcon, $sAuthorUnit) = $oModule->getUserInfo($iEventAuthorId);
    	    $bAuthorIcon = !empty($sAuthorIcon);
 
			$sShowOnClick = "javascript:" . $sJsObject . ".goTo(this, 'timeline-event-" . $iEventId . "', '" . $iEventId . "');";

	    	$aTmplVarsItems[] = array(
	    		'bx_if:show_as_hidden' => array(
	    			'condition' => $iIndex < ($iEvents - 1),
	    			'content' => array(),
	    		),
	    		'item' => $this->parseHtmlByName('live_update_notification.html', array(
	    			'style_prefix' => $sStylePrefix,
	    			'onclick_show' => $sShowOnClick,
	    		    'author_link' => $sAuthorLink, 
	    		    'author_title' => bx_html_attribute($sAuthorName),
	    		    'author_name' => $sAuthorName,
	    		    'bx_if:show_icon' => array(
                        'condition' => $bAuthorIcon,
                        'content' => array(
                            'author_icon' => $sAuthorIcon
                        )
                    ),
                    'bx_if:show_icon_empty' => array(
                        'condition' => !$bAuthorIcon,
                        'content' => array()
                    ),
                    'text' => _t($aData['sample_action'], _t($aData['sample'])),
	    		)),
	    		'bx_if:show_previous' => array(
	    			'condition' => $iIndex > 0,
	    			'content' => array(
	    				'onclick_previous' => $sJsObject . '.previousLiveUpdate(this)'
	    			)
	    		),
	    		'onclick_close' => $sJsObject . '.hideLiveUpdate(this)'
			);
		}

		return $this->parseHtmlByName('popup_chain.html', array(
			'html_id' => $this->_oConfig->getHtmlIds('view', 'live_update_popup') . $sType,
			'bx_repeat:items' => $aTmplVarsItems
		));
    }

    protected function _getPost($sType, $aEvent, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $oModule = $this->getModule();
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('view');

        list($sAuthorName, $sAuthorUrl, $sAuthorIcon, $sAuthorUnit, $sAuthorUnitShort) = $oModule->getUserInfo($aEvent['object_owner_id']);
        $bAuthorIcon = !empty($sAuthorIcon);

        $aTmplVarsNote = $this->_getTmplVarsNote($aEvent);
        $aTmplVarsMenuItemActions = $this->_getTmplVarsMenuItemActions($aEvent, $aBrowseParams);

        $aTmplVarsOwnerActions = $this->_getTmplVarsOwnerActions($aEvent);
        $bTmplVarsOwnerActions = !empty($aTmplVarsOwnerActions); 

        $aTmplVarsTimelineOwner = array();
        if(isset($aBrowseParams['type']) && in_array($aBrowseParams['type'], array(BX_BASE_MOD_NTFS_TYPE_CONNECTIONS, BX_TIMELINE_TYPE_OWNER_AND_CONNECTIONS)))
            $aTmplVarsTimelineOwner = $this->_getTmplVarsTimelineOwner($aEvent);

        $bViewItem = isset($aBrowseParams['view']) && $aBrowseParams['view'] == BX_TIMELINE_VIEW_ITEM;
        $bViewOutline = isset($aBrowseParams['view']) && $aBrowseParams['view'] == BX_TIMELINE_VIEW_OUTLINE;

        $bPromoted = (int)$aEvent['promoted'] > 0;
        $sClass = $bViewItem || !$bViewOutline ? 'bx-tl-view-sizer' : ($bPromoted ? 'bx-tl-grid-sizer-dbl' : 'bx-tl-grid-sizer');
        if(!empty($aBrowseParams['blink']) && in_array($aEvent['id'], $aBrowseParams['blink']))
			$sClass .= ' ' . $sStylePrefix . '-blink';
        if($bPromoted)
            $sClass .= ' ' . $sStylePrefix . '-promoted';

        $sClassOwner = $bTmplVarsOwnerActions ? $sStylePrefix . '-io-with-actions' : '';

        $oMetatags = BxDolMetatags::getObjectInstance($this->_oConfig->getObject('metatags'));
 		$sLocation = $oMetatags->locationsString($aEvent['id']);
 
        $aTmplVars = array (
            'style_prefix' => $sStylePrefix,
            'js_object' => $sJsObject,
        	'html_id' => $this->_oConfig->getHtmlIds('view', 'item_' . $aBrowseParams['view']) . $aEvent['id'],
            'class' => $sClass,
            'class_owner' => $sClassOwner,
        	'class_content' => $bViewItem ? 'bx-def-color-bg-block' : 'bx-def-color-bg-box',
            'id' => $aEvent['id'],
            'bx_if:show_note' => array(
                'condition' => !empty($aTmplVarsNote),
                'content' => $aTmplVarsNote
            ),
            'bx_if:show_owner_icon' => array(
                'condition' => $bAuthorIcon,
                'content' => array(
                    'owner_icon' => $sAuthorIcon
                )
            ),
            'bx_if:show_owner_icon_empty' => array(
                'condition' => !$bAuthorIcon,
                'content' => array()
            ),
            'bx_if:show_owner_actions' => array(
                'condition' => $bTmplVarsOwnerActions,
                'content' => $aTmplVarsOwnerActions
            ),
            'item_icon' => !empty($aEvent['icon']) ? $aEvent['icon'] : $CNF['ICON'],
            'item_owner_url' => $sAuthorUrl,
            'item_owner_title' => bx_html_attribute($sAuthorName),
            'item_owner_name' => $sAuthorName,
            'item_owner_unit' => $sAuthorUnitShort,
            'item_owner_action' => _t($aEvent['sample_action'], _t($aEvent['sample'])),
            'bx_if:show_timeline_owner' => array(
                'condition' => !empty($aTmplVarsTimelineOwner),
                'content' => $aTmplVarsTimelineOwner
            ),
            'item_view_url' => $this->_oConfig->getItemViewUrl($aEvent),
            'item_date' => bx_time_js($aEvent['date']),
            'bx_if:show_pinned' => array(
            	'condition' => (int)$aEvent['pinned'] != 0,
            	'content' => array(
            		'style_prefix' => $sStylePrefix,
            	)
            ),
            'content' => is_string($aEvent['content']) ? $aEvent['content'] : $this->_getContent($sType, $aEvent, $aBrowseParams),
            'bx_if:show_location' => array(
            	'condition' => !empty($sLocation),
            	'content' => array(
            		'style_prefix' => $sStylePrefix,
            		'location' => $sLocation
            	)
            ),
            'bx_if:show_menu_item_actions' => array(
                'condition' => !empty($aTmplVarsMenuItemActions),
                'content' => $aTmplVarsMenuItemActions
            ),
            'comments' => '',
        );

        $sVariable = '_sTmplContentItem' . bx_gen_method_name($aBrowseParams['view']);
        if(empty(self::$$sVariable))
            self::$$sVariable = $this->getHtml('item_' . $aBrowseParams['view'] . '.html');

        return $this->parseHtmlByContent(self::$$sVariable, $aTmplVars);
    }

    protected function _getContent($sType, $aEvent, $aBrowseParams = array())
    {
        $sMethod = '_getTmplVarsContent' . ucfirst($sType);
        if(!method_exists($this, $sMethod))
            return '';

        $sVariable = '_sTmplContentType' . bx_gen_method_name($sType);
        if(empty(self::$$sVariable))
            self::$$sVariable = $this->getHtml('type_' . $sType . '.html');

		return $this->parseHtmlByContent(self::$$sVariable, $this->$sMethod($aEvent, $aBrowseParams));
    }

    protected function _getComments($aComments)
    {
        $mixedComments = $this->getModule()->getCommentsData($aComments);
        if($mixedComments === false)
            return '';

        list($sSystem, $iObjectId, $iCount) = $mixedComments;
        return $this->getComments($sSystem, $iObjectId);
    }

    protected function _getShowMore($aParams)
    {
        return $this->parseHtmlByName('show_more.html', array(
        	'style_prefix' => $this->_oConfig->getPrefix('style'),
            'js_object' => $this->_oConfig->getJsObject('view'),
        ));
    }

    protected function _getImagePopup($aParams)
    {
        $sViewImagePopupId = $this->_oConfig->getHtmlIds('view', 'photo_popup_' . $aParams['view']);
        $sViewImagePopupContent = $this->parseHtmlByName('popup_image.html', array(
    		'image_url' => ''
    	));

    	return BxTemplFunctions::getInstance()->transBox($sViewImagePopupId, $sViewImagePopupContent, true);
    }

    protected function _getRepostJsClick($iOwnerId, $sType, $sAction, $iObjectId)
    {
        $sJsObject = $this->_oConfig->getJsObject('repost');
        $sFormat = "%s.repostItem(this, %d, '%s', '%s', %d);";

        $iOwnerId = !empty($iOwnerId) ? (int)$iOwnerId : $this->getModule()->getUserId(); //--- in whose timeline the content will be reposted
        return sprintf($sFormat, $sJsObject, $iOwnerId, $sType, $sAction, (int)$iObjectId);
    }

    protected function _getTmplVarsMenuItemActions(&$aEvent, $aBrowseParams = array())
    {
        $oMenu = BxDolMenu::getObjectInstance($this->_oConfig->getObject('menu_item_actions'));
        $oMenu->setEvent($aEvent);
        $oMenu->setView($aBrowseParams['view']);
        $oMenu->setDynamicMode(isset($aBrowseParams['dynamic_mode']) && $aBrowseParams['dynamic_mode'] === true);

        $sMenu = $oMenu->getCode();
        if(empty($sMenu))
            return array();

        return array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'js_object' => $this->_oConfig->getJsObject('view'),
            'menu_item_actions' => $sMenu
        );
    }

    protected function _getTmplVarsTimelineOwner(&$aEvent)
    {
        $iOwnerId = (int)$aEvent['owner_id'];
        if($iOwnerId == 0 || $iOwnerId == (int)$aEvent['object_owner_id'])
            return array();

        list($sTimelineAuthorName, $sTimelineAuthorUrl) = $this->getModule()->getUserInfo($aEvent['owner_id']);

        return array(
        	'style_prefix' => $this->_oConfig->getPrefix('style'),
            'owner_url' => $sTimelineAuthorUrl,
            'owner_username' => $sTimelineAuthorName,
        );
    }

    protected function _getTmplVarsContentPost(&$aEvent, $aBrowseParams = array())
    {
    	$aContent = &$aEvent['content'];
        $sStylePrefix = $this->_oConfig->getPrefix('style');
        $sJsObject = $this->_oConfig->getJsObject('view');

        $bViewItem = isset($aBrowseParams['view']) && $aBrowseParams['view'] == BX_TIMELINE_VIEW_ITEM;

        //--- Process Raw ---//
        $sRaw = isset($aContent['raw']) ? $aContent['raw'] : '';

        //--- Process Text ---//
        $sUrl = isset($aContent['url']) ? bx_html_attribute($aContent['url']) : '';
        $sTitle = '';
        if(isset($aContent['title']))
            $sTitle = bx_process_output($aContent['title']);

        if(!empty($sUrl) && !empty($sTitle))
            $sTitle = $this->parseLink($sUrl, $sTitle, array(
            	'class' => $sStylePrefix . '-title',
                'title' => $sTitle
            ));

        $sText = isset($aContent['text']) ? $aContent['text'] : '';
        $sText = $this->_prepareTextForOutput($sText, $aEvent['id']);

        //--- Process Links ---//
        $bAddNofollow = $this->_oDb->getParam('sys_add_nofollow') == 'on';

        $aTmplVarsLinks = array();
        if(!empty($aContent['links']))
            foreach($aContent['links'] as $aLink) {
                $bTmplVarsEmbed = false;
                $aTmplVarsEmbed = array();
                $oEmbed = BxDolEmbed::getObjectInstance();
                if ($oEmbed) {
                    $bTmplVarsEmbed = true;
                    $aTmplVarsEmbed = array(
                        'style_prefix' => $sStylePrefix,
                        'embed' => $oEmbed->getLinkHTML($aLink['url'], $aLink['title']),
                    );
                }
                else {
                    $aLinkAttrs = array(
                    	'title' => $aLink['title']
                    );
                    if(!$this->_oConfig->isEqualUrls(BX_DOL_URL_ROOT, $aLink['url'])) {
                        $aLinkAttrs['target'] = '_blank';
    
                        if($bAddNofollow)
                    	    $aLinkAttrs['rel'] = 'nofollow';
                    }

                    $sLinkAttrs = '';
                    foreach($aLinkAttrs as $sKey => $sValue)
                        $sLinkAttrs .= ' ' . $sKey . '="' . bx_html_attribute($sValue) . '"';
            
                    $aTmplVarsEmbed = array(
                        'bx_if:show_thumbnail' => array(
                    		'condition' => !empty($aLink['thumbnail']),
                    		'content' => array(
                    			'style_prefix' => $sStylePrefix,
                    			'thumbnail' => $aLink['thumbnail'],
                                'link' => !empty($aLink['url']) ? $aLink['url'] : 'javascript:void(0)',
                                'attrs' => $sLinkAttrs
                    		)
                    	),
                    	'link' => !empty($aLink['url']) ? $aLink['url'] : 'javascript:void(0)',
                    	'attrs' => $sLinkAttrs,
                    	'content' => $aLink['title'],
                        'bx_if:show_text' => array(
                            'condition' => !empty($aLink['text']),
                            'content' => array(
                                'style_prefix' => $sStylePrefix,
                                'text' => $aLink['text']
                            )
                        )
                    );
                }

                $aTmplVarsLinks[] = array(
                    'style_prefix' => $sStylePrefix,
                    'bx_if:show_embed_outer' => array(
                		'condition' => $bTmplVarsEmbed,
                		'content' => $aTmplVarsEmbed
                	),
                	'bx_if:show_embed_inner' => array(
                		'condition' => !$bTmplVarsEmbed,
                		'content' => $aTmplVarsEmbed
                    )
                );
            }

        //--- Process Photos ---//
        $sImagesDisplay = '';
        $aTmplVarsImages = array();
        if(!empty($aContent['images'])) {
            $sImageSrcKey = '';
            $sImageSrcKeyDefault = 'src';
            if(count($aContent['images']) == 1) {
                $sImagesDisplay = 'single';
                $sImageSrcKey = $bViewItem ? 'src_orig' : 'src_medium';
            }
            else {
                $sImagesDisplay = 'gallery';
                $sImageSrcKey = 'src';
            }

            foreach($aContent['images'] as $aImage) {
                $sImageSrc = !empty($aImage[$sImageSrcKey]) ? $aImage[$sImageSrcKey] : $aImage[$sImageSrcKeyDefault];
                if(empty($sImageSrc))
                    continue;

                $sImage = $this->parseImage($sImageSrc, array(
                	'class' => $sStylePrefix . '-item-image'
                ));

                $aAttrs = array();
                if(isset($aImage['onclick']))
                    $aAttrs['onclick'] = $aImage['onclick'];
                else if(!$bViewItem && !empty($aImage['src_orig']))
                    $aAttrs['onclick'] = 'return ' . $sJsObject . '.showItem(this, \'' . $aEvent['id'] . '\', \'photo\', ' . json_encode(array('src' => base64_encode($aImage['src_orig']))) . ')'; 

                $sImage = $this->parseLink(isset($aImage['url']) ? $aImage['url'] : 'javascript:void(0)', $sImage, $aAttrs);

                $aTmplVarsImages[] = array(
                    'style_prefix' => $sStylePrefix,
                    'image' => $sImage
                );
            }

            //--- Add Meta Image when Item is viewed on a separate page ---//
            if($bViewItem && !empty($aContent['images'][0]['src']))
                BxDolTemplate::getInstance()->addPageMetaImage($aContent['images'][0]['src']);
        }

    	//--- Process Videos ---//
    	$sVap = $this->_oConfig->getVideosAutoplay();
    	$sVapId = $this->_oConfig->getHtmlIds('view', 'video_iframe') . $aEvent['id'] . '-';
    	$sVapSrc = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'video/' . $aEvent['id'] . '/';

        $aTmplVarsVideos = array();
        if(!empty($aContent['videos']))
            foreach($aContent['videos'] as $iVideo => $aVideo)
                if($sVap == BX_TIMELINE_VAP_OFF)
                    $aTmplVarsVideos[] = array(
                        'style_prefix' => $sStylePrefix,
                    	'video' => BxTemplFunctions::getInstance()->videoPlayer($aVideo['src_poster'], $aVideo['src_mp4'], $aVideo['src_webm']) 
                    );
                else 
                    $aTmplVarsVideos[] = array(
                        'style_prefix' => $sStylePrefix,
                    	'video' => $this->parseHtmlByName('video_iframe.html', array(
                    		'style_prefix' => $sStylePrefix,
                            'html_id' => $sVapId . $iVideo,
                            'src' => $sVapSrc . $iVideo . '/'
                        )) 
                    );

        return array(
            'style_prefix' => $sStylePrefix,
            'bx_if:show_title' => array(
                'condition' => !empty($sTitle),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'title' => $sTitle,
                )
            ),
            'bx_if:show_content' => array(
                'condition' => !empty($sText),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'item_content' => $sText
                )
            ),
            'bx_if:show_content_raw' => array(
                'condition' => !empty($sRaw),
                'content' => array(
                    'item_content_raw' => $sRaw
                )
            ),
            'bx_if:show_links' => array(
                'condition' => !empty($aTmplVarsLinks),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'bx_repeat:links' => $aTmplVarsLinks
                )
            ),
            'bx_if:show_images' => array(
                'condition' => !empty($aTmplVarsImages),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
            		'images_display' => $sImagesDisplay,
                    'bx_repeat:images' => $aTmplVarsImages
                )
            ),
            'bx_if:show_videos' => array(
                'condition' => !empty($aTmplVarsVideos),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'bx_repeat:videos' => $aTmplVarsVideos
                )
            )
        );
    }

    protected function _getTmplVarsContentRepost(&$aEvent, $aBrowseParams = array())
    {
    	$aContent = &$aEvent['content'];
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $sOwnerLink = $this->parseLink($aContent['owner_url'], $aContent['owner_name']);

        $sSample = _t($aContent['sample']);
        $sSampleLink = empty($aContent['url']) ? $sSample : $this->parseLink($aContent['url'], $sSample);

        $sTitle = _t('_bx_timeline_txt_reposted', $sOwnerLink, $sSampleLink);
        $sText = $this->_getContent($aContent['parse_type'], $aEvent);

        return array(
            'bx_if:show_title' => array(
                'condition' => !empty($sTitle),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'title' => $sTitle,
                )
            ),
            'bx_if:show_content' => array(
                'condition' => !empty($sText),
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'content' => $sText,
                )
            )
        );
    }

    protected function _getTmplVarsNote(&$aEvent)
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $iUser = bx_get_logged_profile_id();
        $iOwner = $this->_oConfig->isSystem($aEvent['type'], $aEvent['action']) ? $aEvent['owner_id'] : $aEvent['object_id'];

        $aTmplVars = array();
        if(!empty($aEvent['promoted']) && $iUser != $iOwner) {
            $sConnection = $this->_oConfig->getObject('conn_subscriptions');
            $oConnection = BxDolConnection::getObjectInstance($sConnection);
            if(!$oConnection->isConnected($iUser, $iOwner))
                $aTmplVars = array(
                    'style_prefix' => $sStylePrefix,
                    'bx_if:show_note_color' => array(
                        'condition' => false,
                        'content' => array(
                            'item_note_color' => 'red1',
                        )
                    ),
                    'item_note' => _t('_bx_timeline_txt_promoted')
                );
        }

        return $aTmplVars;
    }

    protected function _getTmplVarsOwnerActions(&$aEvent)
    {
        $sStylePrefix = $this->_oConfig->getPrefix('style');

        $iUser = bx_get_logged_profile_id();
        $iOwner = $this->_oConfig->isSystem($aEvent['type'], $aEvent['action']) ? $aEvent['owner_id'] : $aEvent['object_id'];

        $aTmplVarsActions = array();
        if(!empty($aEvent['promoted']) && !empty($iUser) && !empty($iOwner) && $iUser != $iOwner) {
            $sConnection = $this->_oConfig->getObject('conn_subscriptions');
            $oConnection = BxDolConnection::getObjectInstance($sConnection);
            if(!$oConnection->isConnected($iUser, $iOwner)) {
                $sContent = _t('_sys_menu_item_title_sm_subscribe');
                $aTmplVarsActions[] = array(
                    'href' => "javascript:void(0)",
                    'onclick' => "bx_conn_action(this, '" . $sConnection . "', 'add', '" . $iOwner . "')",
                	'title' => bx_html_attribute($sContent),
                    'content' => $sContent,
                    'icon' => 'check'
                );
            }
        }

        return array(
            'style_prefix' => $sStylePrefix,
            'bx_repeat:actions' => $aTmplVarsActions
        );
    }

    protected function _getSystemData(&$aEvent, $aBrowseParams = array())
    {
        $mixedResult = $this->_oConfig->getSystemData($aEvent, $aBrowseParams);
		if($mixedResult === false) {
    		$sMethod = 'display' . bx_gen_method_name($aEvent['type'] . '_' . $aEvent['action']);
    		if(method_exists($this, $sMethod))
    		    $mixedResult = $this->$sMethod($aEvent);
		}
		
		if($mixedResult === false)
            return '';

        $this->_preparetDataActions($aEvent, $mixedResult);
		return $mixedResult;
    }

    protected function _getCommonData(&$aEvent, $aBrowseParams = array())
    {
        $CNF = $this->_oConfig->CNF;

        $oModule = $this->getModule();
        $sJsObject = $this->_oConfig->getJsObject('view');
        $sPrefix = $this->_oConfig->getPrefix('common_post');
        $sType = str_replace($sPrefix, '', $aEvent['type']);

        $aResult = array(
            'owner_id' => $aEvent['object_id'],
            'icon' => $CNF['ICON'],
        	'sample' => '_bx_timeline_txt_sample_with_article',
            'sample_wo_article' => '_bx_timeline_txt_sample',
        	'sample_action' => '_bx_timeline_txt_added_sample',
            'content_type' => $sType,
            'content' => array(
                'sample' => '_bx_timeline_txt_sample_with_article',
        		'sample_wo_article' => '_bx_timeline_txt_sample',
        		'sample_action' => '_bx_timeline_txt_added_sample',
                'url' => $this->_oConfig->getItemViewUrl($aEvent)
            ), //a string to display or array to parse default template before displaying.
            'views' => '',
            'votes' => '',
            'reports' => '',
            'comments' => '',
            'title' => $aEvent['title'], //may be empty.
            'description' => $aEvent['description'] //may be empty.
        );

        switch($sType) {
            case BX_TIMELINE_PARSE_TYPE_POST:
                if(!empty($aEvent['content']))
                    $aResult['content'] = array_merge($aResult['content'], unserialize($aEvent['content']));

                $aLinks = $this->_oDb->getLinks($aEvent['id']);
                if(!empty($aLinks) && is_array($aLinks))
                	$oTranscoder = BxDolTranscoderImage::getObjectInstance($this->_oConfig->getObject('transcoder_photos_preview'));

                    foreach($aLinks as $aLink)
                        $aResult['content']['links'][] = array(
                            'url' => $aLink['url'],
                            'title' => $aLink['title'],
                            'text' => $aLink['text'],
                        	'thumbnail' => (int)$aLink['media_id'] != 0 ? $oTranscoder->getFileUrl($aLink['media_id']) : ''
                        );

                $aPhotos = $this->_oDb->getMedia(BX_TIMELINE_MEDIA_PHOTO, $aEvent['id']);
                if(!empty($aPhotos) && is_array($aPhotos)) {
                    $oTranscoder = BxDolTranscoderImage::getObjectInstance($this->_oConfig->getObject('transcoder_photos_view'));
                    $oTranscoderMedium = BxDolTranscoderImage::getObjectInstance($this->_oConfig->getObject('transcoder_photos_medium'));
                    $oTranscoderBig = BxDolTranscoderImage::getObjectInstance($this->_oConfig->getObject('transcoder_photos_big'));

                    foreach($aPhotos as $iPhotoId) {
                        $sPhotoSrc = $oTranscoder->getFileUrl($iPhotoId);
                        $sPhotoSrcMedium = $oTranscoderMedium->getFileUrl($iPhotoId);
                        $sPhotoSrcBig = $oTranscoderBig->getFileUrl($iPhotoId);
                        if(empty($sPhotoSrcMedium) && !empty($sPhotoSrc))
                            $sPhotoSrcMedium = $sPhotoSrc;
                        if(empty($sPhotoSrcBig) && !empty($sPhotoSrcMedium))
                            $sPhotoSrcBig = $sPhotoSrcMedium;

                        $aResult['content']['images'][] = array(
                            'src' => $sPhotoSrc,
                        	'src_medium' => $sPhotoSrcMedium,
                            'src_orig' => $sPhotoSrcBig,
                        );
                    }
                }

                $aVideos = $this->_oDb->getMedia(BX_TIMELINE_MEDIA_VIDEO, $aEvent['id']);
                if(!empty($aVideos) && is_array($aVideos)) {
                    $oTranscoderPoster = BxDolTranscoderVideo::getObjectInstance($this->_oConfig->getObject('transcoder_videos_poster'));
                    $oTranscoderMp4 = BxDolTranscoderVideo::getObjectInstance($this->_oConfig->getObject('transcoder_videos_mp4'));
                    $oTranscoderWebm = BxDolTranscoderVideo::getObjectInstance($this->_oConfig->getObject('transcoder_videos_webm'));

                    foreach($aVideos as $iVideoId) {
                        $aResult['content']['videos'][$iVideoId] = array(
                            'id' => $iVideoId,
                            'src_poster' => $oTranscoderPoster->getFileUrl($iVideoId),
                        	'src_mp4' => $oTranscoderMp4->getFileUrl($iVideoId),
                        	'src_webm' => $oTranscoderWebm->getFileUrl($iVideoId),
                        );
                    }
                }
                break;

            case BX_TIMELINE_PARSE_TYPE_REPOST:
                if(empty($aEvent['content']))
                    return array();

                $aContent = unserialize($aEvent['content']);

                if(!$this->_oConfig->isSystem($aContent['type'] , $aContent['action'])) {
                    $aReposted = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $aContent['object_id']));
                    $aReposted = $this->_getCommonData($aReposted, $aBrowseParams);
                } 
                else
                	$aReposted = $this->_getSystemData($aContent, $aBrowseParams);

				if(empty($aReposted) || !is_array($aReposted))
					return array();

                $aResult['content'] = array_merge($aContent, $aReposted['content']);
                $aResult['content']['parse_type'] = !empty($aReposted['content_type']) ? $aReposted['content_type'] : BX_TIMELINE_PARSE_TYPE_DEFAULT;
                $aResult['content']['owner_id'] = $aReposted['owner_id'];
                list($aResult['content']['owner_name'], $aResult['content']['owner_url']) = $oModule->getUserInfo($aReposted['owner_id']);

                if(!empty($aReposted['sample']))
                    $aResult['content']['sample'] = $aReposted['sample'];
                if(!empty($aReposted['sample_wo_article']))
                    $aResult['content']['sample'] = $aReposted['sample_wo_article'];

                list($sUserName) = $oModule->getUserInfo($aEvent['object_id']);
                $aResult['title'] = _t('_bx_timeline_txt_user_repost', $sUserName, _t($aResult['content']['sample']));
                $aResult['description'] = _t('_bx_timeline_txt_user_reposted_user_sample', $sUserName, $aResult['content']['owner_name'], _t($aResult['content']['sample']));
                break;
        }

        $this->_preparetDataActions($aEvent, $aResult);
        return $aResult;
    }

    protected function _preparetDataActions(&$aEvent, &$aResult)
    {
        if(empty($aEvent) || !is_array($aEvent) || empty($aEvent['id']))
            return;

        $oModule = $this->getModule();

        $sSystem = $this->_oConfig->getObject('view');
        if(empty($aResult['views']) && $oModule->getViewObject($sSystem, $aEvent['id']) !== false)
            $aResult['views'] = array(
                'system' => $sSystem,
                'object_id' => $aEvent['id'],
                'count' => $aEvent['views']
            );

        $sSystem = $this->_oConfig->getObject('vote');
        if(empty($aResult['votes']) && $oModule->getVoteObject($sSystem, $aEvent['id']) !== false)
            $aResult['votes'] = array(
                'system' => $sSystem,
                'object_id' => $aEvent['id'],
                'count' => $aEvent['votes']
            );

		$sSystem = $this->_oConfig->getObject('report');
        if(empty($aResult['reports']) && $oModule->getReportObject($sSystem, $aEvent['id']) !== false)
            $aResult['reports'] = array(
                'system' => $sSystem,
                'object_id' => $aEvent['id'],
                'count' => $aEvent['reports']
            );

        $sSystem = $this->_oConfig->getObject('comment');
        if(empty($aResult['comments']) && $oModule->getCmtsObject($sSystem, $aEvent['id']) !== false)
            $aResult['comments'] = array(
                'system' => $sSystem,
                'object_id' => $aEvent['id'],
                'count' => $aEvent['comments']
            );
    }

    protected function _prepareTextForOutput($s, $iEventId = 0)
    {
    	$s = bx_process_output($s, BX_DATA_HTML);

        $oMetatags = BxDolMetatags::getObjectInstance($this->_oConfig->getObject('metatags'));
		$s = $oMetatags->metaParse($iEventId, $s);

        return $s;
    }
}

/** @} */
