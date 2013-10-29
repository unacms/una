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

    	$this->addCss(array('plugins/jquery/themes/|jquery-ui.css', 'view.css'));
        $this->addJs(array('jquery.ui.all.min.js', 'plugins/|masonry.pkgd.min.js', 'common_anim.js', 'main.js', 'view.js'));
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

		$aEvent['object_owner_id'] = $aEvent['object_id'];

		if(!empty($aEvent['content']))
			$aEvent['content'] = unserialize($aEvent['content']);

		return $this->_getPost(str_replace($sPrefix, '', $aEvent['type']), $aEvent);
    }

	function getSystem($aEvent)
    {
        $aResult = $this->_getSystemData($aEvent);
		if(empty($aResult) || empty($aResult['content']))
			return '';

		if((empty($aEvent['title']) && !empty($aResult['title'])) || (empty($aEvent['description']) && !empty($aResult['description'])))
        	$this->_oDb->updateEvent(array(
            	'title' => bx_process_input($aResult['title'], BX_TAGS_STRIP),
                'description' => bx_process_input($aResult['description'], BX_TAGS_STRIP)
			), array('id' => $aEvent['id']));

		$sComments = '';

		/*
		 * TODO: Comments
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
		} 
        else
        	$sComments = $this->getDefaultComments($aEvent['id']);
		*/

		if(is_string($aResult['content']))
			return $this->parseHtmlByContent($aResult['content'], array(
	            'post_id' => $aEvent['id'],
	            'comments' => $sComments
	        ));

		$aEvent['object_owner_id'] = $aResult['owner_id'];
		$aEvent['content'] = $aResult['content'];

		$sType = !empty($aResult['content_type']) ? $aResult['content_type'] : BX_TIMELINE_PARSE_TYPE_DEFAULT;
        return $this->_getPost($sType, $aEvent);
    }

	protected function _getPost($sType, $aEvent)
    {
		$oModule = $this->getModule();
		$sStylePrefix = $this->_oConfig->getPrefix('style');
		$sJsObject = $this->_oConfig->getJsObject('view');

        list($sAuthorName, $sAuthorUrl, $sAuthorIcon) = $oModule->getUserInfo($aEvent['object_owner_id']);
        $bAuthorIcon = !empty($sAuthorIcon);

        $aTmplVarsMenu = $this->_getTmplVarsItemMenu($aEvent);
        $aTmplVarsTimelineOwner = $this->_getTmplVarsTimelineOwner($aEvent);

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
        	'item_date' => bx_time_js($aEvent['date'])
        );

        $sMethod = '_getTmplVarsContent' . ucfirst($sType);
        if(method_exists($this, $sMethod)) {
        	$aTmplVarsContent = $this->$sMethod($aEvent['content']);
        	if(is_array($aTmplVarsContent))
        		$aTmplVars = array_merge($aTmplVars, $aTmplVarsContent);
        }

        /*
         * TODO: Comments
        bx_import('Cmts', $this->_aModule);
        $oComments = new BxTimelineCmts($this->_oConfig->getCommentSystemName(), $aEvent['id']);
        $aTmplVars = array_merge($aTmplVars, array('comments_content' => $oComments->getCommentsFirst('comment')));
		*/

        return $this->parseHtmlByName($sType . '.html', $aTmplVars);
    }

    protected function _getTmplVarsItemMenu(&$aEvent) {
    	$oModule = $this->getModule();
    	$sStylePrefix = $this->_oConfig->getPrefix('style');
		$sJsObject = $this->_oConfig->getJsObject('view');

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

		return $aTmplVarsMenu;
    }

    protected function _getTmplVarsTimelineOwner(&$aEvent)
    {
    	$oModule = $this->getModule();

    	$aTmplVarsTimelineOwner = array();
        if((int)$aEvent['owner_id'] != (int)$aEvent['object_owner_id']) {
        	list($sTimelineAuthorName, $sTimelineAuthorUrl) = $oModule->getUserInfo($aEvent['owner_id']);

        	$aTmplVarsTimelineOwner = array(
				'owner_url' => $sTimelineAuthorUrl,
        		'owner_username' => $sTimelineAuthorName,
			);
        }

        return $aTmplVarsTimelineOwner;
    }

	protected function _getTmplVarsContentText($aContent)
    {
    	$sStylePrefix = $this->_oConfig->getPrefix('style');
		$sJsObject = $this->_oConfig->getJsObject('view');

		$sUrl = isset($aContent['url']) ? $aContent['url'] : '';
		$sTile = isset($aContent['title']) ? $aContent['title'] : '';

    	$sText = isset($aContent['text']) ? $aContent['text'] : '';
		$sTextMore = '';

		$iMaxLength = $this->_oConfig->getCharsDisplayMax();
		if(strlen($sText) > $iMaxLength) {
			$iLength = strpos($sText, ' ', $iMaxLength);

			$sTextMore = trim(substr($sText, $iLength));
			$sText = trim(substr($sText, 0, $iLength));
		}

		$sText = $this->_prepareTextForOutput($sText);
		$sTextMore = $this->_prepareTextForOutput($sTextMore);

		return array(
			'bx_if:show_image' => array(
				'condition' => false,
            	'content' => array()
			),
        	'bx_if:show_title' => array(
		    	'condition' => !empty($sTile),
            	'content' => array(
					'style_prefix' => $sStylePrefix,
					'item_url' => $sUrl,
					'item_title' => $sTile,
				)
			),
			'bx_if:show_content' => array(
				'condition' => !empty($sText),
		        'content' => array(
		        	'style_prefix' => $sStylePrefix,
		        	'item_content' => $sText,
				    'bx_if:show_more' => array(
				    	'condition' => !empty($sTextMore),
				        'content' => array(
				        	'style_prefix' => $sStylePrefix,
				        	'js_object' => $sJsObject,
				        	'item_content_more' => $sTextMore
				        )
					),
		        )
			)
		);
    }

	protected function _getTmplVarsContentLink($aContent)
    {
    	return $this->_getTmplVarsContentText($aContent);
    }

    protected function _getTmplVarsContentImage($aContent)
    {
    	$aTmplVarsContent = $this->_getTmplVarsContentText($aContent);
    	if(!isset($aContent['image']) || empty($aContent['image']))
    		return $aTmplVarsContent;

		$sStylePrefix = $this->_oConfig->getPrefix('style');

		$sImageUrl = $aContent['image'];
		$sImageTitle = isset($aContent['title']) ? $aContent['title'] : '';

    	return array_merge($aTmplVarsContent, array(
    		'bx_if:show_image' => array(
				'condition' => true,
            	'content' => array(
    				'style_prefix' => $sStylePrefix,
    				'image_url' => $sImageUrl,
    				'image_title' => $sImageTitle,
    			)
			)
    	));
    }

    protected function _getSystemData(&$aEvent)
    {
    	$sHandler = $aEvent['type'] . '_' . $aEvent['action'];
        if(!$this->_oConfig->isHandler($sHandler))
            return '';

		$mixedResult = array();
        $aHandler = $this->_oConfig->getHandlers($sHandler);
        if(empty($aHandler['module_uri']) && empty($aHandler['module_class']) && empty($aHandler['module_method'])) {
            $sMethod = 'display' . str_replace(' ', '', ucwords(str_replace('_', ' ', $aHandler['alert_unit'] . '_' . $aHandler['alert_action'])));
            if(!method_exists($this, $sMethod))
                return '';

            $mixedResult = $this->$sMethod($aEvent);
        }
        else {
            $aEvent['js_mode'] = $this->_oConfig->getJsMode();

            $mixedResult = BxDolService::call($aHandler['module_name'], $aHandler['module_method'], array($aEvent), $aHandler['module_class']);
        }

        return $mixedResult;
    } 
	protected function _prepareTextForOutput($s)
    {
		$s = bx_process_output($s, BX_DATA_TEXT);
		$s = preg_replace("/((https?|ftp|news):\/\/)?([a-z]([a-z0-9\-]*\.)+(aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel|[a-z]{2})|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))(\/[a-z0-9_\-\.~]+)*(\/([a-z0-9_\-\.]*)(\?[a-z0-9+_\-\.%=&amp;]*)?)?(#[a-z][a-z0-9_]*)?/", '<a href="$0" target="_blank">$0</a>', $s);

		return $s; 
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
