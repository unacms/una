<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Timeline Timeline
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleTemplate');

class BxTimelineTemplate extends BxDolModuleTemplate
{
    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb)
    {
        parent::BxDolModuleTemplate($oConfig, $oDb);

        $this->_aTemplates = array('comments', 'comments_actions', 'common_media');
    }

    protected function getModule()
    {
    	$sName = $this->_oConfig->getName();
    	return BxDolModule::getInstance($sName);
    }

    public function getJsCode($sType, $aRequestParams = array(), $bWrap = true)
    {
    	$oJson = new Services_JSON();
    	$oModule = $this->getModule();

    	$sJsClass = $this->_oConfig->getJsClass($sType);
    	$sJsObject = $this->_oConfig->getJsObject($sType);    	

        ob_start();
?>
		var <?=$sJsObject; ?> = new <?=$sJsClass; ?>({
            sActionUrl: '<?=BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(); ?>',
			sObjName: '<?=$sJsObject; ?>',
			iOwnerId: <?=$oModule->_iOwnerId; ?>,
            sAnimationEffect: '<?=$this->_oConfig->getAnimationEffect(); ?>',
            iAnimationSpeed: '<?=$this->_oConfig->getAnimationSpeed(); ?>',
            oRequestParams: <?php echo $oJson->encode($aRequestParams); ?>
        });
<?php
		$sContent = ob_get_clean();

        return !$bWrap ? $sContent : $this->_wrapInTagJsCode($sContent);
    }

    public function getPostBlock($iOwnerId)
    {
    	$oModule = $this->getModule();
    	$sJsObject = $this->_oConfig->getJsObject('post');

    	$aFormText = $oModule->getFormText();
    	$aFormLink = $oModule->getFormLink();

    	$this->addCss('post.css');
        $this->addJs(array('jquery.form.js', 'main.js', 'post.js'));
        return $this->parseHtmlByName('post.html', array (
            'js_object' => $sJsObject,
        	'js_content' => $this->getJsCode('post', array(
            	'owner_id' => $iOwnerId 
        	)),
            'text_form' => $aFormText['form'],
        	'text_form_id' => $aFormText['form_id'],
            'link_form' => $aFormLink['form'],
        	'link_form_id' => $aFormLink['form_id'],

            'photo_form' => '',
        	'music_form' => '',
            'video_form' => '',
        ));        
    }

    public function getViewBlock($iOwnerId, $iStart, $iPerPage, $sFilter, $iTimeline, $aModules)
    {
    	if($iStart == -1)
           $iStart = 0;
        if($iPerPage == -1)
           $iPerPage = $this->_oConfig->getPerPage();
		if($iTimeline == -1)
           $iTimeline = $this->_oDb->getMaxDuration($iOwnerId, $sFilter, $aModules);
        if(empty($sFilter))
            $sFilter = BX_TIMELINE_FILTER_ALL;

		list($sContent, $sLoadMore) = $this->getPosts($iOwnerId, 'desc', $iStart, $iPerPage, $sFilter, $iTimeline, $aModules);

    	$this->addCss(array('view.css'));
        $this->addJs(array('jquery.masonry.min.js', 'common_anim.js', 'main.js', 'view.js'));
    	return $this->parseHtmlByName('view.html', array(
    		'style_prefix' => $this->_oConfig->getPrefix('style'),
            'timeline' => $this->getTimeline($iOwnerId, $iStart, $iPerPage, $sFilter, $iTimeline, $aModules),
            'content' => $sContent,
    		'load_more' =>  $sLoadMore,
            'js_content' => $this->getJsCode('view', array(
            	'owner_id' => $iOwnerId, 
            	'start' => $iStart, 
            	'per_page' => $iPerPage, 
            	'filter' => $sFilter, 
            	'timeline' => $iTimeline, 
            	'modules' => $aModules
			))
        ));
    }

	public function getPosts($iOwnerId, $sOrder, $iStart, $iPerPage, $sFilter, $iTimeline, $aModules)
    {
        $iStartEv = $iStart;
        $iPerPageEv = $iPerPage;

        //--- Check for Previous
        $bPrevious = false;
        if($iStart - 1 >= 0) {
            $iStartEv -= 1;
            $iPerPageEv += 1;
            $bPrevious = true;
        }

        //--- Check for Next
        $iPerPageEv += 1;
        $aEvents = $this->_oDb->getEvents(array(
        	'type' => 'owner', 
        	'owner_id' => $iOwnerId, 
        	'order' => $sOrder, 
        	'start' => $iStartEv, 
        	'count' => $iPerPageEv, 
        	'filter' => $sFilter, 
        	'timeline' => $iTimeline,
        	'modules' => $aModules
        ));

        //--- Check for Previous
        if($bPrevious) {
            $aEvent = array_shift($aEvents);
        }

        //--- Check for Next
        $bNext = false;
        if(count($aEvents) > $iPerPage) {
            $aEvent = array_pop($aEvents);
            $bNext = true;
        }

        $iEvents = count($aEvents);
        $sContent = $this->getEmpty($iEvents <= 0);

        foreach($aEvents as $aEvent) {
            $aEvent['content'] = !empty($aEvent['action']) ? $this->getSystem($aEvent) : $this->getCommon($aEvent);
            if(empty($aEvent['content']))
                continue;

            $sContent .= $aEvent['content'];
        }

        $sLoadMore = $this->getLoadMore($iStart, $iPerPage, $bNext, $iEvents > 0);
        return array($sContent, $sLoadMore);
    }

	public function getEmpty($bVisible)
    {
        return $this->parseHtmlByName('empty.html', array(
        	'style_prefix' => $this->_oConfig->getPrefix('style'),
            'visible' => $bVisible ? 'block' : 'none',
            'content' => MsgBox(_t('_bx_timeline_txt_msg_no_results'))
        ));
    }

	public function getTimeline($iOwnerId, $iStart, $iPerPage, $sFilter, $iTimeline, $aModules)
    {
        $iMaxDuration = $this->_oDb->getMaxDuration($iOwnerId, $sFilter, $aModules);
        if($iMaxDuration <= $this->_oConfig->getTimelineVisibilityThreshold())
			return '';

        if(empty($iTimeline))
            $iTimeline = $iMaxDuration;

        return $this->parseHtmlByName('timeline.html', array(
        	'style_prefix' => $this->_oConfig->getPrefix('style'),
        	'js_object' => $this->_oConfig->getJsObject('view'),
			'min' => 0,
			'max' => $iMaxDuration,
        	'value' => $iTimeline
        ));
    }

	public function getLoadMore($iStart, $iPerPage, $bEnabled = true, $bVisible = true)
    {
        $aTmplVars = array(
        	'style_prefix' => $this->_oConfig->getPrefix('style'),
            'visible' => $bVisible ? 'block' : 'none',
            'bx_if:is_disabled' => array(
                'condition' => !$bEnabled,
                'content' => array()
            ),
            'bx_if:show_on_click' => array(
                'condition' => $bEnabled,
                'content' => array(
                    'on_click' => $this->_oConfig->getJsObject('view') . '.changePage(this, ' . ($iStart + $iPerPage) . ', ' . $iPerPage . ')'
                )
            )
        );
        return $this->parseHtmlByName('load_more.html', $aTmplVars);
    }

	public function getCommon($aEvent)
    {
        $sPrefix = $this->_oConfig->getPrefix('common_post');
        if(strpos($aEvent['type'], $sPrefix) !== 0)
            return '';

/*
        if(in_array($aEvent['type'], array($sPrefix . 'photos', $sPrefix . 'sounds', $sPrefix . 'videos'))) {
            $aContent = unserialize($aEvent['content']);
            $aEvent = array_merge($aEvent, $this->getCommonMedia($aContent['type'], (int)$aContent['id']));
            if(empty($aEvent['content']) || (int)$aEvent['content'] > 0)
                return '';
        }
*/

		$oModule = $this->getModule();
		$sStylePrefix = $this->_oConfig->getPrefix('style');
		$sJsObject = $this->_oConfig->getJsObject('view');

        list($sAuthorName, $sAuthorUrl, $sAuthorIcon) = $oModule->getUserInfo($aEvent['object_id']);
        $bAuthorIcon = !empty($sAuthorIcon);

        $aMenuItems = array();
        if($oModule->isAllowedDelete())
        	$aMenuItems[] = array('name' => 'timeline-item-delete', 'link' => 'javascript:void(0)', 'onclick' => "javascript:" . $sJsObject . ".deletePost(this, " . $aEvent['id'] . ")", 'target' => '_self', 'icon' => 'remove', 'title' => _t('_bx_timeline_menu_item_delete'));

		$aTmplVarsMenu = array();
		if(!empty($aMenuItems)) {
			bx_import('BxTemplFunctions');

			$aTmplVarsMenu = array(
        		'style_prefix' => $sStylePrefix,
        		'js_object' => $sJsObject,
				'menu' => BxTemplFunctions::getInstance()->designBoxMenu(array ('template' => 'menu_vertical_lite.html', 'menu_items' => $aMenuItems))
			);
		}

        $aTmplVarsTimelineOwner = array();
        if((int)$aEvent['owner_id'] != (int)$aEvent['object_id']) {
        	list($sTimelineAuthorName, $sTimelineAuthorUrl) = $oModule->getUserInfo($aEvent['owner_id']);

        	$aTmplVarsTimelineOwner = array(
				'owner_url' => $sTimelineAuthorUrl,
        		'owner_username' => $sTimelineAuthorName,
			);
        }

        $aContent = array();
		if(!empty($aEvent['content']))
			$aContent = unserialize($aEvent['content']);

        $aTmplVars = array (
        	'style_prefix' => $sStylePrefix,
        	'post_id' => $aEvent['id'],
        	'bx_if:show_menu' => array(
        		'condition' => !empty($aTmplVarsMenu),
        		'content' => $aTmplVarsMenu
        	),
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
            'item_owner_url' => $sAuthorUrl,
            'item_owner_name' => $sAuthorName,
        	'bx_if:show_timeline_owner' => array(
        		'condition' => !empty($aTmplVarsTimelineOwner),
        		'content' => $aTmplVarsTimelineOwner
        	),
        );

        switch(str_replace($sPrefix, '', $aEvent['type'])) {
            case 'text':
            	$aTmplVars = array_merge($aTmplVars, array(
            		'bx_if:show_title' => array(
		        		'condition' => false,
            			'content' => array()
		        	),
		        	'bx_if:show_content' => array(
		        		'condition' => !empty($aContent['content']),
		        		'content' => array(
		        			'style_prefix' => $sStylePrefix,
		        			'item_content' => $aContent['content']
		        		)
		        	)
            	));
				break;

            case 'link':
                $aTmplVars = array_merge($aTmplVars, array(
            		'bx_if:show_title' => array(
		        		'condition' => true,
            			'content' => array(
                			'style_prefix' => $sStylePrefix,
                			'item_url' => $aContent['url'],
                			'item_title' => $aContent['title']
                		)
		        	),
		        	'bx_if:show_content' => array(
		        		'condition' => !empty($aContent['content']),
		        		'content' => array(
		        			'item_content' => $aContent['description']
		        		)
		        	)
            	));
                break;
            case 'photos':
            case 'videos':
            case 'sounds':
                break;
        }
        
        /*
        bx_import('Cmts', $this->_aModule);
        $oComments = new BxTimelineCmts($this->_oConfig->getCommentSystemName(), $aEvent['id']);
        $aTmplVars = array_merge($aTmplVars, array('comments_content' => $oComments->getCommentsFirst('comment')));
		*/

        return $this->parseHtmlByName(str_replace($sPrefix, '', $aEvent['type']) . '.html', $aTmplVars);
    }
    
    
    

    
    
    /*

	function getDividerToday(&$aEvent)
    {
        return $this->parseHtmlByTemplateName('divider', array(
            'cpt_class' => 'wall-divider-today ' . ($aEvent['days'] == $aEvent['today'] && !empty($aEvent['content']) ? 'visible' : 'hidden'),
            'content' => _t('_wall_today')
        ));
    }

	function getDivider(&$iDays, &$aEvent)
    {
        if($iDays == $aEvent['days'])
            return "";

        if($aEvent['days'] == $aEvent['today']) {
            $iDays = $aEvent['days'];
            return "";
        }

        $sDaysAgo = "";
        $iDaysAgo = (int)$aEvent['ago_days'];
        if($iDaysAgo == 1)
            $sDaysAgo = _t('_wall_1_days_ago');
        else if($iDaysAgo > 1 && $iDaysAgo < 31)
            $sDaysAgo = _t('_wall_n_days_ago', $aEvent['ago_days']);
        else
            $sDaysAgo = $aEvent['print_date'];

        $sResult = $this->parseHtmlByTemplateName('divider', array(
            'cpt_class' => 'wall-divider',
            'content' => $sDaysAgo
        ));

        $iDays = $aEvent['days'];
        return $sResult;
    }

	function getLoadMoreOutline($iStart, $iPerPage, $bEnabled = true, $bVisible = true)
    {
        $aTmplVars = array(
            'visible' => $bVisible ? 'block' : 'none',
            'bx_if:is_disabled' => array(
                'condition' => !$bEnabled,
                'content' => array()
            ),
            'bx_if:show_on_click' => array(
                'condition' => $bEnabled,
                'content' => array(
                    'on_click' => $this->_oConfig->getJsObject('outline') . '.changePage(' . ($iStart + $iPerPage) . ', ' . $iPerPage . ')'
                )
            )
        );
        return $this->parseHtmlByName('load_more.html', $aTmplVars);
    }

    */
    
    
    
    

	

    /**
     * Common public methods.
     * Is used to display events on the Wall.
     */
    function getSystem($aEvent, $sDisplayType = BX_WALL_VIEW_TIMELINE)
    {
    	return "";

        $sResult = "";

        $sHandler = $aEvent['type'] . '_' . $aEvent['action'];
        if(!$this->_oConfig->isHandler($sHandler))
            return '';

        $aHandler = $this->_oConfig->getHandlers($sHandler);
        if(empty($aHandler['module_uri']) && empty($aHandler['module_class']) && empty($aHandler['module_method'])) {
            $sMethod = 'display' . str_replace(' ', '', ucwords(str_replace('_', ' ', $aHandler['alert_unit'] . '_' . $aHandler['alert_action'])));
            if(!method_exists($this, $sMethod))
                return '';

            $aResult = $this->$sMethod($aEvent, $sDisplayType);
        } else {
            $aEvent['js_mode'] = $this->_oConfig->getJsMode();

            $sMethod = $aHandler['module_method'] .  ($sDisplayType == BX_WALL_VIEW_OUTLINE ? '_' . BX_WALL_VIEW_OUTLINE : '');
            $aResult = BxDolService::call($aHandler['module_uri'], $sMethod, array($aEvent), $aHandler['module_class']);

            if(isset($aResult['save']))
                $this->_oDb->updateEvent($aResult['save'], array('id' => $aEvent['id']));
        }

        $bResult = !empty($aResult);
        if($bResult && isset($aResult['perform_delete']) && $aResult['perform_delete'] == true) {
            $this->_oDb->deleteEvent(array('id' => $aEvent['id']));
            return '';
        } else if(!$bResult || ($bResult && empty($aResult['content'])))
            return '';

        $sComments = "";
        if($sDisplayType == BX_WALL_VIEW_TIMELINE) {
            if((empty($aEvent['title']) && !empty($aResult['title'])) || (empty($aEvent['description']) && !empty($aResult['description'])))
                $this->_oDb->updateEvent(array(
                    'title' => process_db_input($aResult['title'], BX_TAGS_STRIP),
                    'description' => process_db_input($aResult['description'], BX_TAGS_STRIP)
                ), array('id' => $aEvent['id']));

            if(!in_array($aEvent['type'], array('profile', 'friend')) && $aEvent['action'] != 'commentPost') {
                $sType = $aEvent['type'];
                $iObjectId = $aEvent['object_id'];
                if(strpos($iObjectId, ',') !== false) {
                    $sType = isset($aResult['grouped']['group_cmts_name']) ? $aResult['grouped']['group_cmts_name'] : '';
                    $iObjectId = isset($aResult['grouped']['group_id']) ? (int)$aResult['grouped']['group_id'] : 0;
                }

                bx_import('Cmts', $this->_aModule);
                $oComments = new BxTimelineCmts($sType, $iObjectId);
                if($oComments->isEnabled())
                    $sComments = $oComments->getCommentsFirstSystem('comment', $aEvent['id']);
                else
                    $sComments = $this->getDefaultComments($aEvent['id']);
            } else
                $sComments = $this->getDefaultComments($aEvent['id']);
        }

        return $this->parseHtmlByContent($aResult['content'], array(
            'post_id' => $aEvent['id'],
            'post_owner_icon' => get_member_icon($aEvent['owner_id'], 'none'),
            'comments_content' => $sComments
        ));
    }

    function displayProfileEdit($aEvent)
    {
        $aOwner = $this->_oDb->getUser($aEvent['owner_id']);
        if(empty($aOwner))
            return array('perform_delete' => true);

        if($aOwner['status'] != 'Active')
            return "";

        if($aOwner['couple'] == 0 && $aOwner['sex'] == 'male')
            $sTxtEditedProfile = _t('_wall_edited_his_profile');
        else if($aOwner['couple'] == 0 && $aOwner['sex'] == 'female')
            $sTxtEditedProfile = _t('_wall_edited_her_profile');
        else if($aOwner['couple'] > 0)
            $sTxtEditedProfile = _t('_wall_edited_their_profile');

        $sOwner = getNickName((int)$aEvent['owner_id']);
        return array(
            'title' => $sOwner . ' ' . $sTxtEditedProfile,
            'description' => '',
            'content' => $this->parseHtmlByName('p_edit.html', array(
                'cpt_user_name' => $sOwner,
                'cpt_edited_profile' => $sTxtEditedProfile,
                'cpt_info_url' => getProfileLink($aOwner['id']),
                'post_id' => $aEvent['id']
            ))
        );
    }

    function displayProfileEditStatusMessage($aEvent)
    {
        $aOwner = $this->_oDb->getUser($aEvent['owner_id']);
        if(empty($aOwner))
            return array('perform_delete' => true);

        if($aOwner['status'] != 'Active')
            return "";

        if($aOwner['couple'] == 0 && $aOwner['sex'] == 'male')
            $sTxtEditedProfile = _t('_wall_edited_his_profile_status_message');
        else if($aOwner['couple'] == 0 && $aOwner['sex'] == 'female')
            $sTxtEditedProfile = _t('_wall_edited_her_profile_status_message');
        else if($aOwner['couple'] > 0)
            $sTxtEditedProfile = _t('_wall_edited_their_profile_status_message');

        $aParams = array();
        if(!empty($aEvent['content']))
            $aParams = unserialize($aEvent['content']);

        $sOwner = getNickName((int)$aEvent['owner_id']);
        $sMessage = isset($aParams[0]) ? stripslashes($aParams[0]) : '';
        return array(
            'title' => $sOwner . ' ' . $sTxtEditedProfile,
            'description' => $sMessage,
            'content' => $this->parseHtmlByName('p_edit_status_message.html', array(
                'cpt_user_name' => $sOwner,
                'cpt_edited_profile_status_message' => $sTxtEditedProfile,
                'cnt_status_message' => $sMessage,
                'post_id' => $aEvent['id']
            ))
        );
    }

    function displayProfileCommentPost($aEvent)
    {
        $iId = (int)$aEvent['object_id'];
        $iOwner = (int)$aEvent['owner_id'];
        $sOwner = getNickName($iOwner);

        $aItem = getProfileInfo($iId);
		if(empty($aItem) || !is_array($aItem))
        	return array('perform_delete' => true);

        $aContent = unserialize($aEvent['content']);
        if(empty($aContent) || !isset($aContent['comment_id']))
            return '';

        bx_import('BxDolCmtsProfile');
        $oCmts = new BxDolCmtsProfile('profile', $iId);
        if(!$oCmts->isEnabled())
            return '';

        $aItem['url'] = getProfileLink($iId);
        $aComment = $oCmts->getCommentRow((int)$aContent['comment_id']);

        $sTextAddedNew = _t('_wall_added_new_comment_profile');
        $sTextWallObject = _t('_wall_object_profile');
        $aTmplVars = array(
            'cpt_user_name' => $sOwner,
            'cpt_added_new' => $sTextAddedNew,
            'cpt_object' => $sTextWallObject,
            'cpt_item_url' => $aItem['url'],
            'cnt_comment_text' => $aComment['cmt_text'],
            'cnt_item_page' => $aItem['url'],
            'cnt_item_icon' => get_member_thumbnail($iId, 'none', true),
            'cnt_item_title' => $aItem['title'],
            'cnt_item_description' => $aItem['description'],
            'post_id' => $aEvent['id'],
        );
        return array(
            'title' => $sOwner . ' ' . $sTextAddedNew . ' ' . $sTextWallObject,
            'description' => $aComment['cmt_text'],
            'content' => $this->parseHtmlByName('p_comment.html', $aTmplVars)
        );
    }

    function displayFriendAccept($aEvent)
    {
        $aOwner = $this->_oDb->getUser($aEvent['owner_id']);
        $aFriend = $this->_oDb->getUser($aEvent['object_id']);
        if(empty($aOwner) || empty($aFriend))
            return array('perform_delete' => true);

        if($aOwner['status'] != 'Active' || $aFriend['status'] != 'Active')
            return "";

        $sOwner = getNickName((int)$aEvent['owner_id']);

        $iFriend = (int)$aFriend['id'];
        $sFriend = getNickName($iFriend);
        return array(
            'title' => $sOwner . ' ' . _t('_wall_friends_with') . ' ' . $aFriend['username'],
            'description' => '',
            'content' => $this->parseHtmlByName('f_accept.html', array(
                'cpt_user_name' => $sOwner,
                'cpt_friend_url' => getProfileLink($aFriend['id']),
                'cpt_friend_name' => $sFriend,
                'cnt_friend' => get_member_thumbnail($iFriend, 'none', true),
                'post_id' => $aEvent['id']
            ))
        );
    }

    function getCommonMedia($sType, $iObject)
    {
        $aConverter = array('photos' => 'photo', 'sounds' => 'music', 'videos' => 'video');

        $aMediaInfo = BxDolService::call($sType, 'get_' . $aConverter[$sType] . '_array', array($iObject, 'browse'), 'Search');
        $aOwner = $this->_oDb->getUser($aMediaInfo['owner']);

        $sAddedMediaTxt = _t('_wall_added_' . $sType);

        $sContent = '';
        if(!empty($aMediaInfo) && is_array($aMediaInfo) && !empty($aMediaInfo['file']))
            $aContent = array(
                'title' => $aOwner['username'] . ' ' . $sAddedMediaTxt,
                'description' => $aMediaInfo['description'],
                'content' => $this->parseHtmlByTemplateName('common_media', array(
                    'image_url' =>  isset($aMediaInfo['file']) ? $aMediaInfo['file'] : '',
                    'image_width' => isset($aMediaInfo['width']) ? (int)$aMediaInfo['width'] : 0,
                    'image_height' => isset($aMediaInfo['height']) ? (int)$aMediaInfo['height'] : 0,
                    'link' => isset($aMediaInfo['url']) ? $aMediaInfo['url'] : '',
                    'title' => isset($aMediaInfo['title']) ? bx_html_attribute($aMediaInfo['title']) : '',
                    'description' => isset($aMediaInfo['description']) ? $aMediaInfo['description'] : ''
                ))
            );
        else
            $aContent = array('title' => '', 'description' => '', 'content' => $iObject);

        return $aContent;
    }

    function getDefaultComments($iEventId)
    {
    	bx_import('Cmts', $this->_aModule);
        $oComments = new BxTimelineCmts($this->_oConfig->getCommentSystemName(), $iEventId);
        return $oComments->getCommentsFirst('comment');
    }
}

/** @} */ 
