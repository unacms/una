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
    	$this->addCss(array('plugins/jquery/themes/|jquery-ui.css', 'view.css', 'view-media-tablet.css', 'view-media-desktop.css', 'post.css', 'share.css'));
        $this->addJs(array('jquery.ui.all.min.js', 'jquery.resize.js', 'plugins/|masonry.pkgd.min.js', 'common_anim.js', 'main.js', 'view.js', 'post.js', 'share.js'));
    }

    public function getJsCode($sType, $aRequestParams = array(), $bWrap = true)
    {
    	$oJson = new Services_JSON();
    	$oModule = $this->getModule();

    	$sBaseUri = $this->_oConfig->getBaseUri();
    	$sJsClass = $this->_oConfig->getJsClass($sType);
    	$sJsObject = $this->_oConfig->getJsObject($sType);
    	$aHtmlIds = $this->_oConfig->getHtmlIds($sType);

        ob_start();
?>
		var <?=$sJsObject; ?> = new <?=$sJsClass; ?>({
			sActionUri: '<?=$sBaseUri; ?>',
            sActionUrl: '<?=BX_DOL_URL_ROOT . $sBaseUri; ?>',
			sObjName: '<?=$sJsObject; ?>',
			iOwnerId: <?=$oModule->_iOwnerId; ?>,
            sAnimationEffect: '<?=$this->_oConfig->getAnimationEffect(); ?>',
            iAnimationSpeed: '<?=$this->_oConfig->getAnimationSpeed(); ?>',
            aHtmlIds: <?=$oJson->encode($aHtmlIds); ?>,
            oRequestParams: <?php echo !empty($aRequestParams) ? $oJson->encode($aRequestParams) : '{}'; ?>
        });
<?php
		$sContent = ob_get_clean();

        return !$bWrap ? $sContent : $this->_wrapInTagJsCode($sContent);
    }

    public function getPostBlock($iOwnerId)
    {
    	$oModule = $this->getModule();
    	$aFormText = $oModule->getFormText();
    	$aFormLink = $oModule->getFormLink();
    	$aFormPhoto = $oModule->getFormPhoto();

        return $this->parseHtmlByName('post.html', array (
            'js_object' => $this->_oConfig->getJsObject('post'),
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

    public function getViewBlock($aParams)
    {
		list($sContent, $sLoadMore, $sBack) = $this->getPosts($aParams);

    	$this->getCssJs();
    	return $this->parseHtmlByName('view.html', array(
    		'style_prefix' => $this->_oConfig->getPrefix('style'),
    		'back' => $sBack,
            'content' => $sContent,
    		'load_more' =>  $sLoadMore,
            'js_content' => $this->getJsCode('view', array(
    			'type' => $aParams['type'],
            	'owner_id' => $aParams['owner_id'], 
            	'start' => $aParams['start'], 
            	'per_page' => $aParams['per_page'], 
            	'filter' => $aParams['filter'],
            	'modules' => $aParams['modules'], 
            	'timeline' => $aParams['timeline'],
			)) . $this->getJsCode('share')
        ));
    }

    public function getViewItemBlock($iId)
    {
    	$aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));
    	if(empty($aEvent))
    		return '';

    	$sContent = $this->getJsCode('view');
    	$sContent .= $this->getPost($aEvent, array('type' => BX_TIMELINE_TYPE_ITEM));

		$this->getCssJs();
    	return $sContent;
    }

    public function getViewItemPopup($iId)
    {
    	return $this->getViewItemBlock($iId);
    }

    public function getPost(&$aEvent, $aBrowseParams = array())
    {
        $aResult = $this->_oConfig->isSystem($aEvent['type'], $aEvent['action']) ? $this->_getSystemData($aEvent) : $this->_getCommonData($aEvent);
		if(empty($aResult) || empty($aResult['owner_id']) || empty($aResult['content']))
			return '';

		list($sUserName) = $this->getModule()->getUserInfo($aResult['owner_id']);

		if(empty($aEvent['title']) || empty($aEvent['description'])) {
			$sTitle = !empty($aResult['title']) ? $aResult['title'] : '';
			if($sTitle == '') {
				$sSample = !empty($aResult['content']['sample']) ? $aResult['content']['sample'] : '_bx_timeline_txt_sample';
				$sTitle = _t('_bx_timeline_txt_user_added_sample', $sUserName, _t($sSample));
			}

			$sDescription = !empty($aResult['description']) ? $aResult['description'] : '';
			if($sDescription == '' && !empty($aResult['content']['text']))
				$sDescription = $aResult['content']['text'];

        	$this->_oDb->updateEvent(array(
            	'title' => bx_process_input(strip_tags($sTitle)),
                'description' => bx_process_input(strip_tags($sDescription))
			), array('id' => $aEvent['id']));
		}

		$aEvent['object_owner_id'] = $aResult['owner_id'];
		$aEvent['content'] = $aResult['content'];
		$aEvent['votes'] = $aResult['votes'];
		$aEvent['comments'] = $aResult['comments'];

		$sType = !empty($aResult['content_type']) ? $aResult['content_type'] : BX_TIMELINE_PARSE_TYPE_DEFAULT;
        return $this->_getPost($sType, $aEvent, $aBrowseParams);
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

        $iYearSel = (int)$aParams['timeline'];
        $iYearMin = $this->_oDb->getMaxDuration($aParams);

        $sBack = $this->getBack($iYearSel);
        $sLoadMore = $this->getLoadMore($iStart, $iPerPage, $bNext, $iYearSel, $iYearMin, $iEvents > 0);
        return array($sContent, $sLoadMore, $sBack);
    }

	public function getEmpty($bVisible)
    {
        return $this->parseHtmlByName('empty.html', array(
        	'style_prefix' => $this->_oConfig->getPrefix('style'),
            'visible' => $bVisible ? 'block' : 'none',
            'content' => MsgBox(_t('_bx_timeline_txt_msg_no_results'))
        ));
    }

	public function getBack($iYearSel)
	{
		if($iYearSel == 0)
			return '';

		$sStylePrefix = $this->_oConfig->getPrefix('style');
    	$sJsObject = $this->_oConfig->getJsObject('view');

		$iYearNow = date('Y', time());
		return $this->parseHtmlByName('back.html', array(
			'style_prefix' => $sStylePrefix,
			'href' => 'javascript:void(0)',
    		'title' => _t('_bx_timeline_txt_jump_to_n_year', $iYearNow),
    		'bx_repeat:attrs' => array(
    			array('key' => 'onclick', 'value' => 'javascript:' . $sJsObject . '.changeTimeline(this, 0)')
    		),
    		'content' => _t('_bx_timeline_txt_jump_to_recent')
		));
	}

	public function getLoadMore($iStart, $iPerPage, $bEnabled, $iYearSel, $iYearMin, $bVisible = true)
    {
    	$sStylePrefix = $this->_oConfig->getPrefix('style');
    	$sJsObject = $this->_oConfig->getJsObject('view');

    	$sYears = '';
    	if(!empty($iYearMin)) {
    		$iYearMax = date('Y', time()) - 1;
    		for($i = $iYearMax; $i >= $iYearMin; $i--)
    			$sYears .= ($i != $iYearSel ? $this->parseHtmlByName('bx_a.html', array(
    				'href' => 'javascript:void(0)',
    				'title' => _t('_bx_timeline_txt_jump_to_n_year', $i),
    				'bx_repeat:attrs' => array(
    					array('key' => 'onclick', 'value' => 'javascript:' . $sJsObject . '.changeTimeline(this, ' . $i . ')')
    				),
    				'content' => $i
    			)) : $i) . ', ';
    		
    		$sYears = substr($sYears, 0, -2);
    	}
    	
        $aTmplVars = array(
        	'style_prefix' => $sStylePrefix,
            'visible' => $bVisible ? 'block' : 'none',
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

	public function getShareElement($iOwnerId, $sType, $sAction, $iObjectId, $aParams = array())
    {
    	$aShared = $this->_oDb->getShared($sType, $sAction, $iObjectId);
    	if(empty($aShared) || !is_array($aShared))
    		return '';

		$bShowDoShareAsButton = isset($aParams['show_do_share_as_button']) && $aParams['show_do_share_as_button'] == true;
		$bShowDoShareIcon = isset($aParams['show_do_share_icon']) && $aParams['show_do_share_icon'] == true;
		$bShowDoShareLabel = isset($aParams['show_do_share_label']) && $aParams['show_do_share_label'] == true;
		$bShowCounter = isset($aParams['show_counter']) && $aParams['show_counter'] === true;

		$sDoShare = '';
		if($bShowDoShareIcon)
			$sDoShare .= $this->parseHtmlByName('bx_icon.html', array('name' => 'share'));

		if($bShowDoShareLabel)
			$sDoShare .= ($sDoShare != '' ? ' ' : '') . _t('_bx_timeline_txt_do_share');

    	$sDoShare = $this->parseHtmlByName('bx_a.html', array(
    		'href' => 'javascript:void(0)',
    		'title' => _t('_bx_timeline_txt_do_share'),
    		'bx_repeat:attrs' => array(
    			array('key' => 'class', 'value' => ($bShowDoShareAsButton ? 'bx-btn' : '')),
    			array('key' => 'onclick', 'value' => $this->getShareJsClick($iOwnerId, $sType, $sAction, $iObjectId))
    		),
    		'content' => $sDoShare
    	));

    	$sStylePrefix = $this->_oConfig->getPrefix('style');
    	return $this->parseHtmlByName('share_element_block.html', array(
    		'style_prefix' => $sStylePrefix,
    		'html_id' => $this->_oConfig->getHtmlIds('share', 'main') . $aShared['id'],
    		'count' => $aShared['shares'],
    		'do_share' => $sDoShare,
    		'bx_if:show_counter' => array(
    			'condition' => $bShowCounter,
    			'content' => array(
    				'style_prefix' => $sStylePrefix,
    				'counter' => $this->getShareCounter($sType, $sAction, $iObjectId)
    			)
    		),
    		'script' => $this->getShareJsScript()
    	));
    }

    public function getShareCounter($sType, $sAction, $iObjectId)
    {
    	$sStylePrefix = $this->_oConfig->getPrefix('style');
    	$sJsObject = $this->_oConfig->getJsObject('share');

    	$aEvent = $this->_oDb->getShared($sType, $sAction, $iObjectId);

    	return $this->parseHtmlByName('share_counter.html', array(
    		'style_prefix' => $sStylePrefix,
    		'js_object' => $sJsObject,
			'html_id_counter' => $this->_oConfig->getHtmlIds('share', 'counter') . $aEvent['id'],
    		'id' => $aEvent['id'],
			'counter' => !empty($aEvent['shares']) && (int)$aEvent['shares'] > 0 ? $aEvent['shares'] : ''
    	));
    }

	public function getSharedBy($iId)
    {
    	$aTmplUsers = array();
    	$oModule = $this->getModule();
    	$sStylePrefix = $this->_oConfig->getPrefix('style');

    	$aUserIds = $this->_oDb->getSharedBy($iId);
    	foreach($aUserIds as $iUserId) {
    		list($sUserName, $sUserUrl, $sUserIcon, $sUserUnit) = $oModule->getUserInfo($iUserId);
			$aTmplUsers[] = array(
				'style_prefix' => $sStylePrefix,
				'user_unit' => $sUserUnit
			);
    	}

		if(empty($aTmplUsers))
			$aTmplUsers = MsgBox(_t('_Empty'));

    	return $this->parseHtmlByName('share_by_list.html', array(
    		'style_prefix' => $sStylePrefix,
    		'bx_repeat:list' => $aTmplUsers
    	));
    }

	public function getShareJsScript()
    {
    	$this->addCss(array('share.css'));
    	$this->addJs(array('main.js', 'share.js'));

		return $this->getJsCode('share');
    }

    public function getShareJsClick($iOwnerId, $sType, $sAction, $iObjectId)
    {
    	$sJsObject = $this->_oConfig->getJsObject('share');
    	$sFormat = "%s.shareItem(this, %d, '%s', '%s', %d);";

    	$iOwnerId = !empty($iOwnerId) ? (int)$iOwnerId : $this->getUserId(); //--- in whose timeline the content will be shared
    	return sprintf($sFormat, $sJsObject, $iOwnerId, $sType, $sAction, (int)$iObjectId);
    }

	protected function _getPost($sType, $aEvent, $aBrowseParams = array())
    {
		$oModule = $this->getModule();
		$sStylePrefix = $this->_oConfig->getPrefix('style');
		$sJsObject = $this->_oConfig->getJsObject('view');

        list($sAuthorName, $sAuthorUrl, $sAuthorIcon) = $oModule->getUserInfo($aEvent['object_owner_id']);
        $bAuthorIcon = !empty($sAuthorIcon);

        $aTmplVarsMenuItemManage = $this->_getTmplVarsMenuItemManage($aEvent, $aBrowseParams);
        $aTmplVarsMenuItemActions = $this->_getTmplVarsMenuItemActions($aEvent, $aBrowseParams);

        $aTmplVarsTimelineOwner = array();
        if(isset($aBrowseParams['type']) && $aBrowseParams['type'] == BX_TIMELINE_TYPE_CONNECTIONS)
        	$aTmplVarsTimelineOwner = $this->_getTmplVarsTimelineOwner($aEvent);

		$bBrowseItem = isset($aBrowseParams['type']) && $aBrowseParams['type'] == BX_TIMELINE_TYPE_ITEM;

        $aTmplVars = array (
        	'style_prefix' => $sStylePrefix,
        	'js_object' => $sJsObject,
        	'class' => $bBrowseItem ? 'bx-tl-view-sizer' : 'bx-tl-grid-sizer',
        	'id' => $aEvent['id'],
        	'bx_if:show_menu_item_manage' => array(
        		'condition' => !empty($aTmplVarsMenuItemManage),
        		'content' => $aTmplVarsMenuItemManage
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
        	'item_view_url' => $this->_oConfig->getItemViewUrl($aEvent),
        	'item_date' => bx_time_js($aEvent['date']),
        	'content' => is_string($aEvent['content']) ? $aEvent['content'] : $this->_getContent($sType, $aEvent['content'], $aBrowseParams),
			'bx_if:show_menu_item_actions' => array(
				'condition' => !empty($aTmplVarsMenuItemActions),
        		'content' => $aTmplVarsMenuItemActions
			),
			'comments' => $bBrowseItem ? $this->_getComments($aEvent['comments']) : '',
			'votes' => $this->_getVotes($aEvent['votes'])
        );

        return $this->parseHtmlByName('item.html', $aTmplVars);
    }

    protected function _getContent($sType, $aContent)
    {
    	$sMethod = '_getTmplVarsContent' . ucfirst($sType);
        if(!method_exists($this, $sMethod)) 
        	return '';

       	$aTmplVars = $this->$sMethod($aContent);
       	return $this->parseHtmlByName($sType . '.html', $aTmplVars);
    }

	protected function _getComments($aComments)
    {
    	$mixedComments = $this->getModule()->getCommentsData($aComments);
    	if($mixedComments === false) 
    		return '';

    	list($sSystem, $iObjectId, $iCount) = $mixedComments;
		return $this->getComments($sSystem, $iObjectId);
    }

	protected function _getVotes($aVotes)
    {
    	$oModule = $this->getModule();

    	$mixedVotes = $oModule->getVotesData($aVotes);
    	if($mixedVotes === false) 
    		return '';

    	list($sSystem, $iObjectId, $iCount) = $mixedVotes;
    	$oVote = $oModule->getVoteObject($sSystem, $iObjectId);
    	if($oVote === false)
			return '';

		return $oVote->getJsScript();
    }

    protected function _getTmplVarsMenuItemManage(&$aEvent) {
    	$oModule = $this->getModule();

		bx_import('BxDolMenu');
		$oMenu = BxDolMenu::getObjectInstance($this->_oConfig->getObject('menu_item_manage'));
		$oMenu->setEvent($aEvent);

		bx_import('BxTemplFunctions');
		$sMenu = BxTemplFunctions::getInstance()->designBoxMenu($oMenu);
		if(empty($sMenu))
			return array();

		return array(
        	'style_prefix' => $this->_oConfig->getPrefix('style'),
        	'js_object' => $this->_oConfig->getJsObject('view'),
			'menu_item_manage' => $sMenu
		);
    }

    protected function _getTmplVarsMenuItemActions(&$aEvent) {
    	$oModule = $this->getModule();

		bx_import('BxDolMenu');
		$oMenu = BxDolMenu::getObjectInstance($this->_oConfig->getObject('menu_item_actions'));
		$oMenu->setEvent($aEvent);

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
			'style_prefix' => $sStylePrefix,
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

    	return array_merge($aTmplVarsContent, array(
    		'bx_if:show_images' => array(
				'condition' => !empty($aTmplVarsImages),
            	'content' => array(
    				'bx_repeat:images' => $aTmplVarsImages
    			)
			)
    	));
    }

	protected function _getTmplVarsContentShare($aContent)
    {
    	$sStylePrefix = $this->_oConfig->getPrefix('style');
    	$sCommonPrefix = $this->_oConfig->getPrefix('common_post');
		$sJsObject = $this->_oConfig->getJsObject('view');

		$sOwnerLink = $this->parseHtmlByName('bx_a.html', array(
			'href' => $aContent['owner_url'],
			'title' => '',
			'bx_repeat:attrs' => array(),
			'content' => $aContent['owner_name']
		));

		$sSample = _t($aContent['sample']);
		$sSampleLink = empty($aContent['url']) ? $sSample : $this->parseHtmlByName('bx_a.html', array(
			'href' => $aContent['url'],
			'title' => '',
			'bx_repeat:attrs' => array(),
			'content' => $sSample
		));

		$sTitle = _t('_bx_timeline_txt_shared', $sOwnerLink, $sSampleLink);
		$sText = $this->_getContent($aContent['parse_type'], $aContent);

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

    protected function _getCommonData(&$aEvent)
    {
    	$oModule = $this->getModule();
    	$sPrefix = $this->_oConfig->getPrefix('common_post');
		$sType = str_replace($sPrefix, '', $aEvent['type']);

    	$aResult = array(
    		'owner_id' => $aEvent['object_id'],
    		'content_type' => $sType,
    		'content' => array(
    			'sample' => '_bx_timeline_txt_common_' . $sType,
    			'url' => $this->_oConfig->getItemViewUrl($aEvent)
    		), //a string to display or array to parse default template before displaying.
    		'votes' => '',
    		'comments' => '',
    		'title' => '', //may be empty.
    		'description' => '' //may be empty. 
    	);

		switch($sType) {
			case BX_TIMELINE_PARSE_TYPE_TEXT:
			case BX_TIMELINE_PARSE_TYPE_LINK:
				if(empty($aEvent['content']))
					break;

				$aResult['content'] = array_merge($aResult['content'], unserialize($aEvent['content']));
				break;

			case BX_TIMELINE_PARSE_TYPE_PHOTO:
				$aPhotos = $this->_oDb->getPhotos($aEvent['id']);
				if(!is_array($aPhotos) || empty($aPhotos))
					break;

	        	$aFirst = current($aPhotos);
	        	if($aFirst !== false) {
	        		$aResult['content']['title'] = isset($aFirst['title']) ? $aFirst['title'] : '';
	        		$aResult['content']['text'] = isset($aFirst['text']) ? $aFirst['text'] : '';
	        	}

				bx_import('BxDolImageTranscoder');
	        	$oTranscoder = BxDolImageTranscoder::getObjectInstance($this->_oConfig->getObject('transcoder_preview'));

				foreach($aPhotos as $aPhoto)
					$aResult['content']['images'][] = array(
						'src' => $oTranscoder->getImageUrl($aPhoto['id']),
						'title' => isset($aPhoto['title']) ? $aPhoto['title'] : '' 
					); 
				break;

			case BX_TIMELINE_PARSE_TYPE_SHARE:
				if(empty($aEvent['content']))
					break;

				$aContent = unserialize($aEvent['content']);

				if(!$this->_oConfig->isSystem($aContent['type'] , $aContent['action'])) {
					$aShared = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $aContent['object_id']));
					$aShared = $this->_getCommonData($aShared);
				}
				else
					$aShared = $this->_getSystemData($aContent); 

				$aResult['content'] = array_merge($aContent, $aShared['content']);
				$aResult['content']['parse_type'] = $aShared['content_type'];
				$aResult['content']['owner_id'] = $aShared['owner_id'];
				list($aResult['content']['owner_name'], $aResult['content']['owner_url']) = $oModule->getUserInfo($aShared['owner_id']);

				list($sUserName) = $oModule->getUserInfo($aEvent['object_id']);
				$sSample = !empty($aResult['content']['sample']) ? $aResult['content']['sample'] : '_bx_timeline_txt_sample';

				$aResult['title'] = _t('_bx_timeline_txt_user_shared_sample', $sUserName, $aResult['content']['owner_name'], _t($sSample));
				$aResult['description'] = '';
				break;
		}

		$sSystem = $this->_oConfig->getSystemName('vote');
        if($oModule->getVoteObject($sSystem, $aEvent['id']) !== false)
        	$aResult['votes'] = array(
        		'system' => $sSystem,
        		'object_id' => $aEvent['id'],
				'count' => $aEvent['votes']
			);

		$sSystem = $this->_oConfig->getSystemName('comment');
        if($oModule->getCmtsObject($sSystem, $aEvent['id']) !== false)
        	$aResult['comments'] = array(
        		'system' => $sSystem,
        		'object_id' => $aEvent['id'],
				'count' => $aEvent['comments']
			);

		return $aResult;
    }

	protected function _prepareTextForOutput($s)
    {
		$s = bx_process_output($s, BX_DATA_TEXT);
		$s = preg_replace("/((https?|ftp|news):\/\/)?([a-z]([a-z0-9\-]*\.)+(aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel|[a-z]{2})|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))(\/[a-z0-9_\-\.~]+)*(\/([a-z0-9_\-\.]*)(\?[a-z0-9+_\-\.%=&amp;]*)?)?(#[a-z][a-z0-9_]*)?/", '<a href="$0" target="_blank">$0</a>', $s);

		return $s; 
    }
}

/** @} */ 
