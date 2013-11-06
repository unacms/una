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
    function __construct(&$oConfig, &$oDb)
    {
        parent::BxDolModuleTemplate($oConfig, $oDb);
    }

    protected function getModule()
    {
    	$sName = $this->_oConfig->getName();
    	return BxDolModule::getInstance($sName);
    }

	public function getCssJs()
    {
    	$this->addCss(array('plugins/jquery/themes/|jquery-ui.css', 'view.css', 'view-media-tablet.css', 'view-media-desktop.css', 'post.css'));
        $this->addJs(array('jquery.ui.all.min.js', 'jquery.resize.js', 'plugins/|masonry.pkgd.min.js', 'common_anim.js', 'main.js', 'view.js', 'post.js'));
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
            oRequestParams: <?php echo !empty($aRequestParams) ? $oJson->encode($aRequestParams) : '{}'; ?>
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
    	$aFormPhoto = $oModule->getFormPhoto();

        return $this->parseHtmlByName('post.html', array (
            'js_object' => $sJsObject,
        	'js_content' => $this->getJsCode('post', array(
            	'owner_id' => $iOwnerId 
        	)),
            'text_form' => $aFormText['form'],
            'link_form' => $aFormLink['form'],
            'photo_form' => $aFormPhoto['form'],
        	
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

		list($sContent, $sLoadMore) = $this->getPosts(array(
			'type' => 'owner',
			'owner_id' => $iOwnerId, 
		 	'order' => 'desc', 
			'start' => $iStart, 
			'per_page' => $iPerPage, 
			'filter' => $sFilter, 
			'timeline' => $iTimeline, 
			'modules' => $aModules
		));

    	$this->getCssJs();
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

    public function getViewItemBlock($iId)
    {
    	$aEvent = $this->_oDb->getEvents(array('type' => 'id', 'value' => $iId));
    	if(empty($aEvent))
    		return '';

    	$sContent = $this->getJsCode('view');
    	$sContent .= $this->getPost($aEvent, array('type' => 'view_item'));

		$this->getCssJs();
    	return $sContent;
    }

    public function getViewItemPopup($iId)
    {
    	$sContent = $this->getViewItemBlock($iId);

    	bx_import('BxTemplFunctions');
    	return $this->parsePageByName('view_item.html', array(
    		'style_prefix' => $this->_oConfig->getPrefix('style'),
    		'id' => $iId,
    		'content' => BxTemplFunctions::getInstance()->transBox($sContent)
    	));
    }

    public function getPost(&$aEvent, $aParams = array())
    {
    	return !empty($aEvent['action']) ? $this->getSystem($aEvent, $aParams) : $this->getCommon($aEvent, $aParams);
    }

	public function getPosts($aParams)
    {
    	$iStart = $aParams['start'];
    	$iPerPage = $aParams['per_page'];

        $aParamsDb = $aParams;

        //--- Check for Previous
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
        if($bPrevious)
            $aEvent = array_shift($aEvents);

        //--- Check for Next
        $bNext = false;
        if(count($aEvents) > $iPerPage) {
            $aEvent = array_pop($aEvents);
            $bNext = true;
        }

        $iEvents = count($aEvents);
        $sContent = $this->getEmpty($iEvents <= 0);

        foreach($aEvents as $aEvent) {
            $sEvent = $this->getPost($aEvent, $aParams);
            if(empty($sEvent))
                continue;

            $sContent .= $sEvent;
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

    public function getComments($sSystem, $iId)
    {
    	$oModule = $this->getModule();
    	$sStylePrefix = $this->_oConfig->getPrefix('style');

    	$oCmts = $oModule->getCmtsObject($sSystem, $iId);
    	if($oCmts === false)
			return '';

		$aComments = $oCmts->getCommentsBlock(0, 0, false);
		return $this->parseHtmlByName('comments.html', array(
        	'style_prefix' => $sStylePrefix,
			'id' => $iId,
        	'content' => $aComments['content']
        ));
    }

	function getSystem(&$aEvent, $aBrowseParams = array())
    {
        $aResult = $this->_getSystemData($aEvent);
		if(empty($aResult) || empty($aResult['owner_id']) || empty($aResult['content']))
			return '';

		if((empty($aEvent['title']) && !empty($aResult['title'])) || (empty($aEvent['description']) && !empty($aResult['description'])))
        	$this->_oDb->updateEvent(array(
            	'title' => bx_process_input($aResult['title'], BX_TAGS_STRIP),
                'description' => bx_process_input($aResult['description'], BX_TAGS_STRIP)
			), array('id' => $aEvent['id']));

		if(is_string($aResult['content']))
			return $aResult['content'];

		$aEvent['object_owner_id'] = $aResult['owner_id'];
		$aEvent['content'] = $aResult['content'];
		$aEvent['comments'] = $aResult['comments'];

		$sType = !empty($aResult['content_type']) ? $aResult['content_type'] : BX_TIMELINE_PARSE_TYPE_DEFAULT;
        return $this->_getPost($sType, $aEvent, $aBrowseParams);
    }

    public function getCommon(&$aEvent, $aBrowseParams = array())
    {
    	$sPrefix = $this->_oConfig->getPrefix('common_post');
        if(strpos($aEvent['type'], $sPrefix) !== 0)
            return '';

		$oModule = $this->getModule();

		$sType = str_replace($sPrefix, '', $aEvent['type']);
		$aEvent['object_owner_id'] = $aEvent['object_id'];

		switch($sType) {
			case BX_TIMELINE_PARSE_TYPE_TEXT:
			case BX_TIMELINE_PARSE_TYPE_LINK:
				if(!empty($aEvent['content']))
					$aEvent['content'] = unserialize($aEvent['content']);
				break;

			case BX_TIMELINE_PARSE_TYPE_PHOTO:
				$aPhotos = $this->_oDb->getPhotos($aEvent['id']);
				if(!is_array($aPhotos) || empty($aPhotos))
					break;

	        	$aEvent['content'] = array(
	        		'title' => '', 
	        		'text' => ''
	        	);

	        	$aFirst = current($aPhotos);
	        	if($aFirst !== false) {
	        		$aEvent['content']['title'] = isset($aFirst['title']) ? $aFirst['title'] : '';
	        		$aEvent['content']['text'] = isset($aFirst['text']) ? $aFirst['text'] : '';
	        	}

				bx_import('BxDolImageTranscoder');
	        	$oTranscoder = BxDolImageTranscoder::getObjectInstance($this->_oConfig->getObject('transcoder_preview'));

				foreach($aPhotos as $aPhoto)
					$aEvent['content']['images'][] = array(
						'src' => $oTranscoder->getImageUrl($aPhoto['id']),
						'title' => isset($aPhoto['title']) ? $aPhoto['title'] : '' 
					); 

				break;
		}

		$sSystem = $this->_oConfig->getSystemName('comment');
        if($oModule->getCmtsObject($sSystem, $aEvent['id']) !== false)
        	$aEvent['comments'] = array(
        		'system' => $sSystem,
        		'object_id' => $aEvent['id'],
				'count' => $aEvent['comments']
			);

		return $this->_getPost($sType, $aEvent, $aBrowseParams);
    }

	protected function _getPost($sType, $aEvent, $aBrowseParams = array())
    {
		$oModule = $this->getModule();
		$sStylePrefix = $this->_oConfig->getPrefix('style');
		$sJsObject = $this->_oConfig->getJsObject('view');

        list($sAuthorName, $sAuthorUrl, $sAuthorIcon) = $oModule->getUserInfo($aEvent['object_owner_id']);
        $bAuthorIcon = !empty($sAuthorIcon);

        $aTmplVarsMenu = $this->_getTmplVarsItemMenu($aEvent);

        $aTmplVarsTimelineOwner = array();
        if(isset($aBrowseParams['type']) && $aBrowseParams['type'] == 'friends')
        	$aTmplVarsTimelineOwner = $this->_getTmplVarsTimelineOwner($aEvent);

		bx_import('BxDolPermalinks');
		$sViewUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=timeline-item&id=' . $aEvent['id']);

        $aTmplVars = array (
        	'style_prefix' => $sStylePrefix,
        	'js_object' => $sJsObject,
        	'id' => $aEvent['id'],
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
        	'item_view_url' => $sViewUrl,
        	'item_date' => bx_time_js($aEvent['date']),
        	'bx_if:show_comments' => array(
				'condition' => false,
				'content' => array()
			),
			'bx_if:show_votes' => array(
				'condition' => false,
				'content' => array()
			),
			'comments' => ''
        );

        //--- Add Comments
        if(!empty($aEvent['comments']) && is_array($aEvent['comments'])) {
        	$aTmplVarsComments = $this->_getTmplVarsComments($aEvent['comments'], $aBrowseParams);
        	if(is_array($aTmplVarsComments))
        		$aTmplVars = array_merge($aTmplVars, $aTmplVarsComments);
        }

        //--- Add Votes
        $aEvent['votes'] = '';
        $aTmplVarsVotes = $this->_getTmplVarsVotes($aEvent['votes']);
		if(is_array($aTmplVarsVotes))
        	$aTmplVars = array_merge($aTmplVars, $aTmplVarsVotes);

        $sMethod = '_getTmplVarsContent' . ucfirst($sType);
        if(method_exists($this, $sMethod)) {
        	$aTmplVarsContent = $this->$sMethod($aEvent['content']);
        	if(is_array($aTmplVarsContent))
        		$aTmplVars = array_merge($aTmplVars, $aTmplVarsContent);
        }

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

	protected function _getTmplVarsComments($aComments, $aBrowseParams)
    {
    	$sStylePrefix = $this->_oConfig->getPrefix('style');

    	$sSystem = isset($aComments['system']) ? $aComments['system'] : '';
    	$iObjectId = isset($aComments['object_id']) ? (int)$aComments['object_id'] : 0;
    	$iCount = isset($aComments['count']) ? (int)$aComments['count'] : 0;
    	if($sSystem == '' || $iObjectId == 0 || ($iCount == 0 && !isLogged()))
    		return array();

    	$oCmts = $this->getModule()->getCmtsObject($sSystem, $iObjectId);
    	$oCmts->addCssJs();

    	$sComments = '';
    	if(isset($aBrowseParams['type']) && $aBrowseParams['type'] == 'view_item')
			$sComments = $this->getComments($sSystem, $iObjectId);

		return array(
    		'bx_if:show_comments' => array(
    			'condition' => true,
    			'content' => array(
					'style_prefix' => $sStylePrefix,
					'url' => 'javascript:void(0)',
					'onclick' => "javascript:" . $this->_oConfig->getJsObject('view') . ".commentItem(this, '" . $sSystem . "', " . $iObjectId . ")",
					'content' => $iCount > 0 ? _t('_bx_timeline_txt_n_comments', $iCount) : _t('_bx_timeline_txt_comment')
				)
			),
			'comments' => $sComments
		);
    }

	protected function _getTmplVarsVotes($aVotes)
    {
    	$sStylePrefix = $this->_oConfig->getPrefix('style');
/*
    	$sSystem = isset($aComments['system']) ? $aComments['system'] : '';
    	$iObjectId = isset($aComments['object_id']) ? (int)$aComments['object_id'] : 0;
    	$iCount = isset($aComments['count']) ? (int)$aComments['count'] : 0;
    	if($sSystem == '' || $iObjectId == 0 || ($iCount == 0 && !isLogged()))
    		return array();
*/
		return array(
    		'bx_if:show_votes' => array(
    			'condition' => true,
    			'content' => array(
					'style_prefix' => $sStylePrefix,
					'url' => 'javascript:void(0)',
					'onclick' => "javascript:" . $this->_oConfig->getJsObject('view') . ".voteItem(this)",
					'content' => _t('_bx_timeline_txt_plus')
				)
			)
		);
    }

	protected function _getTmplVarsContentText($aContent)
    {
    	$sStylePrefix = $this->_oConfig->getPrefix('style');
		$sJsObject = $this->_oConfig->getJsObject('view');

		$sUrl = isset($aContent['url']) ? $aContent['url'] : '';
		$sTitle = isset($aContent['title']) ? bx_process_input(strip_tags($aContent['title'])) : '';
		if(!empty($sUrl) && !empty($sTitle))
			$sTitle = $this->parseHtmlByName('bx_a.html', array(
				'href' => $sUrl,
				'title' => $sTitle,
				'bx_repeat:attrs' => array(
					array('key' => 'class', 'value' => $sStylePrefix . '-title')
				),
				'content' => $sTitle
			));

    	$sText = isset($aContent['text']) ? bx_process_input(strip_tags($aContent['text'])) : '';
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
			'bx_if:show_images' => array(
				'condition' => false,
            	'content' => array()
			),
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

    protected function _getTmplVarsContentPhoto($aContent)
    {
    	$aTmplVarsContent = $this->_getTmplVarsContentText($aContent);
    	if(!isset($aContent['images']) || empty($aContent['images']))
    		return $aTmplVarsContent;

		$sStylePrefix = $this->_oConfig->getPrefix('style');
		$sTitleDefault = isset($aContent['title']) && !empty($aContent['title']) ? $aContent['title'] : "";

		$aTmplVarsImages = array();
		foreach($aContent['images'] as $aImage) {
			$sTitle = isset($aImage['title']) && !empty($aImage['title']) ? $aImage['title'] : $sTitleDefault;

			$sImage = '';
			if(isset($aImage['src']) && !empty($aImage['src']))
				$sImage = $this->parseHtmlByName('bx_img.html', array(
					'src' => $aImage['src'],
					'bx_repeat:attrs' => array(
						array('key' => 'class', 'value' => $sStylePrefix . '-item-image'),
						array('key' => 'title', 'value' => $sTitle)
					)
				));

			if(!empty($sImage) && (isset($aImage['url']) || isset($aImage['onclick']))) {
				$aAttrs = array();
				if(isset($aImage['onclick']))
					$aAttrs[] = array('key' => 'onclick', 'value' => $aImage['onclick']);

				$sImage = $this->parseHtmlByName('bx_a.html', array(
					'href' => isset($aImage['url']) ? $aImage['url'] : 'javascript:void(0)',
					'title' => $sTitle,
					'bx_repeat:attrs' => $aAttrs,
					'content' => $sImage
				));
			}

			$aTmplVarsImages[] = array(
				'style_prefix' => $sStylePrefix,
				'image' => $sImage
			);
		}
			/*
				$aTmplVarsImages[] = array(
					'style_prefix' => $sStylePrefix,
					'src' => $aImage['src'],
					'title' => isset($aImage['title']) && !empty($aImage['title']) ? $aImage['title'] : $sTitleDefault
				);
			*/

    	return array_merge($aTmplVarsContent, array(
    		'bx_if:show_images' => array(
				'condition' => !empty($aTmplVarsImages),
            	'content' => array(
    				'bx_repeat:images' => $aTmplVarsImages
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
}

/** @} */ 
