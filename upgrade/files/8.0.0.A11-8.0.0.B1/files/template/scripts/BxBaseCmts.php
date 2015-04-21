<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @see BxDolCmts
 */
class BxBaseCmts extends BxDolCmts
{
    protected $_sJsObjName;
    protected $_sStylePrefix;

    function __construct( $sSystem, $iId, $iInit = 1 )
    {
        parent::__construct( $sSystem, $iId, $iInit );
        if (empty($sSystem))
            return;

        $this->_sJsObjName = 'oCmts' . bx_gen_method_name($sSystem, array('_' , '-')) . $iId;
        $this->_sStylePrefix = isset($this->_aSystem['root_style_prefix']) ? $this->_aSystem['root_style_prefix'] : 'cmt';

        BxDolTemplate::getInstance()->addJsTranslation('_sys_txt_cmt_loading');
    }

    /**
     * Add comments CSS/JS
     */
    public function addCssJs ()
    {
        $oTemplate = BxDolTemplate::getInstance();

        $oTemplate->addCss(array('cmts.css'));
        $oTemplate->addJs(array('jquery.anim.js', 'jquery.form.min.js', 'BxDolCmts.js'));

        $oForm = BxDolForm::getObjectInstance($this->_sFormObject, $this->_sFormDisplayPost);
        $oForm->addCssJs();
    }

    public function getJsObjectName()
    {
        return $this->_sJsObjName;
    }

    /**
     * Get initialization section of comments box
     *
     * @return string
     */
    public function getJsScript()
    {
        $aParams = array(
            'sObjName' => $this->_sJsObjName,
            'sRootUrl' => BX_DOL_URL_ROOT,
            'sSystem' => $this->getSystemName(),
            'iAuthorId' => $this->_getAuthorId(),
            'iObjId' => $this->getId(),
        	'sBaseUrl' => $this->getBaseUrl(),
            'sPostFormPosition' => $this->_aSystem['post_form_position'],
            'sBrowseType' => $this->_sBrowseType,
            'sDisplayType' => $this->_sDisplayType
        );

        $this->addCssJs();
        return BxDolTemplate::getInstance()->_wrapInTagJsCode("var " . $this->_sJsObjName . " = new BxDolCmts(" . json_encode($aParams) . ");");
    }

    /**
     * get full comments block with initializations
     */
    function getCommentsBlock($iParentId = 0, $iVParentId = 0, $bInDesignbox = true)
    {
    	$aBp = $aDp = array();
		$this->_getParams($aBp, $aDp);

        $aBp['parent_id'] = $iParentId;
        $aBp['vparent_id'] = $iVParentId;
        $aDp['show_empty'] = false;

		//add live update
		$this->actionResumeLiveUpdate();

		$sServiceCall = BxDolService::getSerializedService('system', 'get_live_updates_comments', array($this->_sSystem, $this->_iId, $this->_getAuthorId(), '{count}'), 'TemplCmtsServices');
		BxDolLiveUpdates::getInstance()->add($this->_sSystem . '_live_updates_cmts_' . $this->_iId, 1, $sServiceCall);
		//add live update

        $sCaption = _t('_cmt_block_comments_title', $this->getCommentsCountAll());
        $sContent = BxDolTemplate::getInstance()->parseHtmlByName('comments_block.html', array(
            'system' => $this->_sSystem,
            'list_anchor' => $this->getListAnchor(),
            'id' => $this->getId(),
            'comments' => $this->getComments($aBp, $aDp),
            'post_form_top' => $this->getFormBoxPost($aBp, array('type' => $this->_sDisplayType, 'position' => BX_CMT_PFP_TOP)),
            'post_form_bottom'  => $this->getFormBoxPost($aBp, array('type' => $this->_sDisplayType, 'position' => BX_CMT_PFP_BOTTOM)),
        	'view_image_popup' => $this->_getViewImagePopup(),
            'script' => $this->getJsScript()
        ));

        return $bInDesignbox ? DesignBoxContent($sCaption, $sContent, BX_DB_DEF, $this->_getControlsBox()) : array(
            'title' => $sCaption,
            'content' => $sContent,
            'menu' => $this->_getControlsBox(),
        );
    }

    /**
     * get comments list for specified parent comment
     *
     * @param array $aBp - browse params array
     * @param array $aDp - display params array
     *
     */
    function getComments($aBp = array(), $aDp = array())
    {
        $this->_prepareParams($aBp, $aDp);

        $aCmts = $this->getCommentsArray($aBp['vparent_id'], $aBp['filter'], $aBp['order'], $aBp['start'], $aBp['per_view']);
        if(empty($aCmts) || !is_array($aCmts)) {
        	if((int)$aBp['parent_id'] == 0 && !isLogged())	{
        		$oPermalink = BxDolPermalinks::getInstance();
        		return MsgBox(_t('_cmt_msg_login_required', $oPermalink->permalink('page.php?i=login'), $oPermalink->permalink('page.php?i=create-account')));
        	}

            return isset($aDp['show_empty']) && $aDp['show_empty'] === true ? $this->_getEmpty() : '';
        }

        $sCmts = '';
        foreach($aCmts as $k => $aCmt)
            $sCmts .= $this->getComment($aCmt, $aBp, $aDp);

        $sCmts = $this->_getMoreLink($sCmts, $aBp, $aDp);
        return $sCmts;
    }

    /**
     * get comment view block with initializations
     */
    function getCommentBlock($iCmtId = 0, $aBp = array(), $aDp = array())
    {
        return BxDolTemplate::getInstance()->parseHtmlByName('comment_block.html', array(
            'system' => $this->_sSystem,
            'id' => $this->getId(),
            'comment' => $this->getComment(
        		$iCmtId, 
        		array_merge(array('type' => $this->_sBrowseType), $aBp), 
        		array_merge(array('type' => BX_CMT_DISPLAY_THREADED), $aDp)
        	),
        	'view_image_popup' => $this->_getViewImagePopup(), 
            'script' => $this->getJsScript()
        ));
    }

    /**
     * get one just posted comment
     *
     * @param  int    $iCmtId - comment id
     * @return string
     */
    function getComment($mixedCmt, $aBp = array(), $aDp = array())
    {
        $oTemplate = BxDolTemplate::getInstance();

        $iUserId = $this->_getAuthorId();
        $aCmt = !is_array($mixedCmt) ? $this->getCommentRow((int)$mixedCmt) : $mixedCmt;

        list($sAuthorName, $sAuthorLink, $sAuthorIcon) = $this->_getAuthorInfo($aCmt['cmt_author_id']);

        $sClass = '';
        if(isset($aCmt['vote_rate']) && (float)$aCmt['vote_rate'] < $this->_aSystem['viewing_threshold']) {
            $oTemplate->pareseHtmlByName('comment_hidden.html', array(
                'js_object' => $this->_sJsObjName,
                'id' => $aCmt['cmt_id'],
                'title' => bx_process_output(_t('_hidden_comment', $sAuthorName)),
                'bx_if:show_replies' => array(
                    'condition' => $aCmt['cmt_replies'] > 0,
                    'content' => array(
                        'replies' => _t('_Show N replies', $aCmt['cmt_replies'])
                    )
                )
            ));

            $sClass = ' cmt-hidden';
        }

        if($aCmt['cmt_author_id'] == $iUserId)
            $sClass .= ' cmt-mine';

		if(!empty($aDp['blink']) && in_array($aCmt['cmt_id'], $aDp['blink']))
			$sClass .= ' cmt-blink';

        $sActions = $this->_getActionsBox($aCmt, $aDp);

        $aTmplReplyTo = array();
        if((int)$aCmt['cmt_parent_id'] != 0) {
            $aParent = $this->getCommentRow($aCmt['cmt_parent_id']);
            list($sParAuthorName, $sParAuthorLink, $sParAuthorIcon) = $this->_getAuthorInfo($aParent['cmt_author_id']);

            $aTmplReplyTo = array(
                'style_prefix' => $this->_sStylePrefix,
                'par_cmt_link' => $this->getBaseUrl() . '#' . $this->_sSystem . $aCmt['cmt_parent_id'],
            	'par_cmt_title' => bx_html_attribute(_t('_in_reply_to', $sParAuthorName)),
                'par_cmt_author' => $sParAuthorName
            );
        }

        $aTmplImages = array();
        if($this->isAttachImageEnabled()) {
            $aImages = $this->_oQuery->getImages($this->_aSystem['system_id'], $aCmt['cmt_id']);
            if(!empty($aImages) && is_array($aImages)) {
        		$oStorage = BxDolStorage::getObjectInstance($this->getStorageObjectName());

                $oTranscoder = BxDolTranscoderImage::getObjectInstance($this->getTranscoderPreviewName());

                foreach($aImages as $aImage)
                    $aTmplImages[] = array(
                        'style_prefix' => $this->_sStylePrefix,
                        'js_object' => $this->_sJsObjName,
                        'image' => $oTranscoder->getFileUrl($aImage['image_id']),
                    	'image_orig' => $oStorage->getFileUrlById($aImage['image_id'])
                    );
            }
        }

        $sReplies = '';
        if((int)$aCmt['cmt_replies'] > 0 && !empty($aDp) && $aDp['type'] == BX_CMT_DISPLAY_THREADED) {
        	$aDp['show_empty'] = false;
            $sReplies = $this->getComments(array('parent_id' => $aCmt['cmt_id'], 'vparent_id' => $aCmt['cmt_id'], 'type' => $aBp['type']), $aDp);
        }

		$sAgo = bx_time_js($aCmt['cmt_time']);
        $bObjectTitle = !empty($this->_aSystem['trigger_field_title']);
        return $oTemplate->parseHtmlByName('comment.html', array_merge(array(
            'system' => $this->_sSystem,
            'style_prefix' => $this->_sStylePrefix,
            'js_object' => $this->_sJsObjName,
            'id' => $aCmt['cmt_id'],
            'class' => $sClass,
            'bx_if:show_reply_to' => array(
                'condition' => !empty($aTmplReplyTo),
                'content' => $aTmplReplyTo
            ),
            'bx_if:show_ago_link' => array(
                'condition' => $bObjectTitle,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'view_link' => $this->getViewUrl($aCmt['cmt_id']),
                    'ago' => $sAgo
                )
            ),
            'bx_if:show_ago_text' => array(
                'condition' => !$bObjectTitle,
                'content' => array(
                    'ago' => $sAgo
                )
            ),
            'bx_if:show_attached' => array(
                'condition' => !empty($aTmplImages),
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'bx_repeat:attached' => $aTmplImages
                )
            ),
            'actions' => $sActions,
            'replies' =>  $sReplies
        ), $this->_getTmplVarsAuthor($aCmt), $this->_getTmplVarsText($aCmt)));
    }

	/**
     * get one comment for "Live Search"
     *
     * @param  int    $iCmtId - comment id
     * @return string
     */
    function getCommentLiveSearch($mixedCmt, $aParams = array())
    {
        $aCmt = !is_array($mixedCmt) ? $this->getCommentRow((int)$mixedCmt) : $mixedCmt;

        list($sAuthorName, $sAuthorLink, $sAuthorIcon) = $this->_getAuthorInfo($aCmt['cmt_author_id']);
        $bAuthorIcon = !empty($sAuthorIcon);

        $sViewLink = $this->getViewUrl($aCmt['cmt_id']);

        return BxDolTemplate::getInstance()->parseHtmlByName('comment_live_search.html', array(
            'bx_if:show_icon' => array(
                'condition' => $bAuthorIcon,
                'content' => array(
                    'author_icon' => $sAuthorIcon,
        			'view_link' => $sViewLink
                )
            ),
            'bx_if:show_icon_empty' => array(
                'condition' => !$bAuthorIcon,
                'content' => array()
            ),
            'view_link' => $sViewLink,
            'text' => BxTemplFunctions::getInstance()->getStringWithLimitedLength(strip_tags($aCmt['cmt_text']), $this->_sSnippetLenthLiveSearch),
            'sample' => isset($aParams['txt_sample_single']) ? _t($aParams['txt_sample_single']) : ''
        ));
    }

    function getFormBoxPost($aBp = array(), $aDp = array())
    {
        return $this->_getFormBox(BX_CMT_ACTION_POST, $aBp, $aDp);
    }

    function getFormBoxEdit($aBp = array(), $aDp = array())
    {
        return $this->_getFormBox(BX_CMT_ACTION_EDIT, $aBp, $aDp);
    }

    function getFormPost($iCmtParentId = 0)
    {
        return $this->_getFormPost($iCmtParentId);
    }

    function getFormEdit($aCmt)
    {
        return $this->_getFormEdit($aCmt);
    }

    function getNotification($iCountOld = 0, $iCountNew = 0)
    {
    	$iCount = (int)$iCountNew - (int)$iCountOld;

		$aComments = $this->_oQuery->getCommentsBy(array('type' => 'latest', 'object_id' => $this->_iId, 'author' => $this->_getAuthorId(), 'others' => 1, 'start' => '0', 'per_page' => $iCount));
		if(empty($aComments) || !is_array($aComments))
			return '';

		$sJsObject = $this->getJsObjectName();

		$aComments = array_reverse($aComments);
		$iComments = count($aComments);

		$aTmplVarsNotifs = array();
		foreach($aComments as $iIndex => $aComment) {
			$iCommentId = $aComment['cmt_id'];

			$sShowOnClick = "javascript:" . $sJsObject . ".goTo(this, '" . $this->_sSystem . $iCommentId . "', '" . $iCommentId . "');";
			$sReplyOnClick = "javascript:" . $sJsObject . ".goToAndReply(this, '" . $this->_sSystem . $iCommentId . "', '" . $iCommentId . "');";

	    	$aTmplVarsNotifs[] = array_merge(array(
	    		'style_prefix' => $this->_sStylePrefix,
	    		'js_object' => $sJsObject,
	    		'bx_if:show_as_hidden' => array(
	    			'condition' => $iIndex < ($iComments - 1),
	    			'content' => array(),
	    		),
				'show_onclick' => $sShowOnClick,
	    		'reply_onclick' => $sReplyOnClick,
	    		'bx_if:show_previous' => array(
	    			'condition' => $iIndex > 0,
	    			'content' => array(
	    				'style_prefix' => $this->_sStylePrefix,
	    				'js_object' => $sJsObject
	    			)
	    		)
			), $this->_getTmplVarsAuthor($aComment), $this->_getTmplVarsText($aComment));
		}

		return BxDolTemplate::getInstance()->parseHtmlByName('comments_notification.html', array(
			'html_id' => $this->getNotificationId(),
			'style_prefix' => $this->_sStylePrefix,
			'bx_repeat:notifs' => $aTmplVarsNotifs
		));
    }

    /**
     * private functions
     */
    protected function _getControlsBox()
    {
        $oTemplate = BxDolTemplate::getInstance();

        $sDisplay = '';
        $bDisplay = (int)$this->_aSystem['is_display_switch'] == 1;
        if($bDisplay) {
            $aDisplayLinks = array(
                array('id' => $this->_sSystem . '-flat', 'name' => $this->_sSystem . '-flat', 'class' => '', 'title' => '_cmt_display_flat', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeDisplay(this, \'flat\');'),
                array('id' => $this->_sSystem . '-threaded', 'name' => $this->_sSystem . '-threaded', 'class' => '', 'title' => '_cmt_display_threaded', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeDisplay(this, \'threaded\');')
            );

            $oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive_vertical.html', 'menu_id'=> $this->_sSystem . '-display', 'menu_items' => $aDisplayLinks));
            $oMenu->setSelected('', $this->_sSystem . '-' . $this->_sDisplayType);
            $sDisplay = $oMenu->getCode();
        }

        $sBrowseType = '';
        $bBrowseType = (int)$this->_aSystem['is_browse_switch'] == 1;
        if($bBrowseType) {
            $aBrowseLinks = array(
                array('id' => $this->_sSystem . '-tail', 'name' => $this->_sSystem . '-tail', 'class' => '', 'title' => '_cmt_browse_tail', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeBrowse(this, \'tail\');'),
                array('id' => $this->_sSystem . '-head', 'name' => $this->_sSystem . '-head', 'class' => '', 'title' => '_cmt_browse_head', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeBrowse(this, \'head\');'),
                array('id' => $this->_sSystem . '-popular', 'name' => $this->_sSystem . '-popular', 'class' => '', 'title' => '_cmt_browse_popular', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeBrowse(this, \'popular\');'),
            );

            $oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive_vertical.html', 'menu_id'=> $this->_sSystem . '-browse', 'menu_items' => $aBrowseLinks));
            $oMenu->setSelected('', $this->_sSystem . '-' . $this->_sBrowseType);
            $sBrowseType = $oMenu->getCode();
        }

        $sBrowseFilter = '';
        $bBrowseFilter = (int)$this->_aSystem['is_browse_filter'] == 1;
        if($bBrowseFilter) {
            $aFilterLinks = array(
                array('id' => $this->_sSystem . '-all', 'name' => $this->_sSystem . '-all', 'class' => '', 'title' => '_cmt_browse_all', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeFilter(this, \'all\');'),
                array('id' => $this->_sSystem . '-friends', 'name' => $this->_sSystem . '-friends', 'class' => '', 'title' => '_cmt_browse_friends', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeFilter(this, \'friends\');'),
                array('id' => $this->_sSystem . '-subscriptions', 'name' => $this->_sSystem . '-subscriptions', 'class' => '', 'title' => '_cmt_browse_subscriptions', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeFilter(this, \'subscriptions\');')
            );

            $oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive_vertical.html', 'menu_id'=> $this->_sSystem . '-filter', 'menu_items' => $aFilterLinks));
            $oMenu->setSelected('', $this->_sSystem . '-' . $this->_sBrowseFilter);
            $sBrowseFilter = $oMenu->getCode();
        }

        return $oTemplate->parseHtmlByName('comments_controls.html', array(
            'display_switcher' => $bDisplay ? $sDisplay : '',
            'bx_if:is_divider_1' => array(
                'condition' => $bDisplay && $bBrowseType,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                )
            ),
            'browse_switcher' => $bBrowseType ? $sBrowseType : '',
            'bx_if:is_divider_2' => array(
                'condition' => $bBrowseType && $bBrowseFilter,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                )
            ),
            'filter_switcher' => $bBrowseFilter ? $sBrowseFilter : '',
        ));
    }

    protected function _getActionsBox(&$aCmt, $aDp = array())
    {
    	$oTemplate = BxDolTemplate::getInstance();

    	$bViewOnly = isset($aDp['view_only']) && $aDp['view_only'] === true;

        $bMenuManage = false;
        $sMenuActions = $sMenuManage = '';

		if(!$bViewOnly) {
	        //--- Actions Menu
	        $oMenuActions = BxDolMenu::getObjectInstance($this->_sMenuObjActions);
	        $oMenuActions->setCmtsData($this, $aCmt['cmt_id']);
	        $sMenuActions = $oMenuActions->getCode();

	        $oVote = $this->getVoteObject($aCmt['cmt_id']);
	        if($oVote !== false)
	            $sMenuActions .= $oVote->getJsScript();
	
	        //--- Manage Menu
	        $oMenuManage = BxDolMenu::getObjectInstance($this->_sMenuObjManage);
	        $oMenuManage->setCmtsData($this, $aCmt['cmt_id']);
	
	        $sMenuManage = $oMenuManage->getCode();
	        $bMenuManage = !empty($sMenuManage);
	        if($bMenuManage) {
	            $sMenuManage = $oTemplate->parseHtmlByName('comment_manage.html', array(
	                'style_prefix' => $this->_sStylePrefix,
	                'content' => $sMenuManage
	            ));
	
	            $sMenuManage = BxTemplFunctions::getInstance()->transBox($this->_sSystem . '-manage-' . $aCmt['cmt_id'], $sMenuManage, true);
	        }
		}

        return $oTemplate->parseHtmlByName('comment_actions.html', array(
            'id' => $aCmt['cmt_id'],
            'js_object' => $this->_sJsObjName,
            'style_prefix' => $this->_sStylePrefix,
            'menu_actions' => $sMenuActions,
/*
            'bx_if:hide_rate_count' => array(
                'condition' => (int)$aCmt['cmt_rate'] <= 0,
                'content' => array()
            ),
            'points' => _t(in_array($aCmt['cmt_rate'], array(-1, 0, 1)) ? '_N_point' : '_N_points', $aCmt['cmt_rate']),
*/
            'bx_if:show_menu_manage' => array(
                'condition' => $bMenuManage,
                'content' => array(
                    'js_object' => $this->_sJsObjName,
                    'style_prefix' => $this->_sStylePrefix,
                    'id' => $aCmt['cmt_id'],
                    'popup_text' => $sMenuManage
                )
            )
        ));
    }

    protected function _getFormBox($sType, $aBp, $aDp)
    {
        $iCmtParentId = isset($aBp['parent_id']) ? (int)$aBp['parent_id'] : 0;
        $sPosition = isset($aDp['position']) ? $aDp['position'] : '';

        if(!$this->isPostReplyAllowed())
            return '';

        $sPositionSystem = $this->_aSystem['post_form_position'];
        if(!empty($sPosition) && $sPositionSystem != $sPosition)
            return '';

        $sMethod = '_getForm' . ucfirst($sType);
        $aForm = $this->$sMethod($iCmtParentId);

        return BxDolTemplate::getInstance()->parseHtmlByName('comment_reply_box.html', array(
            'js_object' => $this->_sJsObjName,
            'style_prefix' => $this->_sStylePrefix,
            'bx_if:show_class' => array(
                'condition' => !empty($sPosition),
                'content' => array(
                    'class' => $this->_sStylePrefix . '-reply-' . $sPosition
                )
            ),
            'form' => $aForm['form'],
            'form_id' => $aForm['form_id'],
        ));
    }

    protected function _getFormPost($iCmtParentId = 0)
    {
        $oForm = $this->_getForm(BX_CMT_ACTION_POST, $iCmtParentId);
        $oForm->aInputs['cmt_parent_id']['value'] = $iCmtParentId;
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $iCmtAuthorId = $this->_getAuthorId();
            $iCmtParentId = $oForm->getCleanValue('cmt_parent_id');
            $sCmtText = $oForm->getCleanValue('cmt_text');
            $sCmtText = $this->_prepareTextForSave ($sCmtText);
            $oForm->setSubmittedValue('cmt_text', $sCmtText, $oForm->aFormAttrs['method']);

            $iLevel = 0;
            $iCmtVisualParentId = 0;
            if((int)$iCmtParentId > 0) {
                $aParent = $this->getCommentRow($iCmtParentId);

                $iLevel = (int)$aParent['cmt_level'] + 1;
                $iCmtVisualParentId = $iLevel > $this->getMaxLevel() ? $aParent['cmt_vparent_id'] : $iCmtParentId;
            }

            $iCmtId = (int)$oForm->insert(array('cmt_vparent_id' => $iCmtVisualParentId, 'cmt_object_id' => $this->_iId, 'cmt_author_id' => $iCmtAuthorId, 'cmt_level' => $iLevel, 'cmt_time' => time()));
            if($iCmtId != 0) {
                if($this->isAttachImageEnabled()) {
                    $aImages = $oForm->getCleanValue('cmt_image');
                    if(!empty($aImages) && is_array($aImages)) {
                        $oStorage = BxDolStorage::getObjectInstance($this->getStorageObjectName());

                        foreach($aImages as $iImageId)
                            if($this->_oQuery->saveImages($this->_aSystem['system_id'], $iCmtId, $iImageId))
                                $oStorage->afterUploadCleanup($iImageId, $iCmtAuthorId);
                    }
                }

                if($iCmtParentId) {
                    $this->_oQuery->updateRepliesCount($iCmtParentId, 1);

                    $this->_sendNotificationEmail($iCmtId, $iCmtParentId);
                }

                $this->_triggerComment();

                $this->isPostReplyAllowed(true);

                if ($this->_sMetatagsObj) {
                    $oMetatags = BxDolMetatags::getObjectInstance($this->_sMetatagsObj);
                    $oMetatags->keywordsAdd($this->_oQuery->getUniqId($this->_aSystem['system_id'], $iCmtId), $sCmtText);
                }

                $oZ = new BxDolAlerts($this->_sSystem, 'commentPost', $this->getId(), $iCmtAuthorId, array('comment_id' => $iCmtId, 'comment_author_id' => $iCmtAuthorId));
                $oZ->alert();

                return array('id' => $iCmtId, 'parent_id' => $iCmtParentId);
            }

            return array('msg' => _t('_cmt_err_cannot_perform_action'));
        }

        return array('form' => $oForm->getCode(), 'form_id' => $oForm->id);
    }

    protected function _getFormEdit($iCmtId)
    {
        $aCmt = $this->_oQuery->getCommentSimple ($this->getId(), $iCmtId);
        if(!$aCmt)
            return array('msg' => _t('_No such comment'));

        $iCmtAuthorId = $this->_getAuthorId();
        if(!$this->isEditAllowed($aCmt))
            return array('msg' => $aCmt['cmt_author_id'] == $iCmtAuthorId ? strip_tags($this->msgErrEditAllowed()) : _t('_Access denied'));

        $oForm = $this->_getForm(BX_CMT_ACTION_EDIT, $aCmt['cmt_id']);
        $aCmt['cmt_text'] = $this->_prepareTextForEdit($aCmt['cmt_text']);

        $oForm->initChecker($aCmt);
        if($oForm->isSubmittedAndValid()) {
            $sCmtText = $oForm->getCleanValue('cmt_text');
            $sCmtText = $this->_prepareTextForSave ($sCmtText);
            $oForm->setSubmittedValue('cmt_text', $sCmtText, $oForm->aFormAttrs['method']);

            if($oForm->update($iCmtId)) {

                $this->isEditAllowed(true);

                if ($this->_sMetatagsObj) {
                    $oMetatags = BxDolMetatags::getObjectInstance($this->_sMetatagsObj);
                    $oMetatags->keywordsAdd($this->_oQuery->getUniqId($this->_aSystem['system_id'], $iCmtId), $sCmtText);
                }

                $oZ = new BxDolAlerts($this->_sSystem, 'commentUpdated', $this->getId(), $iCmtAuthorId, array('comment_id' => $aCmt['cmt_id'], 'comment_author_id' => $aCmt['cmt_author_id']));
                $oZ->alert();

                return array('id' => $iCmtId, 'text' => $this->_prepareTextForOutput($sCmtText, $iCmtId));
            }

            return array('msg' => _t('_cmt_err_cannot_perform_action'));
        }

        return array('form' => $oForm->getCode(), 'form_id' => $oForm->id);
    }

    protected function _getForm($sAction, $iId)
    {
        $oForm = $this->_getFormObject($sAction);
        $oForm->setId(sprintf($oForm->aFormAttrs['id'], $sAction, $this->_sSystem, $iId));
        $oForm->setName(sprintf($oForm->aFormAttrs['name'], $sAction, $this->_sSystem, $iId));
        $oForm->aParams['db']['table'] = $this->_aSystem['table'];
        $oForm->aInputs['sys']['value'] = $this->_sSystem;
        $oForm->aInputs['id']['value'] = $this->_iId;
        $oForm->aInputs['action']['value'] = 'Submit' . ucfirst($sAction) . 'Form';

        if(!$this->isAttachImageEnabled())
            unset($oForm->aInputs['cmt_image']);

        if(isset($oForm->aInputs['cmt_text'])) {
            $iCmtTextMin = (int)$this->_aSystem['chars_post_min'];
            $iCmtTextMax = (int)$this->_aSystem['chars_post_max'];

            $oForm->aInputs['cmt_text']['checker']['params'] = array($iCmtTextMin, $iCmtTextMax);
            $oForm->aInputs['cmt_text']['checker']['error'] = _t('_Please enter n1-n2 characters', $iCmtTextMin, $iCmtTextMax);
        }

        return $oForm;
    }

    protected function _getMoreLink($sCmts, $aBp = array(), $aDp = array())
    {
        $iStart = $iPerView = 0;
        switch($aBp['type']) {
            case BX_CMT_BROWSE_HEAD:
            case BX_CMT_BROWSE_POPULAR:
            case BX_CMT_BROWSE_CONNECTION:
                $iPerView = $aBp['per_view'];

                $iStart = $aBp['start'] + $iPerView;
                if($iStart >= $aBp['count'])
                    return $sCmts;

                break;

            case BX_CMT_BROWSE_TAIL:
                $iPerView = $aBp['per_view'];

                $iStart = $aBp['start'] - $iPerView;
                if($iStart < 0) {
                    $iPerView += $iStart;
                    $iStart = 0;
                }

                if($iStart == 0 && $iPerView == 0)
                    return $sCmts;

                break;
        }

        $bRoot = (int)$aBp['vparent_id'] <= 0;

        $sMore = BxDolTemplate::getInstance()->parseHtmlByName('comment_more.html', array(
            'js_object' => $this->_sJsObjName,
            'style_prefix' => $this->_sStylePrefix,
            'bx_if:is_root' => array(
                'condition' => $bRoot,
                'content' => array()
            ),
            'parent_id' => $aBp['vparent_id'],
            'start' => $iStart,
            'per_view' => $iPerView,
            'title' => _t('_cmt_load_more_' . ($aBp['vparent_id'] == 0 ? 'comments' : 'replies') . '_' . $aBp['type'])
        ));

        switch($aBp['type']) {
            case BX_CMT_BROWSE_HEAD:
                case BX_CMT_BROWSE_POPULAR:
                case BX_CMT_BROWSE_CONNECTION:
                $sCmts .= $sMore;
                break;

            case BX_CMT_BROWSE_TAIL:
                $sCmts = $sMore . $sCmts;
                break;
        }

        return $sCmts;
    }

    protected function _getEmpty()
    {
        return BxDolTemplate::getInstance()->parseHtmlByName('comment_empty.html', array(
            'style_prefix' => $this->_sStylePrefix
        ));
    }

    protected function _getViewImagePopup()
    {
    	$sViewImagePopupId = 'cmts-box-' . $this->_sSystem . '-' . $this->getId() . '-view-image-popup' ;
        $sViewImagePopupContent = BxDolTemplate::getInstance()->parseHtmlByName('popup_image.html', array(
    		'image_url' => ''
    	));

    	return BxTemplFunctions::getInstance()->transBox($sViewImagePopupId, $sViewImagePopupContent, true);
    }

    protected function _getTmplVarsAuthor($aCmt)
    {
    	list($sAuthorName, $sAuthorLink, $sAuthorIcon) = $this->_getAuthorInfo($aCmt['cmt_author_id']);
    	$bAuthorIcon = !empty($sAuthorIcon);

    	return array(
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
            'bx_if:show_author_link' => array(
                'condition' => !empty($sAuthorLink),
                'content' => array(
                    'author_link' => $sAuthorLink,
            		'author_title' => bx_html_attribute($sAuthorName),
                    'author_name' => $sAuthorName
                )
            ),
            'bx_if:show_author_text' => array(
                'condition' => empty($sAuthorLink),
                'content' => array(
                    'author_name' => $sAuthorName
                )
            ),
    	);
    }
    protected function _getTmplVarsText($aCmt)
    {
    	$sText = $aCmt['cmt_text'];
        $sTextMore = '';

        $iMaxLength = (int)$this->_aSystem['chars_display_max'];
        if(strlen($sText) > $iMaxLength) {
            $iLength = strpos($sText, ' ', $iMaxLength);

            $sTextMore = trim(substr($sText, $iLength));
            $sText = trim(substr($sText, 0, $iLength));
        }

        $sText = $this->_prepareTextForOutput($sText, $aCmt['cmt_id']);
        $sTextMore = $this->_prepareTextForOutput($sTextMore, $aCmt['cmt_id']);

        return array(
			'text' => $sText,
            'bx_if:show_more' => array(
                'condition' => !empty($sTextMore),
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'js_object' => $this->_sJsObjName,
                    'text_more' => $sTextMore
                )
            ),
        );
    }
    protected function _echoResultJson($a, $isAutoWrapForFormFileSubmit = false)
    {

        header('Content-type: text/html; charset=utf-8');

        $s = json_encode($a);
        if ($isAutoWrapForFormFileSubmit && !empty($_FILES))
            $s = '<textarea>' . $s . '</textarea>'; // http://jquery.malsup.com/form/#file-upload
        echo $s;
    }
}

/** @} */
